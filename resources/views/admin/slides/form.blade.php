@extends('layouts.admin')
@section('title', tr('اسلاید', 'Slide'))
@section('admin-content')
@php($isEdit = $slide->exists)
<div class="admin-heading"><h1>{{ $isEdit ? tr('ویرایش اسلاید', 'Edit slide') : tr('اسلاید جدید', 'New slide') }}</h1><a class="text-link" href="{{ route('admin.slides.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<form class="form-card admin-form" method="post" action="{{ $isEdit ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" enctype="multipart/form-data">
    @csrf @if ($isEdit) @method('PUT') @endif
    @if ($isEdit)<img src="{{ $slide->imageUrl() }}" alt="" style="max-width:320px;border-radius:8px;margin-bottom:1rem">@endif
    <div class="form-row">
        <div class="form-field"><label>{{ tr('عنوان فارسی', 'Title (FA)') }}</label><input name="title_fa" required value="{{ old('title_fa', $slide->title_fa) }}"></div>
        <div class="form-field"><label>{{ tr('عنوان انگلیسی', 'Title (EN)') }}</label><input name="title_en" required dir="ltr" value="{{ old('title_en', $slide->title_en) }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('متن فارسی', 'Subtitle (FA)') }}</label><textarea name="subtitle_fa" rows="2">{{ old('subtitle_fa', $slide->subtitle_fa) }}</textarea></div>
        <div class="form-field"><label>{{ tr('متن انگلیسی', 'Subtitle (EN)') }}</label><textarea name="subtitle_en" rows="2" dir="ltr">{{ old('subtitle_en', $slide->subtitle_en) }}</textarea></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('متن دکمه (FA)', 'Button text (FA)') }}</label><input name="button_text_fa" value="{{ old('button_text_fa', $slide->button_text_fa) }}"></div>
        <div class="form-field"><label>{{ tr('متن دکمه (EN)', 'Button text (EN)') }}</label><input name="button_text_en" dir="ltr" value="{{ old('button_text_en', $slide->button_text_en) }}"></div>
        <div class="form-field"><label>{{ tr('آدرس دکمه', 'Button link') }}</label><input name="button_url" dir="ltr" placeholder="/services" value="{{ old('button_url', $slide->button_url) }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('تصویر (JPG, PNG, WebP تا ۴ مگابایت)', 'Image (JPG, PNG, WebP up to 4 MB)') }}</label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" {{ $isEdit ? '' : 'required' }}></div>
        <div class="form-field"><label>{{ tr('ترتیب نمایش', 'Display order') }}</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}"></div>
    </div>
    <label class="check-field"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}> {{ tr('فعال', 'Active') }}</label>
    <button class="button button-small" type="submit">{{ tr('ذخیره', 'Save') }}</button>
</form>
@endsection
