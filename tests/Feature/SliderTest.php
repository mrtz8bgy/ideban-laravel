<?php

namespace Tests\Feature;

use App\Models\Slide;
use App\Models\User;
use Database\Seeders\SlideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SliderTest extends TestCase
{
    use RefreshDatabase;

    private function staff(string $role, string $username): User
    {
        $user = new User(['name' => ucfirst($username), 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000005']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    public function test_home_shows_only_active_slides_in_order()
    {
        $this->get('/lang/en');
        Slide::create(['title_fa' => 'دوم', 'title_en' => 'Second slide', 'image_path' => 'images/portfolio-2.jpg', 'sort_order' => 2, 'is_active' => true]);
        Slide::create(['title_fa' => 'اول', 'title_en' => 'First slide', 'image_path' => 'images/hero-gold.jpg', 'sort_order' => 1, 'is_active' => true]);
        Slide::create(['title_fa' => 'پنهان', 'title_en' => 'Hidden slide', 'image_path' => 'images/portfolio-3.jpg', 'sort_order' => 0, 'is_active' => false]);

        $this->get('/')->assertOk()
            ->assertSee('First slide')
            ->assertSee('Second slide')
            ->assertDontSee('Hidden slide');

        $html = $this->get('/')->getContent();
        $this->assertLessThan(strpos($html, 'Second slide'), strpos($html, 'First slide'));
    }

    public function test_sample_slides_are_labelled()
    {
        $this->seed(SlideSeeder::class);
        $this->get('/lang/en');

        $this->get('/')->assertOk()->assertSee('Sample');
        $this->assertSame(3, Slide::where('is_sample', true)->count());
    }

    public function test_admin_can_create_edit_and_deactivate_slides()
    {
        $admin = $this->staff('admin', 'boss');

        $this->actingAs($admin)->post(route('admin.slides.store'), [
            'title_fa' => 'تست', 'title_en' => 'Test slide', 'button_url' => '/contact',
            'button_text_fa' => 'تماس', 'button_text_en' => 'Contact',
            'image' => null,
        ])->assertSessionHasErrors('image');

        $slide = Slide::create(['title_fa' => 'تست', 'title_en' => 'Test slide', 'image_path' => 'images/hero-gold.jpg', 'is_active' => true]);

        $this->actingAs($admin)->put(route('admin.slides.update', $slide), [
            'title_fa' => 'تست ویرایش', 'title_en' => 'Edited slide', 'sort_order' => 5,
        ])->assertRedirect(route('admin.slides.index'));

        $slide->refresh();
        $this->assertSame('Edited slide', $slide->title_en);
        $this->assertFalse($slide->is_active, 'Unchecked checkbox must deactivate the slide.');
        $this->assertSame(5, $slide->sort_order);
    }

    public function test_unsafe_button_link_is_rejected()
    {
        $admin = $this->staff('admin', 'boss');
        $slide = Slide::create(['title_fa' => 'x', 'title_en' => 'x', 'image_path' => 'images/hero-gold.jpg', 'is_active' => true]);

        $this->actingAs($admin)->put(route('admin.slides.update', $slide), [
            'title_fa' => 'x', 'title_en' => 'x', 'button_url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('button_url');

        $this->assertNull($slide->fresh()->button_url);
    }

    public function test_sales_staff_cannot_manage_slides()
    {
        $sales = $this->staff('sales', 'seller');

        $this->actingAs($sales)->get(route('admin.slides.index'))->assertForbidden();
    }
}
