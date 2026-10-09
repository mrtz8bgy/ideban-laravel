<?php

namespace App\Services;

use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceAddon;
use Illuminate\Support\Collection;

/**
 * Builds a server-side estimate. Client input only supplies ids; every amount comes from the database.
 * Anything without a public amount becomes a quote line.
 */
class Estimator
{
    public static function calculate(?PricingPlan $plan, Collection $addons): array
    {
        $lines = [];
        $setup = 0;
        $recurring = 0;
        $needsQuote = false;

        if ($plan) {
            $publicPlan = in_array($plan->price_type, ['company', 'negotiated'], true);
            $hasSetup = $publicPlan && $plan->setup_fee !== null;
            $hasRecurring = $publicPlan && $plan->recurring_fee !== null;

            if ($hasSetup) {
                $setup += (int) $plan->setup_fee;
            }
            if ($hasRecurring) {
                $recurring += (int) $plan->recurring_fee;
            }
            if (!$hasSetup && !$hasRecurring) {
                $needsQuote = true;
            }
            $lines[] = [
                'label' => tr($plan->name_fa, $plan->name_en),
                'amount' => $hasSetup ? (int) $plan->setup_fee : null,
                'recurring' => $hasRecurring ? (int) $plan->recurring_fee : null,
                'quote' => !$hasSetup && !$hasRecurring,
            ];
        }

        foreach ($addons as $addon) {
            if ($addon->hasPublicAmount()) {
                $setup += (int) $addon->amount;
                $lines[] = ['label' => tr($addon->name_fa, $addon->name_en), 'amount' => (int) $addon->amount, 'recurring' => null, 'quote' => false];
            } else {
                $needsQuote = true;
                $lines[] = ['label' => tr($addon->name_fa, $addon->name_en), 'amount' => null, 'recurring' => null, 'quote' => true];
            }
        }

        return [
            'lines' => $lines,
            'setup' => $setup,
            'recurring' => $recurring,
            'needs_quote' => $needsQuote,
        ];
    }

    /** Addons that belong to a service and are active, in display order. */
    public static function addonsFor(?Service $service, array $ids): Collection
    {
        if (!$service) {
            return collect();
        }

        return ServiceAddon::where('service_id', $service->id)
            ->where('is_active', true)
            ->whereIn('id', array_map('intval', $ids))
            ->orderBy('sort_order')
            ->get();
    }
}
