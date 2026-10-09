<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceCategory;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'counts' => [
                'leads' => Lead::count(),
                'new_leads' => Lead::where('stage', 'new')->count(),
                'services' => Service::count(),
                'categories' => ServiceCategory::count(),
                'plans' => PricingPlan::count(),
                'portfolio' => Portfolio::count(),
            ],
            'leads' => Lead::with(['service', 'plan'])->latest()->take(8)->get(),
        ]);
    }
}
