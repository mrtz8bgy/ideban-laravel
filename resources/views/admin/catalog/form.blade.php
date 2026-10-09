@extends('layouts.admin')
@section('title', $item ? __('site.edit') : __('site.add_new'))
@section('admin-content')
@php($isEdit = (bool) $item)
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.manage') }}</span><h1>{{ $isEdit ? __('site.edit') : __('site.add_new') }} — {{ __('site.'.$type) }}</h1></div><a class="text-link" href="{{ route('admin.catalog.index', $type) }}">← {{ __('site.back') }}</a></div>
<form class="form-card admin-form" method="post" action="{{ $isEdit ? route('admin.catalog.update', [$type, $item->id]) : route('admin.catalog.store', $type) }}">
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
                    @php($options = $field === 'category_id' ? $categories : $services)
                    <select id="{{ $field }}" name="{{ $field }}"><option value="">—</option>@foreach ($options as $option)<option value="{{ $option->id }}" {{ (string) $value === (string) $option->id ? 'selected' : '' }}>{{ $field === 'category_id' ? $option->{'name_'.app()->getLocale()} : $option->{'title_'.app()->getLocale()} }}</option>@endforeach</select>
                @elseif ($field === 'price_type')
                    <select id="{{ $field }}" name="{{ $field }}">
                        @php($priceTypes = $type === 'prices' ? ['official' => app()->getLocale() === 'fa' ? 'تعرفه رسمی' : 'Official tariff', 'company' => __('site.price_company'), 'negotiated' => __('site.price_negotiated'), 'quote' => __('site.quote_only')] : ['company' => __('site.price_company'), 'negotiated' => __('site.price_negotiated'), 'quote' => __('site.quote_only')])
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
    @if ($type === 'prices')<p class="form-hint">{{ __('site.official_unverified') }}</p>@endif
    <button class="button" type="submit">{{ __('site.save') }}</button>
</form>
@endsection
