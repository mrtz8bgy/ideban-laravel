<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\CommerceSeeder;
use Database\Seeders\IdebanCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AcademyAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(IdebanCatalogSeeder::class);
        $this->seed(CommerceSeeder::class);
    }

    private function user(string $role = 'customer', string $username = 'learner'): User
    {
        $user = new User(['name' => 'Learner', 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000001']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    public function test_free_course_enrollment_is_granted_immediately()
    {
        $course = Course::where('slug', 'sample-website-launch-basics')->firstOrFail();

        $this->actingAs($this->user())->post(route('academy.enroll', $course))->assertRedirect();

        $this->assertDatabaseHas('enrollments', ['course_id' => $course->id, 'source' => 'free']);
    }

    public function test_paid_course_grants_access_only_after_bank_confirmation()
    {
        $course = Course::where('slug', 'sample-linux-server-security')->firstOrFail();
        $learner = $this->user();
        $admin = $this->user('admin', 'boss');

        $this->actingAs($learner)->post(route('academy.enroll', $course))->assertRedirect();
        $invoice = Invoice::where('course_id', $course->id)->firstOrFail();
        $this->assertSame('issued', $invoice->status);
        $this->assertDatabaseMissing('enrollments', ['course_id' => $course->id]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id, 'user_id' => $learner->id, 'amount' => $invoice->total,
            'method' => 'bank_transfer', 'status' => 'pending',
        ]);

        // Access must not be granted before staff approves the receipt.
        $this->assertDatabaseMissing('enrollments', ['course_id' => $course->id]);

        $this->actingAs($admin)->post(route('admin.payments.approve', $payment))->assertRedirect();

        $this->assertDatabaseHas('enrollments', ['course_id' => $course->id, 'user_id' => $learner->id, 'source' => 'payment']);
        $this->assertSame('paid', $invoice->fresh()->status);
    }

    public function test_discount_code_reduces_the_invoice_total()
    {
        $course = Course::where('slug', 'sample-linux-server-security')->firstOrFail();

        $this->actingAs($this->user())->post(route('academy.enroll', $course), ['discount_code' => 'sample10'])->assertRedirect();

        $invoice = Invoice::where('course_id', $course->id)->firstOrFail();
        $this->assertSame(150000, $invoice->discount);
        $this->assertSame(1350000, $invoice->total);
    }

    public function test_uploaded_lesson_video_is_protected_and_streams_only_to_enrolled_users()
    {
        Storage::fake('local');
        Storage::disk('local')->put('academy/videos/test.mp4', str_repeat('v', 2048));

        $course = Course::where('slug', 'sample-linux-server-security')->firstOrFail();
        $lesson = Lesson::where('course_id', $course->id)->where('is_free_preview', false)->firstOrFail();
        $lesson->update(['source' => 'upload', 'file_path' => 'academy/videos/test.mp4']);
        $url = route('academy.stream', $lesson);

        $this->get($url)->assertForbidden();
        $this->actingAs($this->user())->get($url)->assertForbidden();

        Enrollment::create(['user_id' => $this->user('customer', 'paid')->id, 'course_id' => $course->id, 'source' => 'payment', 'granted_at' => now()]);
        $this->actingAs(User::where('username', 'paid')->firstOrFail())
            ->get($url)
            ->assertOk()
            ->assertHeader('Content-Type', 'video/mp4');
    }

    public function test_free_preview_lesson_can_be_streamed_by_guests()
    {
        Storage::fake('local');
        Storage::disk('local')->put('academy/videos/preview.mp4', 'preview');

        $lesson = Lesson::where('is_free_preview', true)->firstOrFail();
        $lesson->update(['source' => 'upload', 'file_path' => 'academy/videos/preview.mp4']);

        $this->get(route('academy.stream', $lesson))->assertOk();
    }

    public function test_uploaded_webm_lesson_stream_uses_webm_content_type()
    {
        Storage::fake('local');
        Storage::disk('local')->put('academy/videos/preview.webm', 'preview');

        $lesson = Lesson::where('is_free_preview', true)->firstOrFail();
        $lesson->update(['source' => 'upload', 'file_path' => 'academy/videos/preview.webm']);

        $this->get(route('academy.stream', $lesson))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/webm');
    }

    public function test_locked_lesson_page_does_not_render_for_guests()
    {
        $course = Course::where('slug', 'sample-linux-server-security')->firstOrFail();
        $lesson = Lesson::where('course_id', $course->id)->where('is_free_preview', false)->firstOrFail();

        $this->get(route('academy.lesson', [$course, $lesson]))->assertForbidden();
    }
}
