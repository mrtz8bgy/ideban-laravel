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
                'source' => 'upload', 'is_published' => 1,
                'video' => UploadedFile::fake()->create('lesson.mp4', 1024, 'video/mp4'),
            ])->assertRedirect();

        $lesson = Lesson::where('course_id', $course->id)->firstOrFail();
        $this->assertSame('upload', $lesson->source);
        $this->assertStringStartsWith('academy/videos/', $lesson->file_path);
        Storage::disk('local')->assertExists($lesson->file_path);
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

    public function test_discount_code_is_stored_in_uppercase()
    {
        $this->actingAs($this->staff('content', 'writer'))
            ->post(route('admin.discounts.store'), ['code' => 'launch20', 'type' => 'percent', 'value' => 20])
            ->assertRedirect();

        $this->assertDatabaseHas('discount_codes', ['code' => 'LAUNCH20', 'value' => 20, 'is_active' => true]);
    }
}
