<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DiscountCode;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        return view('admin.courses.index', ['courses' => Course::withCount('lessons')->orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create()
    {
        return view('admin.courses.form', ['course' => new Course(['level' => 'beginner', 'price' => 0]), 'levels' => Course::LEVELS, 'categories' => Course::CATEGORIES]);
    }

    public function store(Request $request)
    {
        $course = Course::create($this->validatedCourse($request));

        return redirect()->route('admin.courses.lessons', $course)->with('success', tr('دوره ذخیره شد. اکنون درس‌ها را اضافه کنید.', 'Course saved. Now add lessons.'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.form', ['course' => $course, 'levels' => Course::LEVELS, 'categories' => Course::CATEGORIES]);
    }

    public function update(Request $request, Course $course)
    {
        $course->update($this->validatedCourse($request, $course));

        return back()->with('success', tr('دوره ذخیره شد.', 'Course saved.'));
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', tr('دوره حذف شد.', 'Course deleted.'));
    }

    public function lessons(Course $course)
    {
        return view('admin.courses.lessons', [
            'course' => $course->load('lessons'),
            'maxMb' => config('academy.max_video_mb'),
        ]);
    }

    /**
     * Adds a lesson. Video files go to private storage and are served only through the protected stream route.
     * External links are accepted only from YouTube or Vimeo via Video::embedUrl.
     */
    public function storeLesson(Request $request, Course $course)
    {
        $data = $this->validatedLesson($request);
        $lesson = new Lesson($data + ['course_id' => $course->id]);
        $this->applyVideo($request, $lesson, $data);
        $lesson->save();

        return redirect()->route('admin.courses.lessons', $course)->with('success', tr('درس ذخیره شد.', 'Lesson saved.'));
    }

    public function updateLesson(Request $request, Course $course, Lesson $lesson)
    {
        abort_unless($lesson->course_id === $course->id, 404);
        $data = $this->validatedLesson($request);
        $lesson->fill($data);
        $this->applyVideo($request, $lesson, $data);
        $lesson->save();

        return redirect()->route('admin.courses.lessons', $course)->with('success', tr('درس ذخیره شد.', 'Lesson saved.'));
    }

    public function destroyLesson(Course $course, Lesson $lesson)
    {
        abort_unless($lesson->course_id === $course->id, 404);
        if ($lesson->file_path) {
            Storage::disk(config('academy.video_disk'))->delete($lesson->file_path);
        }
        $lesson->delete();

        return redirect()->route('admin.courses.lessons', $course)->with('success', tr('درس حذف شد.', 'Lesson deleted.'));
    }

    public function discounts()
    {
        return view('admin.courses.discounts', [
            'codes' => DiscountCode::with('course')->latest()->get(),
            'courses' => Course::orderBy('title_fa')->get(),
        ]);
    }

    public function storeDiscount(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9_-]+$/', Rule::unique('discount_codes', 'code')],
            'type' => ['required', Rule::in(['percent', 'amount'])],
            'value' => ['required', 'integer', 'min:1', 'max:100000000'],
            'course_id' => ['nullable', Rule::exists('courses', 'id')],
            'expires_at' => ['nullable', 'date'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
        ]);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = true;
        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withErrors(['value' => tr('درصد تخفیف نمی‌تواند بیشتر از ۱۰۰ باشد.', 'Percent cannot exceed 100.')])->withInput();
        }
        DiscountCode::create($data);

        return back()->with('success', tr('کد تخفیف ساخته شد.', 'Discount code created.'));
    }

    public function toggleDiscount(DiscountCode $discount)
    {
        $discount->update(['is_active' => ! $discount->is_active]);

        return back()->with('success', tr('وضعیت کد تخفیف تغییر کرد.', 'Discount status changed.'));
    }

    public function destroyDiscount(DiscountCode $discount)
    {
        $discount->delete();

        return back()->with('success', tr('کد تخفیف حذف شد.', 'Discount code deleted.'));
    }

    private function validatedCourse(Request $request, ?Course $course = null): array
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'max:190', 'alpha_dash', Rule::unique('courses', 'slug')->ignore($course?->id)],
            'title_fa' => ['required', 'string', 'max:190'],
            'title_en' => ['required', 'string', 'max:190'],
            'instructor_fa' => ['nullable', 'string', 'max:190'],
            'instructor_en' => ['nullable', 'string', 'max:190'],
            'summary_fa' => ['nullable', 'string', 'max:500'],
            'summary_en' => ['nullable', 'string', 'max:500'],
            'description_fa' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'category' => ['nullable', Rule::in(Course::CATEGORIES)],
            'level' => ['required', Rule::in(Course::LEVELS)],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'prerequisite_fa' => ['nullable', 'string', 'max:255'],
            'prerequisite_en' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'cover_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['is_free'] = $request->boolean('is_free');
        $data['is_published'] = $request->boolean('is_published');
        $data['price'] = $data['is_free'] ? 0 : (int) $data['price'];

        return $data;
    }

    private function validatedLesson(Request $request): array
    {
        $maxKb = (int) config('academy.max_video_mb') * 1024;
        $data = $request->validate([
            'title_fa' => ['required', 'string', 'max:190'],
            'title_en' => ['required', 'string', 'max:190'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'source' => ['required', Rule::in(['none', 'upload', 'external'])],
            'external_url' => ['nullable', 'url', 'max:500'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:'.$maxKb],
        ]);
        $data['is_free_preview'] = $request->boolean('is_free_preview');
        $data['is_published'] = $request->boolean('is_published');
        if ($data['source'] === 'external' && empty($data['external_url'])) {
            throw ValidationException::withMessages(['external_url' => [tr('لینک ویدیو را وارد کنید.', 'Enter the video link.')]]);
        }
        if ($data['source'] === 'external' && ! \App\Models\Video::embedUrl($data['external_url'])) {
            throw ValidationException::withMessages(['external_url' => [tr('فقط لینک‌های YouTube یا Vimeo پذیرفته می‌شود.', 'Only YouTube or Vimeo links are accepted.')]]);
        }

        return $data;
    }

    private function applyVideo(Request $request, Lesson $lesson, array $data): void
    {
        if ($data['source'] === 'upload' && $request->hasFile('video')) {
            $disk = Storage::disk(config('academy.video_disk'));
            $old = $lesson->exists ? $lesson->file_path : null;
            $ext = $request->file('video')->extension() ?: 'mp4';
            $path = $request->file('video')->storeAs(config('academy.video_dir'), Str::uuid().'.'.$ext, config('academy.video_disk'));
            $lesson->file_path = $path;
            if ($old && $old !== $path) {
                $disk->delete($old);
            }
        }
        if ($data['source'] !== 'upload') {
            $lesson->file_path = null;
        }
        if ($data['source'] !== 'external') {
            $lesson->external_url = null;
        }
        if ($data['source'] === 'upload' && ! $lesson->file_path) {
            throw ValidationException::withMessages(['video' => [tr('برای درس ویدیویی، فایل ویدیو را انتخاب کنید.', 'Choose a video file for an upload lesson.')]]);
        }
    }
}
