<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Slide;
use App\Models\Video;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'slides' => Slide::active()->take(6)->get(),
            'categories' => ServiceCategory::where('is_active', true)->orderBy('sort_order')->get(),
            'services' => Service::where('is_active', true)->where('is_featured', true)->latest()->take(3)->get(),
            'plans' => PricingPlan::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->take(3)->get(),
            'portfolios' => Portfolio::where('is_published', true)->latest('completed_at')->take(3)->get(),
            'videos' => Video::public()->latest('published_at')->take(3)->get(),
            'articles' => Article::public()->latest('published_at')->take(3)->get(),
        ]);
    }
}
