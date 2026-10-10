<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanStoreTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, string $username): User
    {
        $user = new User(['name' => ucfirst($username), 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000011']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    private function plan(array $attrs = []): PricingPlan
    {
        $service = Service::create([
            'slug' => 'store-service', 'title_fa' => 'خدمت', 'title_en' => 'Store service', 'summary_fa' => 'خلاصه', 'summary_en' => 'Summary',
            'is_active' => true, 'price_type' => 'quote',
        ]);

        return PricingPlan::create($attrs + [
            'service_id' => $service->id, 'slug' => 'plan-'.uniqid(), 'name_fa' => 'بسته', 'name_en' => 'Plan',
            'price_type' => 'company', 'setup_fee' => 12000000, 'recurring_fee' => 3000000,
            'recurrence_fa' => 'ماهانه', 'recurrence_en' => 'monthly', 'is_active' => true, 'sort_order' => 1,
        ]);
    }

    public function test_checkout_shows_price_in_toman_for_customers_only()
    {
        $plan = $this->plan();
        $this->get(route('account.plans.checkout', $plan))->assertRedirect(route('login'));

        $this->actingAs($this->user('customer', 'shopper'))
            ->get(route('account.plans.checkout', $plan))
            ->assertOk()
            ->assertSee('12,000,000')
            ->assertSee('15,000,000');

        $this->actingAs($this->user('admin', 'boss2'))
            ->get(route('account.plans.checkout', $plan))
            ->assertForbidden();
    }

    public function test_fixed_company_price_creates_order_snapshot_and_invoice()
    {
        $plan = $this->plan();
        $customer = $this->user('customer', 'buyer2');

        $this->actingAs($customer)->post(route('account.plans.order', $plan), ['accept_terms' => '1'])->assertRedirect();

        $order = Order::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame('quoted', $order->status);
        $this->assertSame('company', $order->plan_price_type);
        $this->assertSame(12000000, (int) $order->plan_setup_fee);
        $this->assertSame(3000000, (int) $order->plan_recurring_fee);

        $invoice = Invoice::where('order_id', $order->id)->firstOrFail();
        $this->assertSame(15000000, (int) $invoice->total);
        $this->assertSame('issued', $invoice->status);
    }

    public function test_price_change_does_not_affect_existing_order()
    {
        $plan = $this->plan();
        $customer = $this->user('customer', 'buyer3');
        $this->actingAs($customer)->post(route('account.plans.order', $plan), ['accept_terms' => '1']);

        $plan->update(['setup_fee' => 99000000]);

        $order = Order::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame(12000000, (int) $order->plan_setup_fee);
    }

    public function test_negotiated_plan_is_a_request_without_invoice()
    {
        $plan = $this->plan(['price_type' => 'negotiated', 'setup_fee' => 5000000]);
        $customer = $this->user('customer', 'buyer4');

        $this->actingAs($customer)->post(route('account.plans.order', $plan), ['accept_terms' => '1']);

        $order = Order::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame('requested', $order->status);
        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_quote_only_plan_stores_no_amount()
    {
        $plan = $this->plan(['price_type' => 'quote', 'setup_fee' => null, 'recurring_fee' => null]);
        $customer = $this->user('customer', 'buyer5');

        $this->actingAs($customer)->post(route('account.plans.order', $plan), ['accept_terms' => '1']);

        $order = Order::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame('requested', $order->status);
        $this->assertNull($order->plan_setup_fee);
        $this->assertNull($order->plan_recurring_fee);
    }

    public function test_order_requires_accepting_terms()
    {
        $plan = $this->plan();
        $customer = $this->user('customer', 'buyer6');

        $this->actingAs($customer)->post(route('account.plans.order', $plan))->assertSessionHasErrors('accept_terms');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_admin_plan_requires_a_setup_fee_unless_quote_only()
    {
        $admin = $this->user('admin', 'manager');
        $service = Service::create([
            'slug' => 'admin-service', 'title_fa' => 'خدمت', 'title_en' => 'Service', 'summary_fa' => 'خ', 'summary_en' => 'S', 'is_active' => true, 'price_type' => 'quote',
        ]);
        $base = ['service_id' => $service->id, 'name_fa' => 'بسته', 'name_en' => 'Plan', 'is_active' => 1, 'sort_order' => 0];

        $this->actingAs($admin)->post(route('admin.catalog.store', 'plans'), $base + ['price_type' => 'company', 'setup_fee' => ''])
            ->assertSessionHasErrors('setup_fee');

        $this->actingAs($admin)->post(route('admin.catalog.store', 'plans'), $base + ['price_type' => 'quote', 'setup_fee' => ''])
            ->assertSessionDoesntHaveErrors('setup_fee');
    }
}
