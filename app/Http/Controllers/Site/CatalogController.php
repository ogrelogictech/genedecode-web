<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Video;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // public function deepDives(Request $request)
    // {
    //     $categoryFilter = $request->query('category', 'all');

    //     $videos = Video::where('bunny_deleted', false)
    //         ->where('access', 'members')
    //         ->when($categoryFilter !== 'all', function ($query) use ($categoryFilter) {
    //             if ($categoryFilter === 'deep-dive') {
    //                 $query->where(function ($q) {
    //                     $q->whereNull('section')
    //                     ->orWhere('section', 'LIKE', '%deep%')
    //                     ->orWhere('section', 'LIKE', '%decode%');
    //                 });
    //             } elseif ($categoryFilter === 'pearls') {
    //                 $query->where('section', 'LIKE', '%pearl%');
    //             } elseif ($categoryFilter === 'qna') {
    //                 $query->where(function ($q) {
    //                     $q->where('section', 'LIKE', '%q&a%')
    //                     ->orWhere('section', 'LIKE', '%qna%')
    //                     ->orWhere('title', 'LIKE', '%q&a%');
    //                 });
    //             }
    //         })
    //         ->latest('uploaded_at')
    //         ->paginate(25)
    //         ->withQueryString();

    //     return view('site.deep-dives', compact('videos', 'categoryFilter'));
    // }

    // public function interviews(Request $request)
    // {
    //     $filter = $request->query('filter', 'all');

    //     $query = Video::where('bunny_deleted', false)
    //         ->where(function ($q) {
    //             $q->where('access', 'free')
    //               ->orWhere('section', 'LIKE', '%interview%');
    //         });

        
    //     $query->latest('uploaded_at');
       

    //     $videos = $query->paginate(25)->withQueryString();

    //     return view('site.interviews', compact('videos', 'filter'));
    // }

    // public function surfaceArea()
    // {
    //     $freeVideos = Video::where('bunny_deleted', false)
    //         ->where(function ($q) {
    //             $q->where('access', 'free')
    //               ->orWhere('section', 'LIKE', '%free%');
    //         })
    //         ->latest('uploaded_at')
    //         ->paginate(25);

    //     return view('site.surface-area', compact('freeVideos'));
    // }

    public function deepDives(Request $request)
    {
        $categoryFilter = $request->query('category', 'all');
        $search = trim((string) $request->query('q', ''));

        $query = Video::where('bunny_deleted', false)
            ->where('access', 'members');

        // Search logic (Title aur Description)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Category filter chips
        if ($categoryFilter !== 'all') {
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
        }

        $videos = $query->latest('uploaded_at')
            ->paginate(24)
            ->withQueryString();

        return view('site.deep-dives', compact('videos', 'categoryFilter', 'search'));
    }

    public function interviews(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $search = trim((string) $request->query('q', ''));

        $query = Video::where('bunny_deleted', false)
            ->where(function ($q) {
                $q->where('access', 'free')
                  ->orWhere('section', 'LIKE', '%interview%');
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($filter === 'newest') {
            $query->latest('uploaded_at');
        } elseif ($filter === 'oldest') {
            $query->oldest('uploaded_at');
        } else {
            $query->latest('uploaded_at');
        }

        $videos = $query->paginate(24)->withQueryString();

        return view('site.interviews', compact('videos', 'filter', 'search'));
    }

    public function surfaceArea(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $query = Video::where('bunny_deleted', false)
            ->where(function ($q) {
                $q->where('access', 'free')
                  ->orWhere('section', 'LIKE', '%free%');
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $freeVideos = $query->latest('uploaded_at')
            ->paginate(24)
            ->withQueryString();

        return view('site.surface-area', compact('freeVideos', 'search'));
    }
}