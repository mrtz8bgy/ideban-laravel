@extends('layouts.admin')
@section('title', $course->{'title_'.app()->getLocale()})
@section('admin-content')
@php($loc = app()->getLocale())
<div class="admin-heading"><div><span class="eyebrow">{{ tr('درس‌ها', 'Lessons') }}</span><h1>{{ $course->{'title_'.$loc} }}</h1></div><a class="text-link" href="{{ route('admin.courses.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<p class="notice">{{ tr('ویدیوهای آپلودی روی سرور نگهداری شده و فقط از طریق مسیر محافظت‌شده پخش می‌شوند؛ حداکثر حجم هر ویدیو', 'Uploaded videos are stored privately on the server and streamed only through the protected route. Max size per video') }}: {{ $maxMb }} MB.</p>

<div class="table-wrap"><table><thead><tr><th>#</th><th>{{ tr('عنوان', 'Title') }}</th><th>{{ tr('منبع', 'Source') }}</th><th>{{ tr('پیش‌نمایش رایگان', 'Free preview') }}</th><th>{{ tr('منتشر', 'Published') }}</th><th></th></tr></thead><tbody>
@forelse ($course->lessons as $lesson)
<tr>
    <td>{{ $lesson->sort_order }}</td>
    <td>{{ $lesson->{'title_'.$loc} }}</td>
    <td>{{ $lesson->source === 'upload' ? tr('آپلود', 'Upload') : ($lesson->source === 'external' ? tr('لینک', 'Link') : tr('بدون ویدیو', 'No video')) }}</td>
    <td>{{ $lesson->is_free_preview ? tr('بله', 'Yes') : tr('خیر', 'No') }}</td>
    <td>{{ $lesson->is_published ? tr('بله', 'Yes') : tr('خیر', 'No') }}</td>
    <td class="table-actions">
        <details><summary>{{ tr('ویرایش', 'Edit') }}</summary>
            @include('admin.courses._lesson-form', ['action' => route('admin.courses.lessons.update', [$course, $lesson]), 'method' => 'PUT', 'lesson' => $lesson, 'maxMb' => $maxMb])
        </details>
        <form method="post" action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" onsubmit="return confirm('{{ tr('حذف شود؟', 'Delete?') }}')">@csrf @method('DELETE')<button class="link-danger" type="submit">{{ tr('حذف', 'Delete') }}</button></form>
    </td>
</tr>
@empty<tr><td colspan="6">{{ tr('هنوز درسی اضافه نشده است.', 'No lessons yet.') }}</td></tr>@endforelse
</tbody></table></div>

<h2>{{ tr('افزودن درس', 'Add lesson') }}</h2>
@include('admin.courses._lesson-form', ['action' => route('admin.courses.lessons.store', $course), 'method' => 'POST', 'lesson' => new \App\Models\Lesson(['source' => 'none', 'is_published' => true, 'sort_order' => ($course->lessons->max('sort_order') ?? 0) + 1]), 'maxMb' => $maxMb])
@endsection
