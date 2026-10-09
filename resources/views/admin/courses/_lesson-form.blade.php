<form class="form-card admin-form" method="post" action="{{ $action }}" enctype="multipart/form-data">
    @csrf @if ($method !== 'POST') @method($method) @endif
    <div class="form-row">
        <div class="form-field"><label>{{ tr('عنوان فارسی', 'Persian title') }}</label><input name="title_fa" required value="{{ $lesson->title_fa }}"></div>
        <div class="form-field"><label>{{ tr('عنوان انگلیسی', 'English title') }}</label><input name="title_en" required dir="ltr" value="{{ $lesson->title_en }}"></div>
        <div class="form-field"><label>{{ tr('ترتیب', 'Order') }}</label><input type="number" min="0" name="sort_order" value="{{ $lesson->sort_order }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('منبع ویدیو', 'Video source') }}</label>
            <select name="source">
                <option value="none" {{ $lesson->source === 'none' ? 'selected' : '' }}>{{ tr('بدون ویدیو (هنوز)', 'No video yet') }}</option>
                <option value="upload" {{ $lesson->source === 'upload' ? 'selected' : '' }}>{{ tr('آپلود فایل (MP4/WebM)', 'Upload file (MP4/WebM)') }}</option>
                <option value="external" {{ $lesson->source === 'external' ? 'selected' : '' }}>{{ tr('لینک YouTube/Vimeo', 'YouTube/Vimeo link') }}</option>
            </select></div>
        <div class="form-field"><label>{{ tr('فایل ویدیو', 'Video file') }}</label><input type="file" name="video" accept="video/mp4,video/webm">@if ($lesson->file_path)<small>{{ tr('فایل فعلی ذخیره شده است.', 'A file is already stored.') }}</small>@endif</div>
        <div class="form-field"><label>{{ tr('لینک ویدیو', 'Video link') }}</label><input name="external_url" dir="ltr" value="{{ $lesson->external_url }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('مدت (ثانیه)', 'Duration (seconds)') }}</label><input type="number" min="0" name="duration_seconds" value="{{ $lesson->duration_seconds }}"></div>
        <label class="check-field"><input type="checkbox" name="is_free_preview" value="1" {{ $lesson->is_free_preview ? 'checked' : '' }}> {{ tr('پیش‌نمایش رایگان', 'Free preview') }}</label>
        <label class="check-field"><input type="checkbox" name="is_published" value="1" {{ $lesson->is_published ? 'checked' : '' }}> {{ tr('منتشر شود', 'Publish') }}</label>
    </div>
    @if ($lesson->thumbnail_image_url)<div class="form-field"><label>{{ tr('تصویر فعلی درس', 'Current lesson image') }}</label><img src="{{ $lesson->thumbnail_image_url }}" alt="" style="display:block;max-width:240px;max-height:160px;object-fit:cover;border-radius:12px"></div>@endif
    <div class="form-field"><label>{{ tr('تصویر درس (JPG، PNG یا WebP؛ حداکثر ۵ مگابایت)', 'Lesson image (JPG, PNG or WebP; max 5 MB)') }}</label><input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp">@error('thumbnail_file')<small class="field-error">{{ $message }}</small>@enderror</div>
    @if ($lesson->thumbnail_path)<label class="check-field"><input type="checkbox" name="remove_thumbnail" value="1" {{ old('remove_thumbnail') ? 'checked' : '' }}> {{ tr('حذف تصویر فعلی', 'Remove current image') }}</label>@endif
    <button class="button button-small" type="submit">{{ tr('ذخیره درس', 'Save lesson') }}</button>
</form>
