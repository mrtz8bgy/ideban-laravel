<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\IdebanCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCourseTest extends TestCase
{
    use RefreshDatabase;

    private function staff(string $role, string $username): User
    {
        $user = new User(['name' => ucfirst($username), 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000003']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    private function course(): Course
    {
        return Course::create([
            'slug' => 'sample-course-test', 'title_fa' => 'نمونه', 'title_en' => 'Sample', 'level' => 'beginner',
            'price' => 0, 'is_free' => true, 'is_published' => true,
        ]);
    }

    public function test_admin_uploads_a_video_to_private_storage()
    {
        Storage::fake('local');
        $course = $this->course();

        $this->actingAs($this->staff('admin', 'boss'))
            ->post(route('admin.courses.lessons.store', $course), [
                'title_fa' => 'درس اول', 'title_en' => 'Lesson one', 'sort_order' => 1,
                'source' => 'none', 'is_published' => 1,
                'video' => UploadedFile::fake()->create('lesson.mp4', 1024, 'video/mp4'),
            ])->assertRedirect();

        $lesson = Lesson::where('course_id', $course->id)->firstOrFail();
        $this->assertSame('upload', $lesson->source);
        $this->assertStringStartsWith('academy/videos/', $lesson->file_path);
        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'source' => 'upload',
            'file_path' => $lesson->file_path,
        ]);
        Storage::disk('local')->assertExists($lesson->file_path);
    }

    public function test_admin_can_upload_course_cover_and_lesson_thumbnail()
    {
        Storage::fake('public');
        $admin = $this->staff('admin', 'boss');
        $image = function ($name) {
            return UploadedFile::fake()->createWithContent(
                $name.'.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/gYkAAAAASUVORK5CYII=')
            );
        };

        $this->actingAs($admin)->post(route('admin.courses.store'), [
            'slug' => 'media-course',
            'title_fa' => 'دوره رسانه',
            'title_en' => 'Media course',
            'level' => 'beginner',
            'price' => 0,
            'is_free' => 1,
            'cover_file' => $image('course-cover'),
        ])->assertRedirect();

        $course = Course::where('slug', 'media-course')->firstOrFail();
        Storage::disk('public')->assertExists($course->cover_path);

        $this->actingAs($admin)->post(route('admin.courses.lessons.store', $course), [
            'title_fa' => 'درس تصویری',
            'title_en' => 'Image lesson',
            'source' => 'none',
            'thumbnail_file' => $image('lesson-thumbnail'),
        ])->assertRedirect();

        $lesson = Lesson::where('course_id', $course->id)->firstOrFail();
        Storage::disk('public')->assertExists($lesson->thumbnail_path);
    }

    public function test_external_link_must_be_youtube_or_vimeo()
    {
        $course = $this->course();

        $this->actingAs($this->staff('admin', 'boss'))
            ->post(route('admin.courses.lessons.store', $course), [
                'title_fa' => 'درس', 'title_en' => 'Lesson', 'sort_order' => 1,
                'source' => 'external', 'external_url' => 'https://example.com/video.mp4',
            ])->assertSessionHasErrors('external_url');

        $this->assertSame(0, Lesson::count());
    }

    public function test_sales_staff_cannot_manage_courses()
    {
        $this->actingAs($this->staff('sales', 'seller'))
            ->post(route('admin.courses.store'), ['slug' => 'x', 'title_fa' => 'x', 'title_en' => 'x', 'level' => 'beginner', 'price' => 0])
            ->assertForbidden();
    }

    public function test_course_form_renders_cover_upload_controls()
    {
        $this->actingAs($this->staff('admin', 'boss'))
            ->get(route('admin.courses.create'))
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="cover_file"', false);
    }

    public function test_lesson_edit_opens_a_dedicated_form_and_saves_changes()
    {
        $course = $this->course();
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title_fa' => 'درس قدیمی',
            'title_en' => 'Old lesson',
            'source' => 'none',
            'is_published' => true,
        ]);
        $admin = $this->staff('admin', 'boss');

        $this->actingAs($admin)
            ->get(route('admin.courses.lessons', $course))
            ->assertOk()
            ->assertSee(route('admin.courses.lessons.edit', [$course, $lesson]), false);

        $editResponse = $this->get(route('admin.courses.lessons.edit', [$course, $lesson]));
        $this->assertSame(200, $editResponse->status(), $editResponse->getContent());
        $editResponse
            ->assertSee('name="title_en"', false)
            ->assertSee('Old lesson')
            ->assertDontSee('<details>', false);

        $this->put(route('admin.courses.lessons.update', [$course, $lesson]), [
            'title_fa' => 'درس جدید',
            'title_en' => 'Updated lesson',
            'source' => 'none',
            'is_published' => 1,
        ])->assertRedirect(route('admin.courses.lessons', $course));

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title_en' => 'Updated lesson',
        ]);
    }

    public function test_discount_code_is_stored_in_uppercase()
    {
        $this->actingAs($this->staff('content', 'writer'))
            ->post(route('admin.discounts.store'), ['code' => 'launch20', 'type' => 'percent', 'value' => 20])
            ->assertRedirect();

        $this->assertDatabaseHas('discount_codes', ['code' => 'LAUNCH20', 'value' => 20, 'is_active' => true]);
    }
}
