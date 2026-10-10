@extends('layouts.admin')
@section('title', $resume ? tr('ویرایش رزومه', 'Edit resume') : tr('رزومه جدید', 'New resume'))
@section('admin-content')
@php
    $isEdit = (bool) $resume;
    $v = fn ($field) => old($field, $resume ? $resume->$field : null);
@endphp
<div class="admin-heading"><div><span class="eyebrow">{{ tr('رزومه‌ها', 'Resumes') }}</span><h1>{{ $isEdit ? tr('ویرایش رزومه', 'Edit resume') : tr('رزومه جدید', 'New resume') }}</h1></div><a class="text-link" href="{{ route('admin.resumes.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>

<form class="form-card admin-form" method="post" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.resumes.update', $resume) : route('admin.resumes.store') }}">
    @csrf
    @if ($isEdit) @method('PUT') @endif
    <div class="form-row">
        <div class="form-field"><label for="name_fa">{{ tr('نام و نام خانوادگی (فارسی)', 'Full name (Persian)') }}</label><input id="name_fa" name="name_fa" value="{{ $v('name_fa') }}" required></div>
        <div class="form-field"><label for="name_en">{{ tr('نام و نام خانوادگی (انگلیسی)', 'Full name (English)') }}</label><input id="name_en" name="name_en" value="{{ $v('name_en') }}" required dir="ltr"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="job_title_fa">{{ tr('سمت (فارسی)', 'Job title (Persian)') }}</label><input id="job_title_fa" name="job_title_fa" value="{{ $v('job_title_fa') }}"></div>
        <div class="form-field"><label for="job_title_en">{{ tr('سمت (انگلیسی)', 'Job title (English)') }}</label><input id="job_title_en" name="job_title_en" value="{{ $v('job_title_en') }}" dir="ltr"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="bio_fa">{{ tr('معرفی (فارسی)', 'Bio (Persian)') }}</label><textarea id="bio_fa" name="bio_fa" rows="4">{{ $v('bio_fa') }}</textarea></div>
        <div class="form-field"><label for="bio_en">{{ tr('معرفی (انگلیسی)', 'Bio (English)') }}</label><textarea id="bio_en" name="bio_en" rows="4" dir="ltr">{{ $v('bio_en') }}</textarea></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="location_fa">{{ tr('شهر (فارسی)', 'Location (Persian)') }}</label><input id="location_fa" name="location_fa" value="{{ $v('location_fa') }}"></div>
        <div class="form-field"><label for="location_en">{{ tr('شهر (انگلیسی)', 'Location (English)') }}</label><input id="location_en" name="location_en" value="{{ $v('location_en') }}" dir="ltr"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="email">{{ tr('ایمیل', 'Email') }}</label><input id="email" type="email" name="email" value="{{ $v('email') }}" dir="ltr"></div>
        <div class="form-field"><label for="phone">{{ tr('تلفن', 'Phone') }}</label><input id="phone" name="phone" value="{{ $v('phone') }}" dir="ltr"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="slug">{{ tr('شناسه نشانی (انگلیسی، بدون فاصله؛ خالی = خودکار)', 'URL slug (Latin, no spaces; blank = automatic)') }}</label><input id="slug" name="slug" value="{{ $v('slug') }}" dir="ltr"></div>
        <div class="form-field"><label for="sort_order">{{ tr('ترتیب نمایش', 'Display order') }}</label><input id="sort_order" type="number" min="0" name="sort_order" value="{{ $v('sort_order') ?? 0 }}"></div>
    </div>
    <div class="form-field">
        <label for="photo">{{ tr('عکس پرسنلی (JPG، PNG یا WebP؛ حداکثر ۳ مگابایت)', 'Portrait photo (JPG, PNG or WebP; max 3 MB)') }}</label>
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp">
        @error('photo')<small class="field-error">{{ $message }}</small>@enderror
    </div>
    @if ($isEdit && $resume->photo_url)
        <div class="form-field"><label>{{ tr('عکس فعلی', 'Current photo') }}</label><img src="{{ $resume->photo_url }}" alt="" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid var(--gold)"></div>
        <label class="check-field"><input type="checkbox" name="remove_photo" value="1"> {{ tr('حذف عکس فعلی', 'Remove current photo') }}</label>
    @endif
    <label class="check-field"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $resume ? $resume->is_published : true) ? 'checked' : '' }}> {{ tr('منتشر شود', 'Publish') }}</label>
    <div class="form-actions">
        <a class="button button-secondary button-small" href="{{ route('admin.resumes.index') }}">{{ tr('انصراف', 'Cancel') }}</a>
        <button class="button button-small" type="submit">{{ tr('ذخیره', 'Save') }}</button>
    </div>
</form>

