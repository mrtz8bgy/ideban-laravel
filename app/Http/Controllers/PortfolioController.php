<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::where('is_published', true)->latest('completed_at')->paginate(9);

        return view('portfolio.index', compact('portfolios'));
    }

    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('portfolio.show', compact('portfolio'));
    }
}
