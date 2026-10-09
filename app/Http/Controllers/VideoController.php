<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::public();
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }
        if ($request->filled('q')) {
            $term = '%'.addcslashes(mb_substr($request->query('q'), 0, 100), '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('title_fa', 'like', $term)->orWhere('title_en', 'like', $term);
            });
        }

        $videos = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Video::public()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('videos.index', compact('videos', 'categories'));
    }

    public function show($slug)
    {
        $video = Video::public()->where('slug', $slug)->with('service')->firstOrFail();
        $related = Video::public()->where('id', '!=', $video->id)
            ->when($video->category, fn ($q) => $q->where('category', $video->category))
            ->latest('published_at')->take(3)->get();

        return view('videos.show', compact('video', 'related'));
    }
}
