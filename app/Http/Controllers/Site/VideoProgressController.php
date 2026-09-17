<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\VideoProgress;
use Illuminate\Http\Request;

class VideoProgressController extends Controller
{

    public function index(Request $request)
    {
        $progress = VideoProgress::where('user_id', $request->user()->id)
            ->where('progress_percent', '>', 0)
            ->where('progress_percent', '<', 90)
            ->orderByDesc('last_watched_at')
            ->get();

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

    public function show(Request $request, string $videoId)
    {
        $progress = VideoProgress::where('user_id', $request->user()->id)
            ->where('video_id', $videoId)
            ->first();

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_id' => ['required', 'string', 'max:255'],
            'progress_seconds' => ['required', 'numeric', 'min:0'],
            'duration_seconds' => ['required', 'numeric', 'min:0'],
            'progress_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $progress = VideoProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'video_id' => $validated['video_id'],
            ],
            [
                'progress_seconds' => $validated['progress_seconds'],
                'duration_seconds' => $validated['duration_seconds'],
                'progress_percent' => $validated['progress_percent'],
                'last_watched_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Video progress saved successfully.',
            'progress' => $progress,
        ]);
    }
}