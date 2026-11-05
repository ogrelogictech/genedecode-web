<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\BunnyStreamService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BunnyWebhookController extends Controller
{
    public function handle(Request $request, BunnyStreamService $bunny)
    {
    
        if ($request->query('token') !== config('services.bunny.webhook_token')) {
            abort(403);
        }

        $guid   = $request->input('VideoGuid');
        $status = (int) $request->input('Status');

        if (! $guid) {
            return response()->json(['ok' => false], 422);
        }

        if ($status !== 3) {
            return response()->json(['ok' => true, 'skipped' => true]);
        }

        $data = $bunny->getVideo($guid);
        if (! $data) {
            return response()->json(['ok' => false], 502); 
        }

        $title = preg_replace('/\.(mp4|mov|mkv|avi|webm|m4v|wmv|flv)$/i', '', (string) ($data['title'] ?? ''));
        $title = trim($title) !== '' ? trim($title) : 'Untitled';

        $thumb = rtrim((string) config('services.bunny.pull_zone_url'), '/')
            . '/' . $guid . '/' . ($data['thumbnailFileName'] ?? 'thumbnail.jpg');

        $video = Video::firstOrNew(['bunny_guid' => $guid]);

        if (! $video->exists) {
            $video->uscreen_id = 'bunny-' . $guid;
        }

        $video->fill([
            'title'            => $title,
            'duration_seconds' => (int) ($data['length'] ?? 0),
            'uploaded_at'      => isset($data['dateUploaded'])
                                    ? Carbon::parse($data['dateUploaded'])
                                    : now(),
            'thumbnail_url'    => $thumb,
        ]);
        $video->save();
    }
}