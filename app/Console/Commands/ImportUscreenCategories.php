<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Collection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('app:import-uscreen-categories {file : Path to the Uscreen collections JSON} {--dry-run : Validate and preview without saving}')]
#[Description('Import Uscreen categories and link them with collections')]
class ImportUscreenCategories extends Command
{
    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("JSON file not found: {$file}");
            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($file), true);

        if (! $data || ! isset($data['lookups']['available_categories'])) {
            $this->error('Invalid JSON structure or missing available_categories.');
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN: no database changes will be made.');
        }

        $categoriesCount = 0;
        $categoryMap = [];

        // 1. Import all categories from lookups
        foreach ($data['lookups']['available_categories'] as $cat) {
            $uscreenId = $cat['id'] ?? null;
            $title = trim($cat['title'] ?? '');

            if (! $uscreenId || $title === '') {
                continue;
            }

            $attributes = [
                'title' => $title,
                'slug' => Str::slug($title),
            ];

            if (! $dryRun) {
                $category = Category::updateOrCreate(
                    ['uscreen_id' => $uscreenId],
                    $attributes
                );
                $categoryMap[$uscreenId] = $category->id;
            }

            $categoriesCount++;
        }

        // 2. Link categories with collections
        $linksCount = 0;
        if (! $dryRun && isset($data['collections'])) {
            foreach ($data['collections'] as $item) {
                $detail = $item['detail'] ?? [];
                $collectionUscreenId = $detail['id'] ?? $item['list_item']['id'] ?? null;

                if (! $collectionUscreenId) {
                    continue;
                }

                $collection = Collection::where('uscreen_id', $collectionUscreenId)->first();

                if ($collection && ! empty($detail['category_ids'])) {
                    $categoryDbIds = [];
                    foreach ($detail['category_ids'] as $catUscreenId) {
                        if (isset($categoryMap[$catUscreenId])) {
                            $categoryDbIds[] = $categoryMap[$catUscreenId];
                        }
                    }

                    if (! empty($categoryDbIds)) {
                        $collection->categories()->syncWithoutDetaching($categoryDbIds);
                        $linksCount += count($categoryDbIds);
                    }
                }
            }
        }

        $this->newLine();
        $this->info('===== Categories Import Summary =====');
        $this->info("Categories Processed: {$categoriesCount}");
        $this->info("Collection-Category Links: {$linksCount}");
        $this->info('=====================================');

        return self::SUCCESS;
    }
}