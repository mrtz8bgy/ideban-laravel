<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Resume;
use App\Models\Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = ['services' => collect(), 'plans' => collect(), 'portfolio' => collect(), 'articles' => collect(), 'team' => collect()];

        if (mb_strlen($q) >= 2) {
            $like = '%'.addcslashes($q, '%_\\').'%';

            $results['services'] = Service::where('is_active', true)
                ->where(fn ($w) => $w->where('title_fa', 'like', $like)->orWhere('title_en', 'like', $like)
                    ->orWhere('summary_fa', 'like', $like)->orWhere('summary_en', 'like', $like))
                ->take(20)->get();

            $results['plans'] = PricingPlan::where('is_active', true)
                ->where(fn ($w) => $w->where('name_fa', 'like', $like)->orWhere('name_en', 'like', $like)
                    ->orWhere('description_fa', 'like', $like)->orWhere('description_en', 'like', $like))
                ->take(20)->get();

            $results['portfolio'] = Portfolio::where('is_published', true)
                ->where(fn ($w) => $w->where('title_fa', 'like', $like)->orWhere('title_en', 'like', $like)
                    ->orWhere('challenge_fa', 'like', $like)->orWhere('challenge_en', 'like', $like)
                    ->orWhere('solution_fa', 'like', $like)->orWhere('solution_en', 'like', $like))
                ->take(20)->get();

            $results['articles'] = Article::where('is_published', true)
                ->where(fn ($w) => $w->where('title_fa', 'like', $like)->orWhere('title_en', 'like', $like)
                    ->orWhere('excerpt_fa', 'like', $like)->orWhere('excerpt_en', 'like', $like))
                ->take(20)->get();

            $results['team'] = Resume::published()
                ->where(fn ($w) => $w->where('name_fa', 'like', $like)->orWhere('name_en', 'like', $like)
                    ->orWhere('job_title_fa', 'like', $like)->orWhere('job_title_en', 'like', $like))
                ->take(20)->get();
        }

        $total = collect($results)->sum->count();

        return view('search.index', compact('q', 'results', 'total'));
    }
}
