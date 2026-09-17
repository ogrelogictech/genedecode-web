<?php

namespace App\Console\Commands;

use App\Models\Membership;
use App\Models\UscreenSubscriber;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Signature('app:migrate-uscreen-subscribers {--dry-run : Preview the migration without making changes} {--limit= : Limit the number of records to process} {--id= : Migrate a specific Uscreen subscriber ID} {--retry-failed : Retry previously failed migrations}')]
#[Description('Migrate Uscreen subscribers into users and memberships')]
class MigrateUscreenSubscribers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $total = UscreenSubscriber::count();

        $this->info("Found {$total} Uscreen subscriber records.");

        if ($dryRun) {
            $this->warn('DRY RUN: No database changes will be made.');
        }

        $processedRecords = 0;
        $successfulMigrations = 0;
        $existingUsers = 0;
        $newUsers = 0;
        $monthlySubscriptions = 0;
        $annualSubscriptions = 0;
        $activeSubscriptions = 0;
        $onHoldSubscriptions = 0;
        $pausedSubscriptions = 0;
        $failedRecords = 0;

        $limit = $this->option('limit');

        $query = UscreenSubscriber::query();

        if ($this->option('retry-failed')) {
            $query->where('migration_status', 'failed');
        } else {
            $query->where('migration_status', 'pending');
        }

        $id = $this->option('id');

        if ($id !== null) {
            $query->where('id', (int) $id);
        }

        if ($limit !== null) {
            $query->limit((int) $limit);
        }

        foreach ($query->cursor() as $subscriber) {

            $processedRecords++;
            $membershipStatus = match ($subscriber->subscription_status) {
                'Active' => 'active',
                'On Hold' => 'on_hold',
                'Paused' => 'paused',
                default => 'active',
            };

            $membershipPlan = match ($subscriber->billing_interval) {
                'Monthly' => 'monthly',
                'Annual' => 'annual',
                default => null,
            };

            if ($dryRun) {
                $user = User::where('email', $subscriber->user_email)->first();

                if ($user) {
                    $existingUsers++;
                } else {
                    $newUsers++;
                }
            } else {

                try {
                    DB::transaction(function () use (
                        $subscriber,
                        $membershipStatus,
                        $membershipPlan,
                        &$existingUsers,
                        &$newUsers
                    ) {
                        $user = User::where('email', $subscriber->user_email)->first();

                        if ($user) {
                            $existingUsers++;
                        } else {
                            $newUsers++;

                            $user = User::create([
                                'name' => $subscriber->user_name,
                                'email' => $subscriber->user_email,
                                'password' => Str::random(64),
                            ]);

                            $this->line("Created user: {$user->email}");
                        }

                        $membership = Membership::where('user_id', $user->id)->first();

                        if ($membership) {
                            if ($membership->uscreen_subscription_id === $subscriber->uscreen_subscription_id) {
                                $this->line("Membership already exists and matches Uscreen subscription for: {$user->email}");
                            } else {
                                throw new \RuntimeException(
                                    "Existing membership has a different Uscreen subscription ID for {$user->email}."
                                );
                            }
                        } else {
                            Membership::create([
                                'user_id' => $user->id,
                                'plan' => $membershipPlan,
                                'status' => $membershipStatus,
                                'started_at' => $subscriber->subscription_start_date,
                                'expires_at' => null,
                                'canceled_at' => $subscriber->canceled_at,
                                'uscreen_subscription_id' => $subscriber->uscreen_subscription_id,
                            ]);

                            $this->line("Created membership for: {$user->email}");
                        }

                        $subscriber->update([
                            'migration_status' => 'migrated',
                            'migrated_at' => now(),
                            'migration_error' => null,
                        ]);

                        $this->line("Migration completed for: {$user->email}");
                        
                    });

                    $successfulMigrations++;
                } catch (\Throwable $e) {
                    $failedRecords++;

                    $subscriber->update([
                        'migration_status' => 'failed',
                        'migration_error' => $e->getMessage(),
                    ]);

                    $this->error(
                        "Migration failed for {$subscriber->user_email}: {$e->getMessage()}"
                    );
                }
            }

            if ($subscriber->billing_interval === 'Monthly') {
                $monthlySubscriptions++;
            }

            if ($subscriber->billing_interval === 'Annual') {
                $annualSubscriptions++;
            }

            if ($subscriber->subscription_status === 'Active') {
                $activeSubscriptions++;
            }

            if ($subscriber->subscription_status === 'On Hold') {
                $onHoldSubscriptions++;
            }

            if ($subscriber->subscription_status === 'Paused') {
                $pausedSubscriptions++;
            }
        }

        $this->newLine();
        $this->info('===== Migration Summary =====');
        $this->info("Processed records: {$processedRecords}");
        $this->info("Successful migrations: {$successfulMigrations}");
        $this->info("Failed records: {$failedRecords}");
        $this->info("Existing users: {$existingUsers}");
        $this->info("New users created: {$newUsers}");
        $this->info("Monthly subscriptions: {$monthlySubscriptions}");
        $this->info("Annual subscriptions: {$annualSubscriptions}");
        $this->info("Active subscriptions: {$activeSubscriptions}");
        $this->info("On Hold subscriptions: {$onHoldSubscriptions}");
        $this->info("Paused subscriptions: {$pausedSubscriptions}");

        $remainingPending = UscreenSubscriber::where('migration_status', 'pending')->count();

        $this->info("Remaining pending records: {$remainingPending}");
        $this->info('=============================');
    }
}