<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->latest();
        if ($term = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('username', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('company', 'like', "%{$term}%");
            });
        }

        return view('admin.customers.index', ['customers' => $query->paginate(20), 'q' => $term ?? '']);
    }

    public function show(User $customer)
    {
        abort_unless($customer->isCustomer(), 404);

        return view('admin.customers.show', [
            'customer' => $customer,
            'orders' => Order::where('user_id', $customer->id)->with('service')->latest()->get(),
            'invoices' => Invoice::where('user_id', $customer->id)->latest()->get(),
            'tickets' => Ticket::where('user_id', $customer->id)->latest()->get(),
            'enrollments' => Enrollment::where('user_id', $customer->id)->with('course')->get(),
        ]);
    }
}
