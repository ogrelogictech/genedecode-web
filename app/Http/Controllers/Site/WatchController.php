<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\BunnyStreamService;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    protected BunnyStreamService $bunnyService;

    public function __construct(BunnyStreamService $bunnyService)
    {
        $this->bunnyService = $bunnyService;
    }

    public function show(Request $request, string $videoId = null)
    {
        
        // if (! auth()->check()) {
        //     return redirect()->route('login')->with('error', 'Please log in to access this video.');
        // }

        // $user = auth()->user();

       
        // if (! $user->hasActiveSubscription()) {
        //     return redirect()->route('deep-dives')->with('error', 'Active membership required to access this video.');
        // }

        
        $id = $videoId ?? $request->query('v');
        $video = null;

        if ($id) {
            $video = Video::where('id', $id)
                ->orWhere('uscreen_id', $id)
                ->orWhere('bunny_guid', $id)
                ->first();
        }

        if (! $video) {
            $video = Video::whereNotNull('bunny_guid')->first() ?? Video::first();
        }

        if (! $video) {
            abort(404, 'No videos found in database.');
        }

        $section = strtolower((string) $video->section);
        $isFree  = $video->access === 'free'
                   || str_contains($section, 'free')
                   || str_contains($section, 'interview');

        if (! $isFree) {
            if (! auth()->check()) {
                return redirect()->route('login')->with('error', 'Please log in to access this video.');
            }

            if (! auth()->user()->hasActiveSubscription()) {
                return redirect()->route('deep-dives')->with('error', 'Active membership required to access this video.');
            }
        }

        if ($video->bunny_deleted) {
            abort(404, 'This video is no longer available.');
        }

        // Generate embed stream URL if bunny_guid exists
        // $guidToPlay = $video->bunny_guid ?: 'd9e6fdb3-2092-4770-9ecf-ceb9524590fb';
        $guidToPlay = $video->bunny_guid ?: '3a8f6e22-d8c7-499a-88ea-b8996e141152';
        $embedUrl = $this->bunnyService->generateEmbedUrl($guidToPlay);

        // $resumeSeconds = 0;
        // $saved = \App\Models\VideoProgress::where('user_id', $user->id)
        //     ->where('video_id', (string) $video->id)
        //     ->first();

        // if ($saved && $saved->progress_percent < 95 && $saved->progress_seconds > 5) {
        //     $resumeSeconds = (int) $saved->progress_seconds;
        // }
        $resumeSeconds = 0;
        if (auth()->check()) {
            $saved = \App\Models\VideoProgress::where('user_id', auth()->id())
                ->where('video_id', (string) $video->id)
                ->first();

            if ($saved && $saved->progress_percent < 95 && $saved->progress_seconds > 5) {
                $resumeSeconds = (int) $saved->progress_seconds;
            }
        }

        // $upNextVideos = Video::where('id', '!=', $video->id)
        //     ->where('bunny_deleted', false)
        //     ->latest()
        //     ->take(10)
        //     ->get();


        $from = $request->query('from');
        if (! in_array($from, ['surface-area', 'interviews', 'deep-dives'], true)) {
            $from = $isFree ? 'surface-area' : 'deep-dives';
        }

        $upNextQuery = Video::where('id', '!=', $video->id)
            ->where('bunny_deleted', false);

        if ($from === 'surface-area') {
            $upNextQuery->where(function ($q) {
                $q->where('access', 'free')
                  ->orWhere('section', 'LIKE', '%free%');
            });
        } elseif ($from === 'interviews') {
            $upNextQuery->where(function ($q) {
                $q->where('access', 'free')
                  ->orWhere('section', 'LIKE', '%interview%');
            });
        } else {
            $upNextQuery->where('access', 'members');
        }

        $upNextVideos = $upNextQuery->latest('uploaded_at')->take(10)->get();

        return view('site.watch', [
            'embedUrl'     => $embedUrl,
            'videoId'      => $video->id,
            'video'        => $video,
            'upNextVideos' => $upNextVideos,
            'resumeSeconds' => $resumeSeconds,
            'from'          => $from,
        ]);
    }
}