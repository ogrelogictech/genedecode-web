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
       
        $id = $videoId ?? $request->query('v') ?? 'v40vxe6';

        $embedUrl = $this->bunnyService->generateEmbedUrl($id);

        return view('site.watch', [
            'embedUrl' => $embedUrl,
            'videoId' => $id
        ]);
    }
}