<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['user', 'order', 'course'])->latest();
        if ($request->filled('status') && in_array($request->query('status'), ['issued', 'paid', 'cancelled'], true)) {
            $query->where('status', $request->query('status'));
        }

        return view('admin.invoices.index', ['invoices' => $query->paginate(20)]);
    }

    public function show(Invoice $invoice)
    {
        return view('admin.invoices.show', ['invoice' => $invoice->load(['user', 'order', 'course', 'payments'])]);
    }

    /** Cancelling is allowed only for unpaid invoices. Paid invoices are never reverted here. */
    public function update(Request $request, Invoice $invoice)
    {
        abort_if($invoice->isPaid(), 422);
        $data = $request->validate(['status' => ['required', Rule::in(['issued', 'cancelled'])], 'notes' => ['nullable', 'string', 'max:1000']]);
        $invoice->update($data);

        return back()->with('success', tr('فاکتور به‌روزرسانی شد.', 'Invoice updated.'));
    }
}
