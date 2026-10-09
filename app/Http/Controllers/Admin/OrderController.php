<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\ServiceAddon;
use App\Services\Billing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'service'])->latest();
        if ($request->filled('status') && in_array($request->query('status'), Order::STATUSES, true)) {
            $query->where('status', $request->query('status'));
        }

        return view('admin.orders.index', ['orders' => $query->paginate(20), 'statuses' => Order::STATUSES]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load(['user', 'service', 'plan', 'lead', 'invoices.payments']),
            'addons' => ServiceAddon::whereIn('id', $order->addon_ids ?? [])->get(),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'staff_note' => ['nullable', 'string', 'max:3000'],
        ]);
        $order->update($data);

        return back()->with('success', tr('سفارش به‌روزرسانی شد.', 'Order updated.'));
    }

    /** Builds an invoice from manually entered rows (toman). Staff sets the amounts; nothing is taken from the public catalog here. */
    public function invoice(Request $request, Order $order)
    {
        // Blank rows from the form are ignored; partially filled rows still fail validation.
        $rows = array_filter((array) $request->input('items', []), fn ($row) => trim((string) ($row['title'] ?? '')) !== '' || trim((string) ($row['amount'] ?? '')) !== '');
        $request->merge(['items' => array_values($rows)]);

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:20'],
            'items.*.title' => ['required', 'string', 'max:190'],
            'items.*.amount' => ['required', 'integer', 'min:0', 'max:100000000000'],
            'extra_costs' => ['nullable', 'integer', 'min:0'],
            'days' => ['nullable', 'integer', 'min:1', 'max:90'],
        ]);

        $items = collect($data['items'])->map(fn ($i) => ['title' => $i['title'], 'amount' => (int) $i['amount']])->values()->all();
        $invoice = Billing::invoiceForOrder($order, $items, (int) ($data['extra_costs'] ?? 0), (int) ($data['days'] ?? 14));

        if ($order->status === 'requested') {
            $order->update(['status' => 'quoted']);
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('success', tr('فاکتور صادر شد.', 'Invoice issued.'));
    }
}
