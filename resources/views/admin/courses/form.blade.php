@extends('layouts.admin')
@section('title', $course->exists ? tr('ویرایش دوره', 'Edit course') : tr('دوره جدید', 'New course'))
@section('admin-content')
@php($isEdit = $course->exists)
<div class="admin-heading"><h1>{{ $isEdit ? tr('ویرایش دوره', 'Edit course') : tr('دوره جدید', 'New course') }}</h1><a class="text-link" href="{{ route('admin.courses.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<form class="form-card admin-form" method="post" action="{{ $isEdit ? route('admin.courses.update', $course) : route('admin.courses.store') }}">
    @csrf @if ($isEdit) @method('PUT') @endif
    <div class="form-row">
        <div class="form-field"><label>{{ tr('عنوان فارسی', 'Persian title') }}</label><input name="title_fa" required value="{{ old('title_fa', $course->title_fa) }}"></div>
        <div class="form-field"><label>{{ tr('عنوان انگلیسی', 'English title') }}</label><input name="title_en" required dir="ltr" value="{{ old('title_en', $course->title_en) }}"></div>
    </div>
    <div class="form-field"><label>{{ tr('شناسه URL (انگلیسی، بدون فاصله)', 'URL slug (Latin, no spaces)') }}</label><input name="slug" required dir="ltr" value="{{ old('slug', $course->slug) }}"></div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('مدرس (فارسی)', 'Instructor (FA)') }}</label><input name="instructor_fa" value="{{ old('instructor_fa', $course->instructor_fa) }}"></div>
        <div class="form-field"><label>{{ tr('مدرس (انگلیسی)', 'Instructor (EN)') }}</label><input name="instructor_en" dir="ltr" value="{{ old('instructor_en', $course->instructor_en) }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('خلاصه فارسی', 'Summary FA') }}</label><textarea name="summary_fa" rows="2">{{ old('summary_fa', $course->summary_fa) }}</textarea></div>
        <div class="form-field"><label>{{ tr('خلاصه انگلیسی', 'Summary EN') }}</label><textarea name="summary_en" rows="2" dir="ltr">{{ old('summary_en', $course->summary_en) }}</textarea></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('شرح فارسی', 'Description FA') }}</label><textarea name="description_fa" rows="5">{{ old('description_fa', $course->description_fa) }}</textarea></div>
        <div class="form-field"><label>{{ tr('شرح انگلیسی', 'Description EN') }}</label><textarea name="description_en" rows="5" dir="ltr">{{ old('description_en', $course->description_en) }}</textarea></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('دسته', 'Category') }}</label><select name="category"><option value="">—</option>@foreach ($categories as $c)<option value="{{ $c }}" {{ old('category', $course->category) === $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
        <div class="form-field"><label>{{ tr('سطح', 'Level') }}</label><select name="level">@foreach ($levels as $l)<option value="{{ $l }}" {{ old('level', $course->level) === $l ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
        <div class="form-field"><label>{{ tr('مدت (دقیقه)', 'Duration (minutes)') }}</label><input type="number" min="0" name="duration_minutes" value="{{ old('duration_minutes', $course->duration_minutes) }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('قیمت (تومان)', 'Price (Toman)') }}</label><input type="number" min="0" name="price" value="{{ old('price', $course->price) }}"></div>
        <div class="form-field"><label>{{ tr('ترتیب نمایش', 'Display order') }}</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order', $course->sort_order) }}"></div>
    </div>
    <div class="form-field"><label>{{ tr('آدرس تصویر کاور (اختیاری)', 'Cover image URL (optional)') }}</label><input name="cover_url" dir="ltr" value="{{ old('cover_url', $course->cover_url) }}"></div>
    <label class="check-field"><input type="checkbox" name="is_free" value="1" {{ old('is_free', $course->is_free) ? 'checked' : '' }}> {{ tr('رایگان', 'Free') }}</label>
    <label class="check-field"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }}> {{ tr('منتشر شود', 'Publish') }}</label>
    <button class="button button-small" type="submit">{{ tr('ذخیره', 'Save') }}</button>
</form>
@if ($isEdit)
<form method="post" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ tr('حذف شود؟', 'Delete?') }}')" style="margin-top:1rem">@csrf @method('DELETE')<button class="link-danger" type="submit">{{ tr('حذف دوره', 'Delete course') }}</button></form>
@endif
@endsection
