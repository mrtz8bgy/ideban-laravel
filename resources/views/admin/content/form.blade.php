@extends('layouts.admin')
@section('title', __('site.manage').' — '.__('content.admin_'.$type))
@section('admin-content')
@php($isArticle = $type === 'articles')
@php($action = $item ? route('admin.content.update', [$type, $item->id]) : route('admin.content.store', $type))
@php($v = fn ($field, $default = '') => old($field, $item ? $item->$field : $default))
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.manage') }}</span><h1>{{ $isArticle ? __('content.admin_articles') : __('content.admin_videos') }}</h1></div><a class="button button-small button-ghost" href="{{ route('admin.content.index', $type) }}">{{ __('site.back') }}</a></div>
<form class="form-card admin-form" method="post" enctype="multipart/form-data" action="{{ $action }}">
    @csrf
    @if ($item) @method('PUT') @endif
    <div class="form-row">
        <div class="form-field"><label for="title_fa">{{ __('fields.title_fa') }}</label><input id="title_fa" name="title_fa" value="{{ $v('title_fa') }}" required maxlength="190"></div>
        <div class="form-field"><label for="title_en">Title (English)</label><input id="title_en" name="title_en" dir="ltr" value="{{ $v('title_en') }}" required maxlength="190"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="slug">شناسه URL (انگلیسی، با خط تیره)</label><input id="slug" name="slug" dir="ltr" value="{{ $v('slug') }}" required maxlength="190" pattern="[a-z0-9]+(-[a-z0-9]+)*"></div>
        <div class="form-field"><label for="category">دسته‌بندی / Category</label><input id="category" name="category" value="{{ $v('category') }}" maxlength="80"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="service_id">{{ __('site.service') }}</label><select id="service_id" name="service_id"><option value="">—</option>@foreach ($services as $service)<option value="{{ $service->id }}" {{ (string) $v('service_id') === (string) $service->id ? 'selected' : '' }}>{{ $service->title_fa }}</option>@endforeach</select></div>
        <div class="form-field"><label for="tags">برچسب‌ها (با کاما جدا کنید)</label><input id="tags" name="tags" value="{{ old('tags', $item && $item->tags ? implode(', ', $item->tags) : '') }}" maxlength="500"></div>
    </div>
    @if ($isArticle)
        <div class="form-field"><label for="excerpt_fa">خلاصه فارسی</label><textarea id="excerpt_fa" name="excerpt_fa" rows="2" maxlength="500">{{ $v('excerpt_fa') }}</textarea></div>
        <div class="form-field"><label for="excerpt_en">Excerpt (English)</label><textarea id="excerpt_en" name="excerpt_en" dir="ltr" rows="2" maxlength="500">{{ $v('excerpt_en') }}</textarea></div>
        <div class="form-field"><label for="body_fa">متن فارسی (Markdown ساده؛ عنوان بخش با ## )</label><textarea id="body_fa" name="body_fa" rows="12">{{ $v('body_fa') }}</textarea></div>
        <div class="form-field"><label for="body_en">Body (English, simple Markdown; section titles with ##)</label><textarea id="body_en" name="body_en" dir="ltr" rows="12">{{ $v('body_en') }}</textarea></div>
        <div class="form-row">
            <div class="form-field"><label for="cover_url">آدرس تصویر شاخص (اختیاری، https)</label><input id="cover_url" name="cover_url" dir="ltr" type="url" value="{{ $v('cover_url') }}" maxlength="2048"></div>
            <div class="form-field"><label for="author_name">نویسنده / Author</label><input id="author_name" name="author_name" value="{{ $v('author_name') }}" maxlength="120"></div>
        </div>
        @if ($item && $item->cover_image_url)<div class="form-field"><label>تصویر فعلی</label><img src="{{ $item->cover_image_url }}" alt="" style="display:block;max-width:240px;max-height:160px;object-fit:cover;border-radius:12px"></div>@endif
        <div class="form-field"><label for="media_file">بارگذاری تصویر شاخص (JPG، PNG یا WebP؛ حداکثر ۵ مگابایت)</label><input id="media_file" type="file" name="media_file" accept="image/jpeg,image/png,image/webp">@error('media_file')<small class="field-error">{{ $message }}</small>@enderror</div>
        @if ($item && ($item->cover_path || $item->cover_url))<label class="check-field"><input type="checkbox" name="remove_media" value="1" {{ old('remove_media') ? 'checked' : '' }}> حذف تصویر فعلی</label>@endif
        <div class="form-field"><label for="meta_title">SEO title</label><input id="meta_title" name="meta_title" dir="ltr" value="{{ $v('meta_title') }}" maxlength="190"></div>
        <div class="form-field"><label for="meta_description">Meta description</label><textarea id="meta_description" name="meta_description" dir="ltr" rows="2" maxlength="320">{{ $v('meta_description') }}</textarea></div>
    @else
        <div class="form-field"><label for="video_url">آدرس ویدیو (YouTube، Vimeo یا Aparat با https؛ در صورت آپلود فایل خالی بماند)</label><input id="video_url" name="video_url" dir="ltr" type="url" value="{{ $v('video_url') }}" maxlength="2048"></div>
        <div class="form-row">
            <div class="form-field"><label for="thumbnail_url">آدرس بندانگشتی (https)</label><input id="thumbnail_url" name="thumbnail_url" dir="ltr" type="url" value="{{ $v('thumbnail_url') }}" maxlength="2048"></div>
            <div class="form-field"><label for="duration_seconds">مدت ویدیو (ثانیه)</label><input id="duration_seconds" name="duration_seconds" type="number" min="1" max="86400" value="{{ $v('duration_seconds') }}"></div>
        </div>
        @if ($item && $item->thumbnail_image_url)<div class="form-field"><label>تصویر فعلی بندانگشتی</label><img src="{{ $item->thumbnail_image_url }}" alt="" style="display:block;max-width:240px;max-height:160px;object-fit:cover;border-radius:12px"></div>@endif
        <div class="form-row">
            <div class="form-field"><label for="media_file">تصویر بندانگشتی (JPG، PNG یا WebP؛ حداکثر ۵ مگابایت)</label><input id="media_file" type="file" name="media_file" accept="image/jpeg,image/png,image/webp">@error('media_file')<small class="field-error">{{ $message }}</small>@enderror</div>
            <div class="form-field"><label for="video_file">فایل ویدیو (MP4 یا WebM؛ حداکثر {{ config('content.public_video_max_mb') }} مگابایت)</label><input id="video_file" type="file" name="video_file" accept="video/mp4,video/webm">@if ($item && $item->isUploadedVideo())<small>فایل ویدیویی روی سرور ذخیره شده است.</small>@endif @error('video_file')<small class="field-error">{{ $message }}</small>@enderror</div>
        </div>
        @if ($item && $item->thumbnail_path)<label class="check-field"><input type="checkbox" name="remove_media" value="1" {{ old('remove_media') ? 'checked' : '' }}> حذف تصویر بندانگشتی</label>@endif
        @if ($item && $item->video_path)<label class="check-field"><input type="checkbox" name="remove_video" value="1" {{ old('remove_video') ? 'checked' : '' }}> حذف فایل ویدیویی</label>@endif
        <div class="form-field"><label for="description_fa">توضیحات فارسی</label><textarea id="description_fa" name="description_fa" rows="3" maxlength="5000">{{ $v('description_fa') }}</textarea></div>
        <div class="form-field"><label for="description_en">Description (English)</label><textarea id="description_en" name="description_en" dir="ltr" rows="3" maxlength="5000">{{ $v('description_en') }}</textarea></div>
    @endif
    <div class="form-row">
        <div class="form-field"><label for="published_at">تاریخ انتشار (خالی = همین حالا)</label><input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', optional($item ? $item->published_at : null)->format('Y-m-d\TH:i')) }}"></div>
        <label class="check-field" style="align-self:end"><input type="checkbox" name="is_published" value="1" {{ $v('is_published', false) ? 'checked' : '' }}> {{ app()->getLocale() === 'fa' ? 'منتشر شود' : 'Publish' }}</label>
    </div>
    <div class="form-actions"><a class="button button-secondary" href="{{ route('admin.content.index', $type) }}">{{ tr('انصراف','Cancel') }}</a><button class="button" type="submit">{{ __('site.save') }}</button></div>
    </form>
@endsection
