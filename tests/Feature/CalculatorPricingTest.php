<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\PricingPlan;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceAddon;
use Database\Seeders\CommerceSeeder;
use Database\Seeders\IdebanCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorPricingTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(IdebanCatalogSeeder::class);
        $this->seed(CommerceSeeder::class);
        $this->service = Service::where('slug', 'business-website')->firstOrFail();
    }

    public function test_estimate_prices_come_from_the_database_not_the_request()
    {
        $plan = PricingPlan::where('is_active', true)->orderBy('sort_order')->firstOrFail();
        $plan->update(['price_type' => 'company', 'setup_fee' => 5000000, 'recurring_fee' => 300000]);
        $addon = ServiceAddon::where('service_id', $this->service->id)->firstOrFail();

        $response = $this->post(route('calculator.estimate'), [
            'service_id' => $this->service->id,
            'plan_id' => $plan->id,
            'addon_ids' => [$addon->id],
            // Client-supplied amounts must be ignored.
            'setup' => 1,
            'price' => 1,
        ]);

        $response->assertOk();
        $response->assertViewHas('result', function ($result) {
            return $result['setup'] === 5000000
                && $result['recurring'] === 300000
                && $result['needs_quote'] === true; // the add-on has no public amount
        });
    }

    public function test_quote_only_addons_are_listed_as_price_inquiries()
    {
        $addon = ServiceAddon::where('service_id', $this->service->id)->firstOrFail();

        $this->post(route('calculator.estimate'), ['service_id' => $this->service->id, 'addon_ids' => [$addon->id]])
            ->assertViewHas('result', fn ($r) => $r['lines'][0]['quote'] === true && $r['lines'][0]['amount'] === null);
    }

    public function test_plan_from_another_service_is_ignored()
    {
        // A plan tied to another service must not be priced for this service.
        $otherService = Service::where('slug', 'network-security')->firstOrFail();
        $otherPlan = PricingPlan::where('is_active', true)->firstOrFail();
        $otherPlan->update(['service_id' => $otherService->id, 'price_type' => 'company', 'setup_fee' => 9000000]);

        $this->post(route('calculator.estimate'), ['service_id' => $this->service->id, 'plan_id' => $otherPlan->id])
            ->assertViewHas('result', fn ($r) => $r['setup'] === 0 && count($r['lines']) === 0);
    }

    public function test_guest_quote_request_creates_a_lead_without_an_order()
    {
        $this->post(route('calculator.quote'), [
            'service_id' => $this->service->id,
            'name' => 'Test Client',
            'phone' => '09120000000',
            'message' => 'Need a site',
        ])->assertRedirect(route('calculator.index'));

        $this->assertDatabaseHas('leads', ['source' => 'calculator', 'name' => 'Test Client']);
        $this->assertSame(0, Order::count());
        $this->assertSame(1, Lead::count());
    }

    public function test_honeypot_filled_quote_request_is_rejected()
    {
        $this->post(route('calculator.quote'), [
            'service_id' => $this->service->id,
            'name' => 'Bot',
            'phone' => '09120000000',
            'website' => 'spam',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, Lead::count());
    }
}
