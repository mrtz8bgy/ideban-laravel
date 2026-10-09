<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Billing
{
    /** Creates an invoice for an order with the given line items (toman). */
    public static function invoiceForOrder(Order $order, array $items, int $extraCosts = 0, int $days = 14): Invoice
    {
        $subtotal = collect($items)->sum('amount');

        return Invoice::create([
            'number' => Invoice::newNumber(),
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'type' => 'invoice',
            'status' => 'issued',
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => 0,
            'extra_costs' => $extraCosts,
            'total' => $subtotal + $extraCosts,
            'valid_until' => now()->addDays($days)->toDateString(),
        ]);
    }

    /** Creates a course invoice for a user, applying a discount code when valid. */
    public static function invoiceForCourse(User $user, \App\Models\Course $course, ?string $code = null): Invoice
    {
        $price = $course->effectivePrice();
        $discount = 0;
        $discountCode = null;

        if ($code) {
            $discountCode = DiscountCode::whereRaw('UPPER(code) = ?', [strtoupper(trim($code))])->first();
            if ($discountCode) {
                $discount = $discountCode->amountFor($course, $price);
            }
        }

        $total = max(0, $price - $discount);

        return DB::transaction(function () use ($user, $course, $price, $discount, $total, $discountCode) {
            if ($discountCode && $discount > 0) {
                $discountCode->increment('used_count');
            }

            return Invoice::create([
                'number' => Invoice::newNumber(),
                'user_id' => $user->id,
                'course_id' => $course->id,
                'type' => 'invoice',
                'status' => 'issued',
                'items' => [['title' => $course->title_fa.' / '.$course->title_en, 'amount' => $price]],
                'subtotal' => $price,
                'discount' => $discount,
                'extra_costs' => 0,
                'total' => $total,
                'valid_until' => now()->addDays(7)->toDateString(),
                'notes' => $discountCode && $discount > 0 ? 'discount:'.$discountCode->code : null,
            ]);
        });
    }

    /**
     * Records a successful payment. Called only after server-side confirmation.
     * Marks the invoice paid when fully covered and grants course access.
     */
    public static function confirmPayment(Payment $payment, ?string $referenceId = null): void
    {
        DB::transaction(function () use ($payment, $referenceId) {
            $payment->refresh();
            if ($payment->status === 'paid') {
                return;
            }

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'reference_id' => $referenceId ?: $payment->reference_id,
            ]);

            $invoice = Invoice::lockForUpdate()->find($payment->invoice_id);
            if (!$invoice || $invoice->isPaid()) {
                return;
            }

            if ($invoice->paidAmount() >= $invoice->total) {
                $invoice->update(['status' => 'paid', 'paid_at' => now()]);

                if ($invoice->course_id && $invoice->user_id) {
                    Enrollment::firstOrCreate(
                        ['user_id' => $invoice->user_id, 'course_id' => $invoice->course_id],
                        ['source' => 'payment', 'payment_id' => $payment->id, 'granted_at' => now()]
                    );
                }

                if ($invoice->order_id) {
                    $invoice->order()->whereIn('status', ['requested', 'quoted'])->update(['status' => 'accepted']);
                }
            }
        });
    }
}
