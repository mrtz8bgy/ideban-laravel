<?php

namespace Tests\Feature;

use App\Http\Kernel;
use App\Http\Middleware\TrustProxies;
use App\Models\MenuItem;
use App\Models\Slide;
use Database\Seeders\ScrollSlideSeeder;
use Database\Seeders\SlideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Links, menus and media must keep working when the site moves to a new domain or folder.
 */
class DomainChangeTest extends TestCase
{
    use RefreshDatabase;

    private const OLD_HOST = 'old-site.test';
    private const NEW_HOST = 'new-domain.ir';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SlideSeeder::class);
        $this->seed(ScrollSlideSeeder::class);
    }

    public function test_trust_proxies_middleware_is_registered_globally(): void
    {
        $kernel = $this->app->make(Kernel::class);
        $middleware = (new \ReflectionProperty($kernel, 'middleware'))->getValue($kernel);

        $this->assertContains(TrustProxies::class, $middleware);
    }

    public function test_stored_links_are_root_relative_and_never_contain_a_host(): void
    {
        foreach (MenuItem::query()->pluck('url') as $url) {
            $this->assertFalse(preg_match('#^(https?:)?//#i', (string) $url) === 1, "Absolute URL stored in menu: {$url}");
        }
        foreach (Slide::query()->pluck('button_url') as $url) {
            $this->assertFalse(preg_match('#^(https?:)?//#i', (string) $url) === 1, "Absolute URL stored in slide: {$url}");
        }
    }

    public function test_rendered_pages_do_not_contain_the_old_host_after_domain_change(): void
    {
        config(['app.url' => 'https://'.self::NEW_HOST]);

        foreach (['/', '/services', '/pricing', '/team', '/search?q=Laravel'] as $path) {
            $html = $this->get('https://'.self::NEW_HOST.$path)->assertStatus(200)->getContent();

            $this->assertStringNotContainsString(self::OLD_HOST, $html, "Old host found on {$path}");
            $this->assertStringContainsString('https://'.self::NEW_HOST, $html, "New host missing on {$path}");
        }
    }

    public function test_rendered_pages_do_not_contain_the_old_host_when_served_from_another_host(): void
    {
        $html = $this->get('https://'.self::NEW_HOST.'/')->getContent();

        $this->assertStringNotContainsString(self::OLD_HOST, $html);
        $this->assertStringNotContainsString('localhost', $html);
    }
}
