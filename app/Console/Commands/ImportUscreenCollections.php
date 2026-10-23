<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\Video;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:import-uscreen-collections {file : Path to the Uscreen collections JSON} {--dry-run : Validate and preview without saving}')]
#[Description('Import Uscreen collections, tiers, thumbnails, and video relationships from JSON')]
class ImportUscreenCollections extends Command
{
    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("JSON file not found: {$file}");
            return self::FAILURE;
        }

        $jsonContent = file_get_contents($file);
        $data = json_decode($jsonContent, true);

        if (! $data || ! isset($data['collections'])) {
            $this->error('Invalid JSON structure.');
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN: no database changes will be made.');
        }

        $collectionsCount = 0;
        $videosLinkedCount = 0;

        foreach ($data['collections'] as $item) {
            $detail = $item['detail'] ?? [];
            $listItem = $item['list_item'] ?? [];

            $uscreenId = $detail['id'] ?? $listItem['id'] ?? null;
            $title = trim($detail['title'] ?? $listItem['title'] ?? '');

            if (! $uscreenId || $title === '') {
                continue;
            }

            $thumbnailUrl = $detail['small_horizontal_image_url'] 
                ?? $listItem['horizontal_preview_url'] 
                ?? null;

            $isFree = $detail['free'] ?? false;
            $planIds = $detail['subscription_plan_ids'] ?? [];

            $collectionAttributes = [
                'title' => $title,
                'slug' => $detail['permalink'] ?? null,
                'short_description' => $detail['short_description'] ?? null,
                'description' => $detail['description'] ?? null,
                'thumbnail_url' => $thumbnailUrl,
                'is_free' => $isFree,
                'subscription_plan_ids' => json_encode($planIds),
                'category_ids' => json_encode($detail['category_ids'] ?? []),
            ];

            if (! $dryRun) {
                $collection = Collection::updateOrCreate(
                    ['uscreen_id' => $uscreenId],
                    $collectionAttributes
                );
            }

            $collectionsCount++;

            // process playlist_items (video mappings)
            $playlistItems = $detail['playlist_items'] ?? [];

            foreach ($playlistItems as $playlistItem) {
                $videoUscreenId = $playlistItem['subject_id'] ?? null;
                $position = $playlistItem['position'] ?? 0;

                if (! $videoUscreenId) {
                    continue;
                }

                $video = Video::where('uscreen_id', $videoUscreenId)->first();

                if ($video) {
                    // update video level access and thumbnail if missing
                    $videoThumbnail = $playlistItem['subject_thumbnail_url'] ?? null;
                    
                    if (! $dryRun) {
                        $video->update([
                            'access' => $isFree ? 'free' : 'members',
                            'thumbnail_url' => $video->thumbnail_url ?: $videoThumbnail,
                        ]);

                        $collection->videos()->syncWithoutDetaching([
                            $video->id => ['position' => $position]
                        ]);
                    }
                    $videosLinkedCount++;
                }
            }
        }

        $this->newLine();
        $this->info('===== Collections Import Summary =====');
        $this->info("Collections Processed: {$collectionsCount}");
        $this->info("Videos Linked to Collections: {$videosLinkedCount}");
        $this->info('======================================');

        return self::SUCCESS;
    }
}