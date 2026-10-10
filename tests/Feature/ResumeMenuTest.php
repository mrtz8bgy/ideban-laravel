<?php

namespace Tests\Feature;

use App\Models\Resume;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeMenuTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::where('username', 'resadmin')->first() ?? $this->admin();
    }

    private function admin(): User
    {
        $user = new User(['name' => 'Admin', 'username' => 'resadmin', 'email' => 'resadmin@example.test', 'phone' => '09120000008']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = 'admin';
        $user->save();

        return $user;
    }

    public function test_menu_lists_published_resume_sections_as_sub_items()
    {
        $resume = Resume::create([
            'slug' => 'test-person', 'name_fa' => 'آزمایشی', 'name_en' => 'Test Person',
            'job_title_en' => 'Engineer', 'is_published' => true, 'is_sample' => true,
        ]);
        $resume->items()->create(['type' => 'education', 'title_fa' => 'کارشناسی', 'title_en' => 'BSc']);
        $resume->items()->create(['type' => 'certificate', 'title_fa' => 'سیسکو', 'title_en' => 'CCNA', 'url' => 'https://example.com/cert']);

        (new MenuSeeder())->run();

        $this->assertDatabaseHas('menu_items', ['label_en' => 'Test Person (Sample)', 'url' => '/team/test-person']);
        $this->assertDatabaseHas('menu_items', ['label_en' => 'Education', 'url' => '/team/test-person#education']);
        $this->assertDatabaseHas('menu_items', ['label_en' => 'Certificates', 'url' => '/team/test-person#certificate']);
        $this->assertDatabaseMissing('menu_items', ['url' => '/team/test-person#skill']);

        $this->get('/lang/en');
        $this->get('/')->assertSee('Test Person (Sample)');
    }

    public function test_admin_adds_certificate_with_verification_link_and_education_entry()
    {
        $this->get('/lang/en');
        $resume = Resume::create(['slug' => 'staff-two', 'name_fa' => 'نفر دوم', 'name_en' => 'Staff Two', 'is_published' => true]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.resumes.items.store', $resume), [
                'type' => 'certificate', 'title_fa' => 'گواهی', 'title_en' => 'Cert', 'organization_en' => 'Vendor',
                'period' => '2024', 'url' => 'https://example.com/verify',
            ])->assertRedirect();

        $this->actingAs($this->adminUser())
            ->post(route('admin.resumes.items.store', $resume), [
                'type' => 'education', 'title_fa' => 'کارشناسی', 'title_en' => 'BSc Computer Engineering',
                'organization_en' => 'Sample University', 'period' => '2017 – 2021',
            ])->assertRedirect();

        $this->assertDatabaseHas('resume_items', ['type' => 'certificate', 'url' => 'https://example.com/verify']);
        $this->assertDatabaseHas('resume_items', ['type' => 'education', 'organization_en' => 'Sample University']);
        $this->get('/team/staff-two')->assertOk()->assertSee('Sample University')->assertSee('id="education"', false);
    }

    public function test_item_form_shows_type_specific_labels()
    {
        $this->get('/lang/en');
        $resume = Resume::create(['slug' => 'form-check', 'name_fa' => 'فرم', 'name_en' => 'Form Check', 'is_published' => true]);

        $this->actingAs($this->adminUser())
            ->get(route('admin.resumes.edit', $resume))
            ->assertOk()
            ->assertSee('Degree')
            ->assertSee('Certificate name')
            ->assertSee('Issuing organisation');
    }
}
