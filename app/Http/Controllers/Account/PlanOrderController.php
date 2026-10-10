<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Services\Billing;
use Illuminate\Http\Request;

class PlanOrderController extends Controller
{
    /** Checkout summary: plan, price breakdown and the confirmation step. */
    public function checkout(Request $request, PricingPlan $plan)
    {
        abort_unless($plan->is_active, 404);
        abort_unless($request->user()->isCustomer(), 403);

        return view('account.plan-checkout', ['plan' => $plan->load('service')]);
    }

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

        $data = $request->validate([
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'accept_terms' => ['accepted'],
        ], [
            'accept_terms.accepted' => tr('برای ثبت سفارش، شرایط را بپذیرید.', 'Please accept the terms to place the order.'),
        ]);

        // Snapshot of the price at order time (toman). Quote-only plans keep null amounts.
        $quoteOnly = $plan->isQuoteOnly();
        $order = Order::create([
            'reference' => Order::newReference(),
            'user_id' => $user->id,
            'service_id' => $plan->service_id,
            'plan_id' => $plan->id,
            'status' => 'requested',
            'customer_note' => $data['customer_note'] ?? null,
            'plan_price_type' => $plan->price_type,
            'plan_setup_fee' => $quoteOnly ? null : $plan->setup_fee,
            'plan_recurring_fee' => $quoteOnly ? null : $plan->recurring_fee,
        ]);

        // Fixed company prices: issue the first invoice straight away so the customer can pay.
        // Negotiated and quote-only plans stay as requests that staff answer with an invoice.
        $message = tr('سفارش شما ثبت شد. کارشناسان ایده‌بان الماس با شما تماس می‌گیرند.', 'Your order has been placed. Our team will contact you.');
        if ($plan->price_type === 'company' && !$quoteOnly) {
            $items = [['title' => $plan->name_fa.' — '.tr('راه‌اندازی', 'Setup'), 'amount' => (int) $plan->setup_fee]];
            if ((int) $plan->recurring_fee > 0) {
                $items[] = ['title' => $plan->name_fa.' — '.tr('اولین دوره', 'First period'), 'amount' => (int) $plan->recurring_fee];
            }
            Billing::invoiceForOrder($order, $items);
            $order->update(['status' => 'quoted']);
            $message = tr('سفارش ثبت شد و فاکتور آن صادر شد. از بخش فاکتورها پرداخت کنید.', 'Your order is placed and the invoice is ready. Pay it from the invoices section.');
        }

        return redirect()->route('account.orders.show', $order)->with('success', $message);
    }
}
