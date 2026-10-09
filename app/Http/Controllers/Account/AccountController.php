<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return view('account.dashboard', [
            'orders' => Order::where('user_id', $user->id)->with('service')->latest()->take(5)->get(),
            'invoices' => Invoice::where('user_id', $user->id)->latest()->take(5)->get(),
            'tickets' => Ticket::where('user_id', $user->id)->latest()->take(5)->get(),
            'enrollments' => Enrollment::where('user_id', $user->id)->with('course')->latest('granted_at')->take(5)->get(),
            'openTickets' => Ticket::where('user_id', $user->id)->whereIn('status', ['open', 'answered', 'in_progress'])->count(),
        ]);
    }

    public function profile(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:190'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
        ]);
        $user->update($data);

        return back()->with('success', tr('اطلاعات ذخیره شد.', 'Profile saved.'));
    }

    public function orders(Request $request)
    {
        return view('account.orders', ['orders' => Order::where('user_id', $request->user()->id)->with('service')->latest()->paginate(10)]);
    }

    public function showOrder(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('account.order', [
            'order' => $order->load(['service', 'plan', 'invoices.payments']),
            'addons' => \App\Models\ServiceAddon::whereIn('id', $order->addon_ids ?? [])->get(),
        ]);
    }

    public function invoices(Request $request)
    {
        return view('account.invoices', ['invoices' => Invoice::where('user_id', $request->user()->id)->latest()->paginate(10)]);
    }

    public function showInvoice(Request $request, Invoice $invoice)
    {
        abort_unless($invoice->user_id === $request->user()->id, 403);

        return view('account.invoice', ['invoice' => $invoice->load('payments')]);
    }

    public function courses(Request $request)
    {
        return view('account.courses', [
            'enrollments' => Enrollment::where('user_id', $request->user()->id)->with(['course.lessons', 'lastLesson'])->latest('granted_at')->get(),
        ]);
    }
}
