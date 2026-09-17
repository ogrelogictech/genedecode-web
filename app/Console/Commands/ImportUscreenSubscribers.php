<?php

namespace App\Console\Commands;

use App\Models\UscreenSubscriber;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:import-uscreen-subscribers {file : Path to the Uscreen CSV file}')]
#[Description('Import Uscreen subscriber data into the migration staging table')]
class ImportUscreenSubscribers extends Command
{
    public function handle(): int
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("CSV file not found: {$file}");

            return self::FAILURE;
        }

        $handle = fopen($file, 'r');

        if ($handle === false) {
            $this->error('Unable to open CSV file.');

            return self::FAILURE;
        }

        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);

            $this->error('CSV file is empty.');

            return self::FAILURE;
        }

        $headers = array_map('trim', $headers);

        $expectedHeaders = [
            'User ID',
            'User Name',
            'User Email',
            'Subscription ID',
            'Subscription Plan',
            'Billing Interval',
            'Origin',
            'Is Migrated',
            'Subscription Start Date',
            'Canceled At',
            'Churn Date',
            'Next Invoice At',
            'Subscription Status',
            'Lifetime Spend',
        ];

        if ($headers !== $expectedHeaders) {
            fclose($handle);

            $this->error('CSV headers do not match the expected Uscreen report.');

            return self::FAILURE;
        }

        $this->info('CSV headers validated successfully.');

        $imported = 0;

        DB::transaction(function () use ($handle, &$imported) {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== 14) {
                    throw new \RuntimeException(
                        'Invalid CSV row: expected 14 columns, found ' . count($row)
                    );
                }

                UscreenSubscriber::create([
                    'uscreen_user_id' => trim($row[0]),
                    'user_name' => trim($row[1]),
                    'user_email' => trim($row[2]),
                    'uscreen_subscription_id' => trim($row[3]),
                    'subscription_plan' => trim($row[4]),
                    'billing_interval' => trim($row[5]),
                    'origin' => trim($row[6]),
                    'is_migrated' => strtolower(trim($row[7])) === 'true',

                    'subscription_start_date' => $this->parseDate($row[8]),
                    'canceled_at' => $this->parseDate($row[9]),
                    'churn_date' => $this->parseDate($row[10]),
                    'next_invoice_at' => $this->parseDate($row[11]),

                    'subscription_status' => trim($row[12]),
                    'lifetime_spend' => $this->parseAmount($row[13]),
                ]);

                $imported++;

                if ($imported % 500 === 0) {
                    $this->info("Processed {$imported} records...");
                }
            }
        });

        fclose($handle);

        $this->newLine();
        $this->info("Successfully imported {$imported} Uscreen subscriber records.");

        return self::SUCCESS;
    }

    private function parseDate(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return date('Y-m-d H:i:s', strtotime($value));
    }

    private function parseAmount(?string $value): ?float
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return (float) str_replace([',', '$'], '', $value);
    }
}