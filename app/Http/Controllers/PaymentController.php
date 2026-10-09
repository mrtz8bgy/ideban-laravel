<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Billing;
use App\Services\Payments\GatewayResolver;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** Starts an online payment for the remaining balance. The invoice is never marked paid here. */
    public function online(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);
        abort_unless($invoice->isPayable(), 422);

        $gateway = GatewayResolver::online();
        if (!$gateway) {
            return back()->withErrors(['payment' => tr('پرداخت آنلاین در حال حاضر فعال نیست؛ از کارت به کارت استفاده کنید.', 'Online payment is not available right now. Please use bank transfer.')]);
        }

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => $request->user()->id,
            'amount' => $invoice->remainingAmount(),
            'method' => 'online',
            'gateway' => config('payments.gateway'),
            'status' => 'pending',
        ]);

        $result = $gateway->request($payment, route('payments.callback'), 'Invoice '.$invoice->number);
        if (!$result) {
            $payment->update(['status' => 'failed']);

            return back()->withErrors(['payment' => tr('ایجاد درخواست پرداخت ناموفق بود. دوباره تلاش کنید.', 'Could not start the payment. Please try again.')]);
        }

        $payment->update(['authority' => $result['authority']]);

        return redirect()->away($result['redirect_url']);
    }

    /** Gateway return URL. Verification happens on the server before anything is recorded as paid. */
    public function callback(Request $request)
    {
        $authority = (string) $request->query('Authority', '');
        $payment = $authority !== '' ? Payment::where('authority', $authority)->with('invoice')->first() : null;

        if (!$payment) {
            return redirect()->route('home')->withErrors(['payment' => tr('تراکنش یافت نشد.', 'Transaction not found.')]);
        }

        $gateway = GatewayResolver::online();
        $refId = ($request->query('Status') === 'OK' && $gateway)
            ? $gateway->verify($payment, $authority)
            : null;

        if ($refId) {
            Billing::confirmPayment($payment, $refId);

            return redirect()->route('account.invoices.show', $payment->invoice)->with('success', tr('پرداخت با موفقیت ثبت شد.', 'Payment recorded successfully.'));
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('account.invoices.show', $payment->invoice)->withErrors(['payment' => tr('پرداخت تأیید نشد.', 'The payment was not verified.')]);
    }

    /** Bank transfer: the customer uploads a receipt, staff confirms it. */
    public function receipt(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);
        abort_unless($invoice->isPayable(), 422);

        $data = $request->validate([
            'receipt' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:'.config('payments.receipt_max_kb')],
            'notes' => ['nullable', 'string', 'max:500'],
            'amount' => ['required', 'integer', 'min:1000', 'max:'.$invoice->remainingAmount()],
        ]);

        $path = $request->file('receipt')->store('receipts');

        Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => $request->user()->id,
            'amount' => (int) $data['amount'],
            'method' => 'bank_transfer',
            'status' => 'pending',
            'receipt_path' => $path,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('account.invoices.show', $invoice)->with('success', tr('رسید شما ثبت شد و پس از بررسی تأیید می‌شود.', 'Your receipt was submitted and will be checked shortly.'));
    }

    private function authorizeInvoice(Request $request, Invoice $invoice): void
    {
        abort_unless($invoice->user_id === $request->user()->id, 403);
    }
}
