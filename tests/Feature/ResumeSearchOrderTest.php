<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\Resume;
use App\Models\ResumeItem;
use App\Models\User;
use Database\Seeders\IdebanCatalogSeeder;
use Database\Seeders\ResumeSampleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeSearchOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(IdebanCatalogSeeder::class);
        $this->seed(ResumeSampleSeeder::class);
    }

    private function english(): void
    {
        $this->get('/lang/en');
    }

    private function user(string $role, string $username): User
    {
        $user = new User(['name' => ucfirst($username), 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000009']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    public function test_search_requires_two_characters_and_finds_services_and_team(): void
    {
        $this->get('/search?q=a')->assertOk()->assertSee('حداقل ۲ حرف');
        $this->english();
        $this->get('/search?q=Laravel')->assertOk()->assertSee('Custom Laravel development');
        $this->get('/search?q=Niloufar')->assertOk()->assertSee('Sample: Niloufar Kaviani');
        $this->get('/search?q=%25%25')->assertOk();
    }

    public function test_guest_sees_login_prompt_and_cannot_order_a_plan(): void
    {
        $plan = PricingPlan::where('is_active', true)->firstOrFail();
        $this->get('/pricing')->assertOk()->assertSee('ورود برای ثبت سفارش');
        $this->post(route('account.plans.order', $plan))->assertRedirect(route('login'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_can_order_a_plan_once(): void
    {
        $plan = PricingPlan::where('is_active', true)->firstOrFail();
        $customer = $this->user('customer', 'buyer');

        $this->actingAs($customer)->post(route('account.plans.order', $plan), ['customer_note' => 'Please call me'])
            ->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'plan_id' => $plan->id, 'status' => 'requested', 'customer_note' => 'Please call me']);

        $this->actingAs($customer)->post(route('account.plans.order', $plan))->assertRedirect();
        $this->assertSame(1, Order::where('user_id', $customer->id)->count());
    }

    public function test_staff_cannot_use_the_customer_order_route(): void
    {
        $plan = PricingPlan::where('is_active', true)->firstOrFail();
        $response = $this->actingAs($this->user('admin', 'boss'))->post(route('account.plans.order', $plan));
        $response->assertForbidden();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_team_pages_show_only_published_resumes(): void
    {
        $this->english();
        $this->get('/team')->assertOk()->assertSee('Sample: Arash Mousavi');
        $resume = Resume::where('slug', 'sample-laravel-developer')->firstOrFail();
        $resume->update(['is_published' => false]);
        $this->get('/team/sample-laravel-developer')->assertNotFound();
        $this->get('/team')->assertDontSee('Sample: Arash Mousavi');
    }

    public function test_resume_page_shows_sections_and_sample_notice(): void
    {
        $this->english();
        $this->get('/team/sample-network-engineer')->assertOk()
            ->assertSee('Firewall configuration')->assertSee('Sample networking certificate')
            ->assertSee('does not belong to a real person');
    }

    public function test_admin_can_create_resume_and_manage_items(): void
    {
        $admin = $this->user('admin', 'resume-admin');

        $this->actingAs($admin)->post(route('admin.resumes.store'), [
            'name_fa' => 'علی تست', 'name_en' => 'Ali Test', 'job_title_en' => 'Engineer',
            'slug' => '', 'is_published' => '1', 'sort_order' => 5,
        ])->assertRedirect();

        $resume = Resume::where('name_en', 'Ali Test')->firstOrFail();
        $this->assertSame('ali-test', $resume->slug);
        $this->assertTrue($resume->is_published);

        $this->actingAs($admin)->post(route('admin.resumes.items.store', $resume), [
            'type' => 'skill', 'title_fa' => 'PHP', 'title_en' => 'PHP', 'level' => 80,
        ])->assertRedirect();
        $item = $resume->items()->firstOrFail();
        $this->assertSame(80, $item->level);

        $this->actingAs($admin)->delete(route('admin.resumes.items.destroy', [$resume, $item]))->assertRedirect();
        $this->assertSame(0, ResumeItem::where('resume_id', $resume->id)->count());
    }

    public function test_resume_item_rejects_unknown_type_and_unsafe_link(): void
    {
        $admin = $this->user('admin', 'resume-admin2');
        $resume = Resume::first();

        $this->actingAs($admin)->post(route('admin.resumes.items.store', $resume), [
            'type' => 'hacking', 'title_fa' => 'x', 'title_en' => 'x',
        ])->assertSessionHasErrors('type');

        $this->actingAs($admin)->post(route('admin.resumes.items.store', $resume), [
            'type' => 'certificate', 'title_fa' => 'x', 'title_en' => 'x', 'url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('url');
    }

    public function test_customer_cannot_access_admin_resumes(): void
    {
        $this->actingAs($this->user('customer', 'nosy'))->get('/admin/resumes')->assertForbidden();
    }
}