@if ($isEdit)
    <div class="form-card admin-form" style="margin-top:28px">
        <h2 style="font-size:20px;margin-bottom:6px">{{ tr('بخش‌های رزومه', 'Resume sections') }}</h2>
        <p class="form-hint">{{ tr('سوابق کاری، تحصیلات، مهارت‌ها و گواهینامه‌ها را اینجا اضافه یا حذف کنید.', 'Add or remove work experience, education, skills and certificates here.') }}</p>
        @foreach (\App\Models\Resume::ITEM_TYPES as $type => $label)
            <div class="resume-block" style="margin-bottom:18px">
                <h2>{{ $label[app()->getLocale()] }}</h2>
                @forelse ($resume->itemsOf($type) as $item)
                    <div class="resume-item" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                        <div>
                            <strong>{{ $item->title_fa }} · {{ $item->title_en }}</strong>
                            <span>{{ implode(' · ', array_filter([$item->organization_fa, $item->organization_en, $item->period, $item->level !== null ? $item->level.'%' : null])) }}</span>
                        </div>
                        <form method="post" action="{{ route('admin.resumes.items.destroy', [$resume, $item]) }}" onsubmit="return confirm('{{ tr('حذف شود؟', 'Delete?') }}')">@csrf @method('DELETE')<button class="button button-danger button-small" type="submit">{{ tr('حذف', 'Delete') }}</button></form>
                    </div>
                @empty
                    <p class="form-hint">{{ tr('موردی ثبت نشده است.', 'No entries yet.') }}</p>
                @endforelse
            </div>
        @endforeach
    </div>

    @php($loc = app()->getLocale())
    <form class="form-card admin-form" method="post" action="{{ route('admin.resumes.items.store', $resume) }}" style="margin-top:18px" id="resume-item-form">
        @csrf
        <h2 style="font-size:20px;margin-bottom:6px">{{ tr('افزودن مورد', 'Add entry') }}</h2>
        <p class="form-hint">{{ tr('نوع مورد را انتخاب کنید؛ برچسب فیلدها متناسب با آن تغییر می‌کند.', 'Choose the entry type; the field labels update to match.') }}</p>
        <div class="form-row">
            <div class="form-field"><label for="type">{{ tr('نوع', 'Type') }}</label>
                <select id="type" name="type" required>
                    @foreach (\App\Models\Resume::ITEM_TYPES as $type => $label)<option value="{{ $type }}" @selected(old('type') === $type)>{{ $label[$loc] }}</option>@endforeach
                </select></div>
            <div class="form-field"><label for="period"><span data-f="period">{{ \App\Models\Resume::ITEM_FIELDS['experience']['period'][$loc] }}</span></label><input id="period" name="period" dir="ltr" value="{{ old('period') }}"></div>
        </div>
        <div class="form-row">
            <div class="form-field"><label for="title_fa"><span data-f="title">{{ \App\Models\Resume::ITEM_FIELDS['experience']['title']['fa'] }}</span> ({{ tr('فارسی', 'Persian') }})</label><input id="title_fa" name="title_fa" required value="{{ old('title_fa') }}"></div>
            <div class="form-field"><label for="title_en"><span data-f="title">{{ \App\Models\Resume::ITEM_FIELDS['experience']['title']['en'] }}</span> ({{ tr('انگلیسی', 'English') }})</label><input id="title_en" name="title_en" required dir="ltr" value="{{ old('title_en') }}"></div>
        </div>
        <div class="form-row">
            <div class="form-field"><label for="organization_fa"><span data-f="org">{{ \App\Models\Resume::ITEM_FIELDS['experience']['org']['fa'] }}</span> ({{ tr('فارسی', 'Persian') }})</label><input id="organization_fa" name="organization_fa" value="{{ old('organization_fa') }}"></div>
            <div class="form-field"><label for="organization_en"><span data-f="org">{{ \App\Models\Resume::ITEM_FIELDS['experience']['org']['en'] }}</span> ({{ tr('انگلیسی', 'English') }})</label><input id="organization_en" name="organization_en" dir="ltr" value="{{ old('organization_en') }}"></div>
        </div>
        <div class="form-row">
            <div class="form-field"><label for="description_fa"><span data-f="desc">{{ \App\Models\Resume::ITEM_FIELDS['experience']['desc']['fa'] }}</span> ({{ tr('فارسی', 'Persian') }})</label><textarea id="description_fa" name="description_fa" rows="3">{{ old('description_fa') }}</textarea></div>
            <div class="form-field"><label for="description_en"><span data-f="desc">{{ \App\Models\Resume::ITEM_FIELDS['experience']['desc']['en'] }}</span> ({{ tr('انگلیسی', 'English') }})</label><textarea id="description_en" name="description_en" rows="3" dir="ltr">{{ old('description_en') }}</textarea></div>
        </div>
        <div class="form-row">
            <div class="form-field" data-only="skill"><label for="level"><span>{{ tr('میزان تسلط ۰ تا ۱۰۰ (فقط برای مهارت)', 'Proficiency 0–100 (skills only)') }}</span></label><input id="level" type="number" min="0" max="100" name="level" value="{{ old('level') }}"></div>
            <div class="form-field" data-only="certificate"><label for="url"><span>{{ tr('لینک تأیید گواهینامه (اختیاری، با https://)', 'Verification link (optional, https://)') }}</span></label><input id="url" name="url" dir="ltr" placeholder="https://" value="{{ old('url') }}"></div>
        </div>
        <div class="form-actions"><button class="button button-small" type="submit">{{ tr('افزودن', 'Add') }}</button></div>
    </form>
    <script>
    (function () {
        var fields = @json(\App\Models\Resume::ITEM_FIELDS);
        var loc = @json($loc);
        var sel = document.getElementById('type');
        function apply() {
            var f = fields[sel.value];
            document.querySelectorAll('[data-f]').forEach(function (el) {
                var key = el.getAttribute('data-f');
                var label = key === 'title' || key === 'org' || key === 'desc' || key === 'period' ? f[key] : null;
                if (label) el.textContent = label[loc] || label.fa;
            });
            document.querySelectorAll('[data-only]').forEach(function (el) {
                el.style.display = el.getAttribute('data-only') === sel.value ? '' : 'none';
            });
        }
        sel.addEventListener('change', apply);
        apply();
    })();
    </script>
@endif
@endsection
