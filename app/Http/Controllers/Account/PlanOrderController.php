<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PricingPlan;
use Illuminate\Http\Request;

class PlanOrderController extends Controller
{
    public function store(Request $request, PricingPlan $plan)
    {
        abort_unless($plan->is_active, 404);
        $user = $request->user();
        // Staff accounts pass the account middleware, but only customers place plan orders.
        abort_unless($user->isCustomer(), 403);

        $existing = Order::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'requested')
            ->first();

        if ($existing) {
            return redirect()->route('account.orders.show', $existing)
                ->with('success', tr('شما قبلاً برای این بسته سفارش در انتظار بررسی دارید.', 'You already have a pending order for this plan.'));
        }

        $data = $request->validate(['customer_note' => ['nullable', 'string', 'max:1000']]);

        $order = Order::create([
            'reference' => Order::newReference(),
            'user_id' => $user->id,
            'service_id' => $plan->service_id,
            'plan_id' => $plan->id,
            'status' => 'requested',
            'customer_note' => $data['customer_note'] ?? null,
        ]);

        return redirect()->route('account.orders.show', $order)
            ->with('success', tr('سفارش شما ثبت شد. کارشناسان ایده‌بان الماس با شما تماس می‌گیرند.', 'Your order has been placed. Our team will contact you.'));
    }
}
