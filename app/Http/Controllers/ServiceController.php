<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->with(['services' => function ($query) {
                $query->where('is_active', true)->orderByDesc('is_featured');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('services.index', compact('categories'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)
            ->with(['category', 'plans' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->firstOrFail();

        return view('services.show', compact('service'));
    }
}
