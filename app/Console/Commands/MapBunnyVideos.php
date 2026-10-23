<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\BunnyStreamService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:map-bunny-videos {--dry-run : Preview matching without saving changes}')]
#[Description('Fetch videos from Bunny.net Stream library and map bunny_guid into the videos table')]
class MapBunnyVideos extends Command
{
    public function handle(BunnyStreamService $bunnyService): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN: no database changes will be made.');
        }

        $this->info('Fetching videos from Bunny.net Stream library...');

        $page = 1;
        $matchedCount = 0;
        $unmatchedCount = 0;

        do {
            $result = $bunnyService->getLibraryVideos($page);
            $bunnyVideos = $result['items'] ?? [];
            $totalItems = $result['totalItems'] ?? 0;

            if (empty($bunnyVideos)) {
                break;
            }

            foreach ($bunnyVideos as $bunnyVideo) {
                $guid = $bunnyVideo['guid'] ?? null;
                $bunnyTitle = trim($bunnyVideo['title'] ?? '');

                if (! $guid || $bunnyTitle === '') {
                    continue;
                }

                // Match exact title or partial title
                $video = Video::where('title', $bunnyTitle)
                    ->orWhere('title', 'LIKE', '%' . $bunnyTitle . '%')
                    ->first();

                if ($video) {
                    if (! $dryRun) {
                        $video->update([
                            'bunny_guid' => $guid,
                            'status' => 'ready',
                        ]);
                    }
                    $matchedCount++;
                    $this->info("Matched: '{$bunnyTitle}' => Video ID #{$video->id} (GUID: {$guid})");
                } else {
                    $unmatchedCount++;
                    $this->warn("Unmatched in DB: '{$bunnyTitle}' (Bunny GUID: {$guid})");
                }
            }

            $page++;
        } while (($page - 1) * 100 < $totalItems);

        $this->newLine();
        $this->info('===== Bunny Mapping Summary =====');
        $this->info("Successfully Matched: {$matchedCount}");
        $this->info("Unmatched Videos: {$unmatchedCount}");
        $this->info('=================================');

        return self::SUCCESS;
    }
}