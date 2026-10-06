<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this video.');
        }

        $catalog = [
            'v40vxe6' => [
                'id'          => 'v40vxe6',
                'title'       => 'Envisioning Freedom',
                'category'    => 'Deep Dive',
                'description' => 'Members get the complete session plus the companion notes and the live Q&A replay. Your progress is saved automatically.',
                'meta'        => 'Included with membership',
                'duration'    => '45 mins'
            ],
            'v40vxe7' => [
                'id'          => 'v40vxe7',
                'title'       => 'Unlocking Gene Codes',
                'category'    => 'Deep Dive',
                'description' => 'An in-depth session exploring fundamental frequency alignments and personal decoding strategies.',
                'meta'        => 'Included with membership',
                'duration'    => '38 mins'
            ],
            'v40vxe8' => [
                'id'          => 'v40vxe8',
                'title'       => 'Live Q&A Session Replay',
                'category'    => 'Community',
                'description' => 'Replay of the monthly live Q&A covering subscriber questions and system updates.',
                'meta'        => 'Included with membership',
                'duration'    => '52 mins'
            ],
            '76a8eecd-f70e-4097-a01a-f2f663a3cf74' => [
                'id' => '76a8eecd-f70e-4097-a01a-f2f663a3cf74',
                'title' => 'GeneDecode Test Video',
                'category' => 'Deep Dive',
                'duration' => 'Test',
                'description' => 'Test video for Bunny.net integration.',
                'meta' => 'Bunny test video',
            ],
        ];

        $id = $videoId ?? $request->query('v') ?? 'v40vxe6';
        $video = $catalog[$id] ?? [
            'id'          => $id,
            'title'       => 'Gene Decode Video',
            'category'    => 'Deep Dive',
            'description' => 'Members get full access to session recordings and companion notes.',
            'meta'        => 'Included with membership',
            'duration'    => ''
        ];

        $embedUrl = $this->bunnyService->generateEmbedUrl($id);

        $upNextVideos = array_filter($catalog, fn($item) => $item['id'] !== $id);

        return view('site.watch', [
            'embedUrl'     => $embedUrl,
            'videoId'      => $id,
            'video'        => $video,
            'upNextVideos' => $upNextVideos
        ]);
    }
}