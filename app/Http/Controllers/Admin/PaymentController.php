<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query = Payment::with(['invoice.user'])->latest();
        if (in_array($status, ['pending', 'paid', 'failed'], true)) {
            $query->where('status', $status);
        }

        return view('admin.payments.index', ['payments' => $query->paginate(20), 'status' => $status]);
    }

    /** Confirms a bank transfer after a staff member checked the receipt. */
    public function approve(Payment $payment)
    {
        abort_unless($payment->method === 'bank_transfer' && $payment->status === 'pending', 422);
        Billing::confirmPayment($payment, 'BANK-'.$payment->id);

        return back()->with('success', tr('پرداخت تأیید شد.', 'Payment approved.'));
    }

    public function reject(Payment $payment)
    {
        abort_unless($payment->method === 'bank_transfer' && $payment->status === 'pending', 422);
        $payment->update(['status' => 'failed']);

        return back()->with('success', tr('رسید رد شد.', 'Receipt rejected.'));
    }

    public function receipt(Payment $payment)
    {
        abort_unless($payment->receipt_path && Storage::exists($payment->receipt_path), 404);

        return Storage::download($payment->receipt_path);
    }
}
