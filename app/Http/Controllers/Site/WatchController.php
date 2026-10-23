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
        // 1. Check if user is logged in
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this video.');
        }

        $user = auth()->user();

        // 2. Check if user has active membership subscription
        if (! $user->hasActiveSubscription()) {
            return redirect()->route('deep-dives')->with('error', 'Active membership required to access this video.');
        }

        // Fetch video by ID, uscreen_id, or bunny_guid
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

        // Generate embed stream URL if bunny_guid exists
        $guidToPlay = $video->bunny_guid ?: 'd9e6fdb3-2092-4770-9ecf-ceb9524590fb';
        $embedUrl = $this->bunnyService->generateEmbedUrl($guidToPlay);

        $resumeSeconds = 0;
        $saved = \App\Models\VideoProgress::where('user_id', $user->id)
            ->where('video_id', (string) $video->id)
            ->first();

        if ($saved && $saved->progress_percent < 95 && $saved->progress_seconds > 5) {
            $resumeSeconds = (int) $saved->progress_seconds;
        }

        $upNextVideos = Video::where('id', '!=', $video->id)
            ->latest()
            ->take(10)
            ->get();

        return view('site.watch', [
            'embedUrl'     => $embedUrl,
            'videoId'      => $video->id,
            'video'        => $video,
            'upNextVideos' => $upNextVideos,
            'resumeSeconds' => $resumeSeconds,
        ]);
    }
}