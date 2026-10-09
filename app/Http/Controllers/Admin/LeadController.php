<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with(['service', 'plan', 'assignee'])->latest()->paginate(20);
        $stages = ['new', 'contacted', 'discovery', 'proposal', 'negotiation', 'won', 'lost'];

        return view('admin.leads.index', compact('leads', 'stages'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'stage' => ['required', 'in:new,contacted,discovery,proposal,negotiation,won,lost'],
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->whereIn('role', ['admin', 'content', 'sales']),
            ],
            'follow_up_at' => ['nullable', 'date'],
            'expected_value' => ['nullable', 'integer', 'min:0'],
            'sales_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $lead->update($data);

        return back()->with('success', __('site.saved'));
    }
}
