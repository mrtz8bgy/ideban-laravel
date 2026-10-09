<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\CommerceSeeder;
use Database\Seeders\IdebanCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, string $username): User
    {
        $user = new User(['name' => ucfirst($username), 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000004', 'company' => 'Test Co']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    public function test_quote_to_paid_order_flow()
    {
        Storage::fake('local');
        $this->seed(IdebanCatalogSeeder::class);
        $this->seed(CommerceSeeder::class);

        $customer = $this->user('customer', 'buyer');
        $admin = $this->user('admin', 'boss');
        $service = Service::where('slug', 'business-website')->firstOrFail();
        $plan = PricingPlan::where('is_active', true)->firstOrFail();

        // 1. Logged-in customer requests a quote; an order is created for the account.
        $this->actingAs($customer)->post(route('calculator.quote'), [
            'service_id' => $service->id, 'plan_id' => $plan->id,
            'name' => 'Buyer', 'phone' => '09120000004',
        ])->assertRedirect();
        $order = Order::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame('requested', $order->status);
        $this->actingAs($customer)->get(route('account.orders.show', $order))->assertOk();

        // 2. Staff issues an invoice; the order moves to quoted.
        $this->actingAs($admin)->get(route('admin.orders.show', $order))->assertOk();
        $this->actingAs($admin)->post(route('admin.orders.invoice', $order), [
            'items' => [
                ['title' => 'Website build', 'amount' => 2000000],
                ['title' => '', 'amount' => ''],
            ],
            'extra_costs' => 0, 'days' => 14,
        ])->assertRedirect();
        $invoice = Invoice::where('order_id', $order->id)->firstOrFail();
        $this->assertSame(2000000, $invoice->total);
        $this->assertSame('quoted', $order->fresh()->status);

        // 3. Online payment is unavailable with the bank gateway; the invoice stays unpaid.
        $this->actingAs($customer)->post(route('account.invoices.pay', $invoice))->assertSessionHasErrors('payment');

        // 4. Bank receipt upload creates a pending payment.
        $this->actingAs($customer)->post(route('account.invoices.receipt', $invoice), [
            'amount' => 2000000,
            'receipt' => UploadedFile::fake()->create('receipt.jpg', 120, 'image/jpeg'),
        ])->assertRedirect();
        $payment = Payment::where('invoice_id', $invoice->id)->firstOrFail();
        $this->assertSame('pending', $payment->status);
        $this->assertSame('unpaid', $invoice->fresh()->isPaid() ? 'paid' : 'unpaid');

        // 5. Staff approves the receipt; the invoice is paid and the order accepted.
        $this->actingAs($admin)->post(route('admin.payments.approve', $payment))->assertRedirect();
        $this->assertSame('paid', $invoice->fresh()->status);
        $this->assertSame('accepted', $order->fresh()->status);
        $this->actingAs($admin)->get(route('admin.payments.receipt', $payment))->assertOk();
    }

    public function test_customer_cannot_see_another_customers_invoice()
    {
        $this->seed(IdebanCatalogSeeder::class);
        $owner = $this->user('customer', 'owner');
        $other = $this->user('customer', 'nosy');
        $invoice = Invoice::create([
            'number' => Invoice::newNumber(), 'user_id' => $owner->id, 'type' => 'invoice', 'status' => 'issued',
            'items' => [['title' => 'x', 'amount' => 1000]], 'subtotal' => 1000, 'discount' => 0, 'extra_costs' => 0, 'total' => 1000,
        ]);

        $this->actingAs($other)->get(route('account.invoices.show', $invoice))->assertForbidden();
    }
}
