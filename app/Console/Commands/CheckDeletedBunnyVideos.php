<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\BunnyStreamService;
use Illuminate\Console\Command;

class CheckDeletedBunnyVideos extends Command
{
    protected $signature = 'bunny:check-deleted';
    protected $description = 'Hide Bunny deleted videos';

    public function handle(BunnyStreamService $bunny)
    {
        $bunnyGuids = [];
        $page = 1;

        do {
            $res = $bunny->getLibraryVideos($page, 100);

            if (! isset($res['items'])) {
                $this->error('No respose found for Bunny API.');
                return self::FAILURE;
            }

            foreach ($res['items'] as $v) {
                $bunnyGuids[] = $v['guid'];
            }

            $total = (int) ($res['totalItems'] ?? 0);
            $page++;
        } while (! empty($res['items']) && count($bunnyGuids) < $total);

        $dbGuids = Video::whereNotNull('bunny_guid')->pluck('bunny_guid')->all();

        $missing = array_diff($dbGuids, $bunnyGuids);
        $present = array_intersect($dbGuids, $bunnyGuids);

        foreach (array_chunk($missing, 500) as $chunk) {
            Video::whereIn('bunny_guid', $chunk)->update(['bunny_deleted' => true]);
        }
        foreach (array_chunk($present, 500) as $chunk) {
            Video::whereIn('bunny_guid', $chunk)->update(['bunny_deleted' => false]);
        }

        $this->info('Hidden: ' . count($missing) . ' | Visible: ' . count($present));
        return self::SUCCESS;
    }
}