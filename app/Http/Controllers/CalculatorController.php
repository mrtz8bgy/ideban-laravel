<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Services\Estimator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CalculatorController extends Controller
{
    private function services()
    {
        return Service::where('is_active', true)
            ->with(['addons' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->orderBy('title_fa')
            ->get();
    }

    private function plans()
    {
        return PricingPlan::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function index()
    {
        return view('calculator.index', [
            'services' => $this->services(),
            'plans' => $this->plans(),
            'result' => null,
        ]);
    }

    /** Recomputes everything from the database; the client only sends ids. */
    private function resolve(Request $request): array
    {
        $data = $request->validate([
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'plan_id' => ['nullable', Rule::exists('pricing_plans', 'id')->where('is_active', true)],
            'addon_ids' => ['nullable', 'array', 'max:20'],
            'addon_ids.*' => ['integer'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        // Plans are general packages unless a plan is tied to a specific service.
        $plan = !empty($data['plan_id'])
            ? PricingPlan::where('is_active', true)
                ->where(fn ($q) => $q->whereNull('service_id')->orWhere('service_id', $service->id))
                ->find($data['plan_id'])
            : null;
        $addons = Estimator::addonsFor($service, $data['addon_ids'] ?? []);

        return [$service, $plan, $addons, Estimator::calculate($plan, $addons)];
    }

    public function estimate(Request $request)
    {
        [$service, $plan, $addons, $result] = $this->resolve($request);

        return view('calculator.index', [
            'services' => $this->services(),
            'plans' => $this->plans(),
            'result' => $result,
            'service' => $service,
            'plan' => $plan,
            'addons' => $addons,
        ]);
    }

    public function requestQuote(Request $request)
    {
        $contact = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:3000'],
            'website' => ['nullable', 'max:0'],
        ]);
        [$service, $plan, $addons, $result] = $this->resolve($request);

        $summary = collect($result['lines'])->map(function ($line) {
            $amount = $line['quote'] ? tr('استعلام قیمت', 'Quote') : number_format((int) $line['amount']).' '.tr('تومان', 'Toman');

            return '- '.$line['label'].': '.$amount;
        })->implode("\n");

        $lead = Lead::create([
            'name' => $contact['name'],
            'company' => $contact['company'] ?? null,
            'phone' => $contact['phone'],
            'email' => $contact['email'] ?? null,
            'service_id' => $service->id,
            'plan_id' => $plan?->id,
            'source' => 'calculator',
            'message' => "برآورد ماشین‌حساب:\n".$summary."\n\n".($contact['message'] ?? ''),
            'stage' => 'new',
            'expected_value' => $result['needs_quote'] ? null : $result['setup'],
        ]);

        $order = null;
        if ($request->user()) {
            $order = Order::create([
                'reference' => Order::newReference(),
                'user_id' => $request->user()->id,
                'lead_id' => $lead->id,
                'service_id' => $service->id,
                'plan_id' => $plan?->id,
                'status' => 'requested',
                'customer_note' => $contact['message'] ?? null,
                'addon_ids' => $addons->pluck('id')->all(),
                'estimate_setup' => $result['needs_quote'] ? null : $result['setup'],
                'estimate_recurring' => $result['needs_quote'] ? null : $result['recurring'],
            ]);
        }

        $message = tr('درخواست پیش‌فاکتور ثبت شد.', 'Your quote request has been received.');
        if ($order) {
            return redirect()->route('account.orders.show', $order)->with('success', $message);
        }

        return redirect()->route('calculator.index')->with('success', $message);
    }
}
