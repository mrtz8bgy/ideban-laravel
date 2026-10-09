<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\ServicePrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdebanPlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_in_persian_by_default()
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('شبکه پردازان ایده‌بان الماس');
    }

    public function test_locale_change_redirect_preserves_subdirectory_referer_without_duplicating_it()
    {
        $this->withHeader('referer', 'http://localhost/renamed-project/public/admin?tab=orders')
            ->get('/lang/en')
            ->assertRedirect('http://localhost/renamed-project/public/admin?tab=orders');

        $this->assertSame('en', session('locale'));
    }

    public function test_consultation_request_is_persisted_as_a_new_lead()
    {
        $this->post('/contact', [
            'name' => 'Test customer',
            'phone' => '09100000000',
            'email' => 'client@example.test',
            'message' => 'Need a company website',
        ])->assertRedirect(route('contact'));

        $this->assertDatabaseHas('leads', [
            'name' => 'Test customer',
            'phone' => '09100000000',
            'source' => 'website',
            'stage' => 'new',
        ]);
    }

    public function test_unverified_official_price_is_never_shown_publicly()
    {
        $service = Service::create([
            'slug' => 'test-service',
            'title_fa' => 'خدمت آزمایشی',
            'title_en' => 'Test service',
            'is_active' => true,
        ]);

        ServicePrice::create([
            'service_id' => $service->id,
            'title_fa' => 'تعرفه آزمایشی تأییدنشده',
            'title_en' => 'Unverified test tariff',
            'price_type' => 'official',
            'amount' => 123000,
            'is_verified' => false,
            'show_amount' => true,
        ]);
        ServicePrice::create([
            'service_id' => $service->id,
            'title_fa' => 'تعرفه تأییدشده آزمایشی',
            'title_en' => 'Verified test tariff',
            'price_type' => 'official',
            'amount' => 456000,
            'is_verified' => true,
            'show_amount' => true,
        ]);

        $this->withSession(['locale' => 'en'])->get('/pricing')
            ->assertOk()
            ->assertSee('Verified test tariff')
            ->assertDontSee('Unverified test tariff');
    }

    public function test_regular_user_cannot_open_admin_panel()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_create_a_bilingual_service()
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $this->actingAs($admin)->post(route('admin.catalog.store', 'services'), [
            'slug' => 'secure-service',
            'title_fa' => 'خدمت امن',
            'title_en' => 'Secure service',
            'is_active' => '1',
            'is_featured' => '1',
        ])->assertRedirect(route('admin.catalog.index', 'services'));

        $this->assertDatabaseHas('services', [
            'slug' => 'secure-service',
            'title_fa' => 'خدمت امن',
            'title_en' => 'Secure service',
            'is_active' => 1,
            'is_featured' => 1,
        ]);
    }

    public function test_service_page_displays_active_addons()
    {
        $service = Service::create([
            'slug' => 'service-with-addon',
            'title_fa' => 'خدمت دارای افزودنی',
            'title_en' => 'Service with add-on',
            'is_active' => true,
        ]);
        ServiceAddon::create([
            'service_id' => $service->id,
            'name_fa' => 'افزودنی نمایشی',
            'name_en' => 'Visible add-on',
            'price_type' => 'quote',
            'is_active' => true,
        ]);

        $this->withSession(['locale' => 'en'])->get('/services/service-with-addon')
            ->assertOk()
            ->assertSee('Visible add-on');
    }
}
