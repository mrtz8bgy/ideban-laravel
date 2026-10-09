@extends('layouts.admin')
@section('title', $item ? __('site.edit') : __('site.add_new'))
@section('admin-content')
@php
    $isEdit = (bool) $item;
@endphp
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.manage') }}</span><h1>{{ $isEdit ? __('site.edit') : __('site.add_new') }} — {{ __('site.'.$type) }}</h1></div><a class="text-link" href="{{ route('admin.catalog.index', $type) }}">← {{ __('site.back') }}</a></div>
<form class="form-card admin-form" method="post" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.catalog.update', [$type, $item->id]) : route('admin.catalog.store', $type) }}">
    @csrf
    @if ($isEdit) @method('PUT') @endif
    @foreach ($labels as $field => $label)
        @php
            $value = old($field, $item ? $item->getAttribute($field) : '');
            if (is_array($value)) $value = implode("\n", $value);
            $isCheckbox = in_array($field, ['is_active', 'is_featured', 'is_published', 'is_verified', 'show_amount']);
            $isTextArea = in_array($field, ['description_fa', 'description_en', 'summary_fa', 'summary_en', 'included_fa', 'included_en', 'excluded_fa', 'excluded_en', 'features_fa', 'features_en', 'challenge_fa', 'challenge_en', 'solution_fa', 'solution_en', 'result_fa', 'result_en', 'technologies', 'notes']);
            $inputType = in_array($field, ['valid_until', 'valid_from', 'completed_at']) ? 'date' : (in_array($field, ['setup_fee', 'recurring_fee', 'amount', 'minimum_amount', 'maximum_amount', 'delivery_days', 'sort_order', 'tariff_year']) ? 'number' : (in_array($field, ['source_url', 'image_url', 'project_url']) ? 'url' : 'text'));
        @endphp
        @if ($isCheckbox)
            <label class="check-field"><input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $item ? $item->$field : $field === 'is_active') ? 'checked' : '' }}> {{ __('fields.'.$field) }}</label>
        @else
            <div class="form-field">
                <label for="{{ $field }}">{{ __('fields.'.$field) }}</label>
                @if (in_array($field, ['category_id', 'service_id']))
                    @php
                        $options = $field === 'category_id' ? $categories : $services;
                    @endphp
                    <select id="{{ $field }}" name="{{ $field }}"><option value="">—</option>@foreach ($options as $option)<option value="{{ $option->id }}" {{ (string) $value === (string) $option->id ? 'selected' : '' }}>{{ $field === 'category_id' ? $option->{'name_'.app()->getLocale()} : $option->{'title_'.app()->getLocale()} }}</option>@endforeach</select>
                @elseif ($field === 'price_type')
                    <select id="{{ $field }}" name="{{ $field }}">
                        @php
                            $priceTypes = $type === 'prices' ? ['official' => app()->getLocale() === 'fa' ? 'تعرفه رسمی' : 'Official tariff', 'company' => __('site.price_company'), 'negotiated' => __('site.price_negotiated'), 'quote' => __('site.quote_only')] : ['company' => __('site.price_company'), 'negotiated' => __('site.price_negotiated'), 'quote' => __('site.quote_only')];
                        @endphp
                        @foreach ($priceTypes as $key => $option)<option value="{{ $key }}" {{ old($field, $item ? $item->$field : 'quote') === $key ? 'selected' : '' }}>{{ $option }}</option>@endforeach
                    </select>
                @elseif ($isTextArea)
                    <textarea id="{{ $field }}" name="{{ $field }}" rows="4">{{ $value }}</textarea>
                @else
                    <input id="{{ $field }}" type="{{ $inputType }}" name="{{ $field }}" value="{{ $value }}" @if ($inputType === 'number') min="0" @endif>
                @endif
                @error($field)<small class="field-error">{{ $message }}</small>@enderror
            </div>
        @endif
    @endforeach
    @if (in_array($type, ['categories', 'services', 'plans', 'addons', 'portfolio']))
        @php($mediaUrl = $item && $item->media_url ? $item->media_url : null)
        @if ($mediaUrl)<div class="form-field"><label>{{ app()->getLocale() === 'fa' ? 'رسانه فعلی' : 'Current media' }}</label><img src="{{ $mediaUrl }}" alt="" style="display:block;max-width:240px;max-height:160px;object-fit:cover;border-radius:12px"></div>@endif
        <div class="form-field">
            <label for="media_file">{{ app()->getLocale() === 'fa' ? 'تصویر (JPG، PNG یا WebP؛ حداکثر ۵ مگابایت)' : 'Image (JPG, PNG or WebP; max 5 MB)' }}</label>
            <input id="media_file" type="file" name="media_file" accept="image/jpeg,image/png,image/webp">
            @error('media_file')<small class="field-error">{{ $message }}</small>@enderror
        </div>
        @if ($mediaUrl)<label class="check-field"><input type="checkbox" name="remove_media" value="1" {{ old('remove_media') ? 'checked' : '' }}> {{ app()->getLocale() === 'fa' ? 'حذف تصویر فعلی' : 'Remove current image' }}</label>@endif
    @endif
    @if ($type === 'prices')<p class="form-hint">{{ __('site.official_unverified') }}</p>@endif
    @if ($type === 'services')<p class="form-hint">{{ app()->getLocale() === 'fa' ? 'خدماتی که «ویژه صفحه اصلی» باشند با این تصویر در بخش «خدمات منتخب» صفحه اصلی نمایش داده می‌شوند (توصیه: ۱۶:۹، حداقل ۸۰۰ پیکسل عرض).' : 'Services marked "Featured on homepage" show this image in the homepage "Featured services" section (recommended: 16:9, at least 800 px wide).' }}</p>@endif
    <button class="button" type="submit">{{ __('site.save') }}</button>
</form>
@endsection
