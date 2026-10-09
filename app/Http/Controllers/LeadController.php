<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PricingPlan;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('title_fa')->get();
        $selectedPlan = PricingPlan::where('slug', request('plan'))->where('is_active', true)->first();

        return view('contact', compact('services', 'selectedPlan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'service_id' => ['nullable', Rule::exists('services', 'id')->where('is_active', true)],
            'plan_id' => ['nullable', Rule::exists('pricing_plans', 'id')->where('is_active', true)],
            'message' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'max:0'],
        ]);

        unset($data['website']);
        $data['source'] = 'website';
        Lead::create($data);

        return redirect()->route('contact')->with('success', __('site.lead_received'));
    }
}
