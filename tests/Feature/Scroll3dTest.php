<?php

namespace Tests\Feature;

use App\Models\Slide;
use Database\Seeders\ScrollSlideSeeder;
use Database\Seeders\SlideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Scroll3dTest extends TestCase
{
    use RefreshDatabase;

    public function test_scroll_slide_seeder_adds_three_marked_slides_and_is_idempotent(): void
    {
        $this->seed(SlideSeeder::class);
        $this->seed(ScrollSlideSeeder::class);
        $this->seed(ScrollSlideSeeder::class);

        $this->assertSame(6, Slide::count());
        $this->assertSame(3, Slide::where('image_path', 'like', 'images/samples/scroll-%')->count());
    }

    public function test_homepage_wraps_slider_in_pinned_scroll_container(): void
    {
        $this->seed(SlideSeeder::class);
        $this->seed(ScrollSlideSeeder::class);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('data-scroll3d', false);
        $response->assertSee('--n: 6', false);
        $response->assertSee('scroll3d-sticky', false);
        $response->assertSee('initScroll3d', false);
        $response->assertSee('prefers-reduced-motion', false);
    }

    public function test_homepage_shows_showcase_video_with_poster_fallback(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('showcase-3d', false);
        $response->assertSee('videos/ideban-showcase.mp4', false);
        $response->assertSee('images/samples/showcase-poster.jpg', false);
        $response->assertSee('data-tilt', false);
    }

    public function test_slider_is_not_scroll_pinned_with_a_single_slide(): void
    {
        Slide::query()->delete();
        Slide::create([
            'title_fa' => 'یک', 'title_en' => 'One', 'subtitle_fa' => 'یک', 'subtitle_en' => 'One',
            'button_text_fa' => 'خدمات', 'button_text_en' => 'Services', 'button_url' => '/services',
            'image_path' => 'images/samples/category-cabling-fiber.jpg', 'sort_order' => 1,
            'is_active' => true, 'is_sample' => false,
        ]);

        $this->get('/')->assertDontSee('class="scroll3d"', false);
    }
}
