<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Video;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function deepDives(Request $request)
    {
        $categoryFilter = $request->query('category', 'all');

        $videos = Video::where('access', 'members')
            ->when($categoryFilter !== 'all', function ($query) use ($categoryFilter) {
                if ($categoryFilter === 'deep-dive') {
                    $query->where(function ($q) {
                        $q->whereNull('section')
                          ->orWhere('section', 'LIKE', '%deep%')
                          ->orWhere('section', 'LIKE', '%decode%');
                    });
                } elseif ($categoryFilter === 'pearls') {
                    $query->where('section', 'LIKE', '%pearl%');
                } elseif ($categoryFilter === 'qna') {
                    $query->where(function ($q) {
                        $q->where('section', 'LIKE', '%q&a%')
                          ->orWhere('section', 'LIKE', '%qna%')
                          ->orWhere('title', 'LIKE', '%q&a%');
                    });
                }
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('site.deep-dives', compact('videos', 'categoryFilter'));
    }

    public function interviews(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = Video::where('access', 'free')
            ->orWhere('section', 'LIKE', '%interview%');

        if ($filter === 'newest') {
            $query->latest('uploaded_at');
        } else {
            $query->latest();
        }

        $videos = $query->paginate(25)->withQueryString();

        return view('site.interviews', compact('videos', 'filter'));
    }

    public function surfaceArea()
    {
        $freeVideos = Video::where('access', 'free')
            ->orWhere('section', 'LIKE', '%free%')
            ->latest()
            ->paginate(25);

        return view('site.surface-area', compact('freeVideos'));
    }
}