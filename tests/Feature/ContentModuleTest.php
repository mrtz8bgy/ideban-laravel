<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Video;
use App\Support\PublicMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ContentModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublished_and_future_articles_are_hidden_publicly()
    {
        Article::create(['slug' => 'draft-post', 'title_fa' => 'پیش‌نویس', 'title_en' => 'Draft post', 'is_published' => false]);
        Article::create(['slug' => 'future-post', 'title_fa' => 'آینده', 'title_en' => 'Future post', 'is_published' => true, 'published_at' => now()->addDay()]);
        Article::create(['slug' => 'live-post', 'title_fa' => 'منتشرشده', 'title_en' => 'Live post', 'is_published' => true, 'published_at' => now()->subDay()]);

        $this->withSession(['locale' => 'en'])->get('/blog')->assertOk()->assertSee('Live post')->assertDontSee('Draft post')->assertDontSee('Future post');
        $this->get('/blog/draft-post')->assertNotFound();
    }

    public function test_article_body_escapes_raw_html_and_builds_toc()
    {
        $article = Article::create([
            'slug' => 'safe-post', 'title_fa' => 'امن', 'title_en' => 'Safe', 'is_published' => true,
            'body_en' => "## First section\n\nHello <script>alert(1)</script>\n\n## Second section\n\nBody",
        ]);

        $rendered = $article->renderedBody('en');

        $this->assertStringNotContainsString('<script>', $rendered['html']);
        $this->assertCount(2, $rendered['toc']);
        $this->assertSame('sec-1', $rendered['toc'][0]['id']);
    }

    public function test_video_embed_accepts_only_whitelisted_https_hosts()
    {
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', Video::embedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', Video::embedUrl('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertSame('https://player.vimeo.com/video/123456789', Video::embedUrl('https://vimeo.com/123456789'));
        $this->assertNull(Video::embedUrl('http://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertNull(Video::embedUrl('https://evil.example.com/watch?v=dQw4w9WgXcQ'));
        $this->assertNull(Video::embedUrl('javascript:alert(1)'));
    }

    public function test_public_media_url_uses_current_application_path()
    {
        URL::forceRootUrl('https://site.example/renamed-project/public');
        URL::forceScheme('https');

        try {
            $this->assertSame(
                'https://site.example/renamed-project/public/storage/media/sample.png',
                PublicMedia::url('media/sample.png')
            );
        } finally {
            URL::forceRootUrl(null);
            URL::forceScheme(null);
        }
    }

    public function test_content_manager_can_publish_a_video_and_it_appears_on_the_public_site()
    {
        $editor = $this->staff('content');

        $this->actingAs($editor)->post(route('admin.content.store', 'videos'), [
            'slug' => 'intro-video',
            'title_fa' => 'معرفی خدمات',
            'title_en' => 'Service intro',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => '1',
        ])->assertRedirect(route('admin.content.index', 'videos'));

        $this->withSession(['locale' => 'en'])->get('/videos')->assertOk()->assertSee('Service intro');
        $this->withSession(['locale' => 'en'])->get('/videos/intro-video')->assertOk()->assertSee('youtube-nocookie.com/embed/dQw4w9WgXcQ', false);
    }

    public function test_video_with_unsupported_host_is_rejected()
    {
        $editor = $this->staff('content');

        $this->actingAs($editor)->post(route('admin.content.store', 'videos'), [
            'slug' => 'bad-video',
            'title_fa' => 'بد',
            'title_en' => 'Bad',
            'video_url' => 'https://example.com/clip.mp4',
        ])->assertSessionHasErrors('video_url');

        $this->assertDatabaseMissing('videos', ['slug' => 'bad-video']);
    }

    public function test_uploaded_public_video_is_saved_and_rendered_with_a_native_player()
    {
        Storage::fake('public');
        $editor = $this->staff('content');

        $this->actingAs($editor)->post(route('admin.content.store', 'videos'), [
            'slug' => 'uploaded-video',
            'title_fa' => 'ویدیوی آپلودی',
            'title_en' => 'Uploaded video',
            'video_file' => UploadedFile::fake()->create('video.mp4', 100, 'video/mp4'),
            'media_file' => UploadedFile::fake()->createWithContent(
                'thumbnail.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/gYkAAAAASUVORK5CYII=')
            ),
            'is_published' => '1',
        ])->assertRedirect(route('admin.content.index', 'videos'));

        $video = Video::where('slug', 'uploaded-video')->firstOrFail();
        Storage::disk('public')->assertExists($video->video_path);
        Storage::disk('public')->assertExists($video->thumbnail_path);
        $this->withSession(['locale' => 'en'])->get('/videos/uploaded-video')
            ->assertOk()
            ->assertSee('<video', false)
            ->assertSee(Storage::disk('public')->url($video->video_path), false)
            ->assertSee(Storage::disk('public')->url($video->thumbnail_path), false);
    }

    public function test_sales_role_cannot_manage_content()
    {
        $sales = $this->staff('sales');

        $this->actingAs($sales)->get(route('admin.content.index', 'articles'))->assertForbidden();
    }

    public function test_content_upload_forms_render_multipart_media_fields()
    {
        $editor = $this->staff('content');

        $this->actingAs($editor)->get(route('admin.catalog.create', 'services'))
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="media_file"', false);
        $this->actingAs($editor)->get(route('admin.content.create', 'articles'))
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="media_file"', false);
        $this->actingAs($editor)->get(route('admin.content.create', 'videos'))
            ->assertOk()
            ->assertSee('name="video_file"', false);
    }

    public function test_robots_txt_is_served_dynamically_with_sitemap()
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('sitemap.xml', false);
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->role = $role;
        $user->save();

        return $user;
    }
}
