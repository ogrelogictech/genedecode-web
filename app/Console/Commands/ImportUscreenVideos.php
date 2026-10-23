<?php

namespace App\Console\Commands;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:import-uscreen-videos {file : Path to the Uscreen videos CSV} {--dry-run : Validate and preview without saving}')]
#[Description('Import Uscreen video metadata (videos.csv) into the videos table')]
class ImportUscreenVideos extends Command
{
    private const HEADERS = [
        'ID',
        'Title',
        'Duration (Seconds)',
        'Uploaded On',
        'Short Description',
        'Description',
        'Author',
    ];

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("CSV file not found: {$file}");
            return self::FAILURE;
        }

        $handle = fopen($file, 'r');
        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);
            $this->error('CSV file is empty.');
            return self::FAILURE;
        }

        // strip UTF-8 BOM if present
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        $headers = array_map('trim', $headers);

        $missing = array_diff(self::HEADERS, $headers);

        if ($missing) {
            fclose($handle);
            $this->error('Missing CSV columns: ' . implode(', ', $missing));
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN: no database changes will be made.');
        }

        $created = $updated = $skipped = $badDates = $emptyDesc = 0;
        $line = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            if (count($row) !== count($headers)) {
                $skipped++;
                $this->warn("Line {$line}: expected " . count($headers) . ' columns, found ' . count($row) . '. Skipped.');
                continue;
            }

            $data = array_combine($headers, $row);

            $uscreenId = trim($data['ID']);
            $title = trim($data['Title']);

            if ($uscreenId === '' || $title === '') {
                $skipped++;
                $this->warn("Line {$line}: missing ID or Title. Skipped.");
                continue;
            }

            $rawDate = trim($data['Uploaded On']);
            $uploadedAt = $this->parseDate($rawDate);

            if ($rawDate !== '' && $uploadedAt === null) {
                $badDates++;
                $this->warn("Line {$line}: could not parse date '{$rawDate}'.");
            }

            $description = $this->cleanHtml($data['Description']);

            if ($description === null) {
                $emptyDesc++;
            }

            $attributes = [
                'title' => $title,
                'short_description' => trim($data['Short Description']) ?: null,
                'description' => $description,
                'duration_seconds' => (int) trim($data['Duration (Seconds)']),
                'uploaded_at' => $uploadedAt,
                'author' => trim($data['Author']) ?: null,
            ];

            if ($dryRun) {
                if (Video::where('uscreen_id', $uscreenId)->exists()) {
                    $updated++;
                } else {
                    $created++;
                }
                continue;
            }

            $video = Video::updateOrCreate(['uscreen_id' => $uscreenId], $attributes);

            if ($video->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info('===== Import Summary =====');
        $this->info("Created: {$created}");
        $this->info("Updated: {$updated}");
        $this->info("Skipped: {$skipped}");
        $this->info("Unparsed dates: {$badDates}");
        $this->info("Empty descriptions: {$emptyDesc}");
        $this->info('==========================');

        return self::SUCCESS;
    }

    private function parseDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    private function cleanHtml(?string $html): ?string
    {
        $html = trim((string) $html);

        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // treat "<p></p>", "<p>&nbsp;</p>" etc. as empty
        if (preg_replace('/[\s\x{00A0}]+/u', '', $text) === '') {
            return null;
        }

        return $html;
    }
}