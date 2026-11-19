<?php

namespace App\Console\Commands;

use App\Models\Membership;
use App\Models\UscreenSubscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Subscription;

#[Signature('app:reconcile-stripe
    {--dry-run : Preview reconciliation without updating database}
    {--limit= : Limit number of Stripe subscriptions to fetch for testing}
    {--create-missing : Create user & membership records for active Stripe subscribers missing from DB}
    {--export-report : Save detailed CSV reconciliation audit report to storage/app/reconciliation}')]
#[Description('Reconcile migrated Uscreen users and memberships against Stripe subscriptions')]
class ReconcileStripeSubscriptions extends Command
{
    public function handle(): int
    {
        $secret = config('services.stripe.secret');
        if (empty($secret)) {
            $this->error('STRIPE_SECRET is missing in config/services.php or .env');
            return self::FAILURE;
        }

        Stripe::setApiKey($secret);

        $dryRun = (bool) $this->option('dry-run');
        $createMissing = (bool) $this->option('create-missing');
        $exportReport = (bool) $this->option('export-report');
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;

        if ($dryRun) {
            $this->warn('DRY RUN MODE: No database writes will be performed.');
        }

        $this->info('Fetching subscriptions from Stripe...');

        $stats = [
            'stripe_fetched'      => 0,
            'matched_linked'      => 0,
            'stripe_only_created' => 0,
            'stripe_only_skipped' => 0,
            'skipped_no_email'    => 0,
            'errors'              => 0,
        ];

        $reportRows = [];
        $matchedUserIds = [];

        $params = [
            'status' => 'all',
            'limit'  => 100,
            'expand' => ['data.customer'],
        ];

        $hasMore = true;
        $startingAfter = null;

        while ($hasMore) {
            if ($startingAfter) {
                $params['starting_after'] = $startingAfter;
            }

            $subscriptions = Subscription::all($params);

            if (empty($subscriptions->data)) {
                break;
            }

            foreach ($subscriptions->data as $sub) {
                $startingAfter = $sub->id;
                $stats['stripe_fetched']++;

                if ($limit !== null && $stats['stripe_fetched'] > $limit) {
                    $hasMore = false;
                    break;
                }

                // We care about active, trialing, past_due, and recently canceled subscriptions
                if (!in_array($sub->status, ['active', 'trialing', 'past_due', 'canceled', 'unpaid'], true)) {
                    continue;
                }

                $customer = $sub->customer;
                $email = is_object($customer) ? strtolower(trim((string) ($customer->email ?? ''))) : '';
                $customerName = is_object($customer) ? trim((string) ($customer->name ?? '')) : '';
                $customerId = is_object($customer) ? $customer->id : (string) $customer;

                if ($email === '') {
                    $stats['skipped_no_email']++;
                    $reportRows[] = [
                        'bucket' => 'SKIPPED_NO_EMAIL',
                        'email' => '',
                        'stripe_customer_id' => $customerId,
                        'stripe_subscription_id' => $sub->id,
                        'stripe_status' => $sub->status,
                        'db_status' => '',
                        'plan' => '',
                        'expires_at' => '',
                        'notes' => 'Customer object has no email address in Stripe',
                    ];
                    continue;
                }

                $subItem = $sub->items->data[0] ?? null;
                $priceId = $subItem->price->id ?? ($subItem->plan->id ?? null);
                $interval = $subItem->price->recurring->interval
                    ?? $subItem->plan->interval
                    ?? null;
                $intervalCount = (int) ($subItem->price->recurring->interval_count
                    ?? $subItem->plan->interval_count
                    ?? 1);
                $unitAmount = (int) ($subItem->price->unit_amount
                    ?? $subItem->plan->amount
                    ?? 0);

                if (
                    $priceId === 'price_1QW0NMJJKwXzuCD8iavgBhTj' ||
                    $interval === 'year' ||
                    ($interval === 'month' && $intervalCount >= 12) ||
                    $unitAmount >= 5000
                ) {
                    $plan = 'annual';
                } else {
                    $plan = 'monthly';
                }

                $mappedStatus = $this->mapStripeStatus($sub);
                $startedAt = isset($sub->start_date) ? Carbon::createFromTimestamp($sub->start_date) : Carbon::now();
                $periodEndTs = $sub->current_period_end ?? ($subItem->current_period_end ?? null);
                $expiresAt = $periodEndTs ? Carbon::createFromTimestamp($periodEndTs) : null;

                if ($sub->status === 'trialing' && !empty($sub->trial_end) && $sub->trial_end > time()) {
                    $startedAt = Carbon::createFromTimestamp($sub->trial_end);
                    $expiresAt = $plan === 'annual'
                        ? $startedAt->copy()->addYear()
                        : $startedAt->copy()->addMonth();
                }

                $canceledAt = isset($sub->canceled_at) && $sub->canceled_at
                    ? Carbon::createFromTimestamp($sub->canceled_at)
                    : null;

                // Match existing user by stripe_id first, then case-insensitive email
                $user = User::where('stripe_id', $customerId)->first()
                    ?? User::whereRaw('LOWER(email) = ?', [$email])->first();

                if ($user) {
                    $matchedUserIds[$user->id] = true;

                    try {
                        if (!$dryRun) {
                            DB::transaction(function () use (
                                $user,
                                $customerId,
                                $sub,
                                $priceId,
                                $plan,
                                $mappedStatus,
                                $startedAt,
                                $expiresAt,
                                $canceledAt
                            ) {
                                if ($user->stripe_id !== $customerId) {
                                    $user->update(['stripe_id' => $customerId]);
                                }

                                // Find existing non-gift membership for this user
                                $membership = Membership::where('user_id', $user->id)
                                    ->where('is_gift', false)
                                    ->orderByDesc('id')
                                    ->first();

                                // Guard: Never overwrite an active/cancelling/past_due membership with an older canceled Stripe sub
                                if (
                                    $membership &&
                                    !empty($membership->stripe_subscription_id) &&
                                    $membership->stripe_subscription_id !== $sub->id &&
                                    in_array($membership->status, ['active', 'cancelling', 'past_due'], true) &&
                                    $mappedStatus === 'cancelled'
                                ) {
                                    return;
                                }

                                // Guard: If DB already has a different active/cancelling sub with a LATER expiry date, keep the newer one
                                if (
                                    $membership &&
                                    !empty($membership->stripe_subscription_id) &&
                                    $membership->stripe_subscription_id !== $sub->id &&
                                    in_array($membership->status, ['active', 'cancelling'], true) &&
                                    in_array($mappedStatus, ['active', 'cancelling'], true) &&
                                    $membership->expires_at &&
                                    $expiresAt &&
                                    $membership->expires_at->gt($expiresAt)
                                ) {
                                    return;
                                }

                                $payload = [
                                    'stripe_subscription_id' => $sub->id,
                                    'stripe_price_id'        => $priceId,
                                    'plan'                   => $plan,
                                    'status'                 => $mappedStatus,
                                    'started_at'             => $membership?->started_at ?? $startedAt,
                                    'expires_at'             => $expiresAt ?? $membership?->expires_at,
                                    'canceled_at'            => $canceledAt ?? $membership?->canceled_at,
                                ];

                                if ($membership) {
                                    $membership->update($payload);
                                } else {
                                    Membership::create(array_merge($payload, [
                                        'user_id' => $user->id,
                                        'is_gift' => false,
                                    ]));
                                }
                            });
                        }

                        $stats['matched_linked']++;
                        $reportRows[] = [
                            'bucket' => 'MATCHED_LINKED',
                            'email' => $email,
                            'stripe_customer_id' => $customerId,
                            'stripe_subscription_id' => $sub->id,
                            'stripe_status' => $sub->status . ($sub->cancel_at_period_end ? ' (cancel_at_period_end)' : ''),
                            'db_status' => $mappedStatus,
                            'plan' => $plan,
                            'expires_at' => $expiresAt ? $expiresAt->toDateTimeString() : '',
                            'notes' => 'Linked to existing User ID #' . $user->id,
                        ];
                    } catch (\Throwable $e) {
                        $stats['errors']++;
                        $this->error("Failed linking {$email}: {$e->getMessage()}");
                    }
                } else {
                    // User exists in Stripe but NOT in local DB (e.g. signed up after Uscreen CSV export)
                    $isBillableOrActive = in_array($mappedStatus, ['active', 'cancelling', 'past_due'], true);

                    if ($createMissing && $isBillableOrActive) {
                        try {
                            if (!$dryRun) {
                                DB::transaction(function () use (
                                    $email,
                                    $customerName,
                                    $customerId,
                                    $sub,
                                    $priceId,
                                    $plan,
                                    $mappedStatus,
                                    $startedAt,
                                    $expiresAt,
                                    $canceledAt,
                                    &$matchedUserIds
                                ) {
                                    $newUser = User::create([
                                        'name'      => $customerName !== '' ? $customerName : Str::before($email, '@'),
                                        'email'     => $email,
                                        'password'  => Str::random(64),
                                        'stripe_id' => $customerId,
                                    ]);

                                    Membership::create([
                                        'user_id'                => $newUser->id,
                                        'stripe_subscription_id' => $sub->id,
                                        'stripe_price_id'        => $priceId,
                                        'plan'                   => $plan,
                                        'status'                 => $mappedStatus,
                                        'is_gift'                => false,
                                        'started_at'             => $startedAt,
                                        'expires_at'             => $expiresAt,
                                        'canceled_at'            => $canceledAt,
                                    ]);

                                    $matchedUserIds[$newUser->id] = true;
                                });
                            }

                            $stats['stripe_only_created']++;
                            $reportRows[] = [
                                'bucket' => 'STRIPE_ONLY_CREATED',
                                'email' => $email,
                                'stripe_customer_id' => $customerId,
                                'stripe_subscription_id' => $sub->id,
                                'stripe_status' => $sub->status,
                                'db_status' => $mappedStatus,
                                'plan' => $plan,
                                'expires_at' => $expiresAt ? $expiresAt->toDateTimeString() : '',
                                'notes' => 'Active in Stripe (missing in Uscreen CSV) — auto-created',
                            ];
                        } catch (\Throwable $e) {
                            $stats['errors']++;
                            $this->error("Failed creating {$email}: {$e->getMessage()}");
                        }
                    } else {
                        $stats['stripe_only_skipped']++;
                        $reportRows[] = [
                            'bucket' => 'STRIPE_ONLY_UNLINKED',
                            'email' => $email,
                            'stripe_customer_id' => $customerId,
                            'stripe_subscription_id' => $sub->id,
                            'stripe_status' => $sub->status,
                            'db_status' => $mappedStatus,
                            'plan' => $plan,
                            'expires_at' => $expiresAt ? $expiresAt->toDateTimeString() : '',
                            'notes' => $isBillableOrActive
                                ? 'Active in Stripe but missing in DB (run with --create-missing to import)'
                                : 'Inactive/canceled in Stripe and absent from DB',
                        ];
                    }
                }

                if ($stats['stripe_fetched'] % 250 === 0) {
                    $this->line("Fetched {$stats['stripe_fetched']} Stripe subscriptions...");
                }
            }

            if ($limit !== null && $stats['stripe_fetched'] >= $limit) {
                break;
            }

            $hasMore = $subscriptions->has_more;
        }

        // Check DB memberships that were NOT matched to any Stripe subscription
        $unlinkedMemberships = Membership::with('user')
            ->where('is_gift', false)
            ->whereNull('stripe_subscription_id')
            ->get();

        $dbWithoutStripeCount = 0;
        foreach ($unlinkedMemberships as $m) {
            if ($m->user && isset($matchedUserIds[$m->user->id])) {
                continue;
            }
            $dbWithoutStripeCount++;
            $reportRows[] = [
                'bucket' => 'DB_WITHOUT_STRIPE',
                'email' => $m->user->email ?? 'unknown',
                'stripe_customer_id' => $m->user->stripe_id ?? '',
                'stripe_subscription_id' => '',
                'stripe_status' => 'NOT_FOUND_IN_STRIPE',
                'db_status' => $m->status,
                'plan' => $m->plan,
                'expires_at' => $m->expires_at ? $m->expires_at->toDateTimeString() : '',
                'notes' => 'Present in Uscreen CSV (Uscreen Sub ID: ' . ($m->uscreen_subscription_id ?? 'N/A') . ') but no matching Stripe subscription found',
            ];
        }

        $this->newLine();
        $this->info('========== Stripe Reconciliation Summary ==========');
        $this->info("Stripe Subscriptions Scanned : {$stats['stripe_fetched']}");
        $this->info("Matched & Linked in DB       : {$stats['matched_linked']}");
        $this->info("Stripe-Only Created          : {$stats['stripe_only_created']}");
        $this->info("Stripe-Only Unlinked         : {$stats['stripe_only_skipped']}");
        $this->info("DB Memberships No Stripe Sub : {$dbWithoutStripeCount}");
        $this->info("Skipped (No Email in Stripe) : {$stats['skipped_no_email']}");
        $this->info("Errors                       : {$stats['errors']}");
        $this->info('===================================================');

        if ($exportReport) {
            $filename = 'reconciliation/stripe_reconcile_' . now()->format('Y-m-d_His') . ($dryRun ? '_dryrun' : '') . '.csv';
            $csvLines = [];
            $csvLines[] = implode(',', [
                'Bucket',
                'Email',
                'Stripe Customer ID',
                'Stripe Subscription ID',
                'Stripe Status',
                'Mapped DB Status',
                'Plan',
                'Expires At',
                'Notes',
            ]);

            foreach ($reportRows as $r) {
                $escaped = array_map(fn ($v) => '"' . str_replace('"', '""', (string) $v) . '"', array_values($r));
                $csvLines[] = implode(',', $escaped);
            }

            Storage::disk('local')->put($filename, implode("\n", $csvLines));
            $this->info('Audit CSV saved to: ' . Storage::disk('local')->path($filename));
        }

        return self::SUCCESS;
    }

    private function mapStripeStatus(object $sub): string
    {
        if ($sub->status === 'canceled') {
            return 'cancelled';
        }

        if (in_array($sub->status, ['past_due', 'unpaid'], true)) {
            return 'past_due';
        }

        if (in_array($sub->status, ['active', 'trialing'], true)) {
            return !empty($sub->cancel_at_period_end) ? 'cancelling' : 'active';
        }

        return 'active';
    }
}