<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::public();
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }
        if ($request->filled('q')) {
            $term = '%'.addcslashes(mb_substr($request->query('q'), 0, 100), '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('title_fa', 'like', $term)->orWhere('title_en', 'like', $term)
                  ->orWhere('excerpt_fa', 'like', $term)->orWhere('excerpt_en', 'like', $term);
            });
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Article::public()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('blog.index', compact('articles', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::public()->where('slug', $slug)->with('service')->firstOrFail();
        $related = Article::public()->where('id', '!=', $article->id)
            ->when($article->category, fn ($q) => $q->where('category', $article->category))
            ->latest('published_at')->take(3)->get();

        return view('blog.show', compact('article', 'related'));
    }
}
