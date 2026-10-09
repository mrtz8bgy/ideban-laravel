<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcademyController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::published()->orderBy('sort_order')->orderBy('id');
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }
        if ($request->query('price') === 'free') {
            $query->where('is_free', true);
        } elseif ($request->query('price') === 'paid') {
            $query->where('is_free', false);
        }
        if ($request->filled('level') && in_array($request->query('level'), Course::LEVELS, true)) {
            $query->where('level', $request->query('level'));
        }

        return view('academy.index', [
            'courses' => $query->withCount('lessons')->get(),
            'categories' => Course::CATEGORIES,
        ]);
    }

    public function show(Request $request, Course $course)
    {
        abort_unless($course->is_published, 404);
        $course->load('lessons');
        $enrolled = $this->enrolled($request, $course);

        return view('academy.show', ['course' => $course, 'enrolled' => $enrolled]);
    }

    /** Free courses are granted immediately; paid courses create an invoice that becomes access after payment. */
    public function enroll(Request $request, Course $course)
    {
        abort_unless($course->is_published, 404);
        if ($this->enrolled($request, $course)) {
            $firstLesson = $course->lessons->first();

            return redirect()->route('academy.lesson', [$course, $firstLesson ? $firstLesson->id : 0]);
        }

        if ($course->is_free) {
            Enrollment::firstOrCreate(
                ['user_id' => $request->user()->id, 'course_id' => $course->id],
                ['source' => 'free', 'granted_at' => now()]
            );

            return redirect()->route('academy.show', $course)->with('success', tr('دوره به کتابخانه شما اضافه شد.', 'The course was added to your library.'));
        }

        $data = $request->validate(['discount_code' => ['nullable', 'string', 'max:40']]);
        $invoice = Billing::invoiceForCourse($request->user(), $course, $data['discount_code'] ?? null);

        return redirect()->route('account.invoices.show', $invoice)->with('success', tr('فاکتور دوره صادر شد. پس از پرداخت، دسترسی فعال می‌شود.', 'Your course invoice is ready. Access is granted after payment.'));
    }

    public function lesson(Request $request, Course $course, Lesson $lesson)
    {
        abort_unless($course->is_published && $lesson->course_id === $course->id && $lesson->is_published, 404);
        $enrolled = $this->enrolled($request, $course);
        abort_unless($enrolled || $lesson->is_free_preview, 403);

        if ($enrolled && $request->user()) {
            Enrollment::where('user_id', $request->user()->id)->where('course_id', $course->id)->update(['last_lesson_id' => $lesson->id]);
        }

        $course->load('lessons');

        return view('academy.lesson', [
            'course' => $course,
            'lesson' => $lesson,
            'enrolled' => $enrolled,
            // External embed URLs are only handed to users who may watch the lesson.
            'embed' => $lesson->source === 'external' ? $lesson->externalEmbedUrl() : null,
            'completed' => $request->user() ? LessonProgress::where('user_id', $request->user()->id)->where('lesson_id', $lesson->id)->whereNotNull('completed_at')->exists() : false,
        ]);
    }

    /** Streams uploaded lesson videos from private storage. Range requests are handled by the file response. */
    public function stream(Request $request, Lesson $lesson)
    {
        $course = $lesson->course;
        abort_unless($course->is_published && $lesson->is_published && $lesson->source === 'upload' && $lesson->file_path, 404);
        abort_unless($this->enrolled($request, $course) || $lesson->is_free_preview, 403);

        $path = Storage::disk(config('academy.video_disk'))->path($lesson->file_path);
        abort_unless(is_file($path), 404);

        $contentType = pathinfo($path, PATHINFO_EXTENSION) === 'webm' ? 'video/webm' : 'video/mp4';

        return response()->file($path, ['Content-Type' => $contentType, 'Cache-Control' => 'private, no-store']);
    }

    public function complete(Request $request, Lesson $lesson)
    {
        $course = $lesson->course;
        abort_unless($this->enrolled($request, $course), 403);

        LessonProgress::updateOrCreate(
            ['user_id' => $request->user()->id, 'lesson_id' => $lesson->id],
            ['completed_at' => now()]
        );

        return back()->with('success', tr('درس به عنوان کامل‌شده ثبت شد.', 'Lesson marked as complete.'));
    }

    private function enrolled(Request $request, Course $course): bool
    {
        return $request->user() !== null
            && Enrollment::where('user_id', $request->user()->id)->where('course_id', $course->id)->exists();
    }
}
