@extends('layouts.admin')
@section('title', tr('ویرایش درس', 'Edit lesson').' — '.$lesson->{'title_'.app()->getLocale()})
@section('admin-content')
<div class="admin-heading">
    <div><span class="eyebrow">{{ tr('درس‌ها', 'Lessons') }}</span><h1>{{ tr('ویرایش درس', 'Edit lesson') }}</h1><p class="form-hint">{{ $course->{'title_'.app()->getLocale()} }} · {{ $lesson->{'title_'.app()->getLocale()} }}</p></div>
    <a class="text-link" href="{{ route('admin.courses.lessons', $course) }}">← {{ tr('بازگشت به درس‌ها', 'Back to lessons') }}</a>
</div>
<p class="notice">{{ tr('ویدیوهای آپلودی خصوصی ذخیره می‌شوند. برای نگه‌داشتن فایل قبلی، فایل جدید انتخاب نکنید.', 'Uploaded videos remain private. Leave the file field empty to keep the existing video.') }} {{ $maxMb }} MB.</p>
@include('admin.courses._lesson-form', ['action' => route('admin.courses.lessons.update', [$course, $lesson]), 'method' => 'PUT', 'lesson' => $lesson, 'maxMb' => $maxMb])
@endsection
