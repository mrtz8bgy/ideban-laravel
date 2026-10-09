<?php

namespace App\Http\Controllers;

use App\Models\PricingPlan;
use App\Models\ServicePrice;

class PricingController extends Controller
{
    public function index()
    {
        $plans = PricingPlan::where('is_active', true)->with('service')->orderBy('sort_order')->get();
        $prices = ServicePrice::where('is_active', true)->where(function ($query) {
            $query->where('price_type', '!=', 'official')->orWhere('is_verified', true);
        })->where(function ($query) {
            $query->whereNull('valid_from')->orWhereDate('valid_from', '<=', now());
        })->where(function ($query) {
            $query->whereNull('valid_until')->orWhereDate('valid_until', '>=', now());
        })->with('service')->latest()->get();

        return view('pricing.index', compact('plans', 'prices'));
    }
}
