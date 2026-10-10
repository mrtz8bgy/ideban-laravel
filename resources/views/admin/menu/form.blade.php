@extends('layouts.admin')
@section('title', tr('آیتم منو', 'Menu item'))
@section('admin-content')
@php($isEdit = $item !== null)
<div class="admin-heading"><h1>{{ $isEdit ? tr('ویرایش آیتم منو', 'Edit menu item') : tr('آیتم منوی جدید', 'New menu item') }}</h1><a class="text-link" href="{{ route('admin.menu.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<form class="form-card admin-form" method="post" action="{{ $isEdit ? route('admin.menu.update', $item) : route('admin.menu.store') }}">
    @csrf @if ($isEdit) @method('PUT') @endif
    <input type="hidden" name="location" value="header">
    @if ($errors->any())<div class="flash flash-error">@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
    <div class="form-row">
        <div class="form-field"><label for="parent_id">{{ tr('منوی والد', 'Parent item') }}</label>
            <select id="parent_id" name="parent_id">
                <option value="">{{ tr('— سطح اصلی —', '— Top level —') }}</option>
                @foreach ($parents as $p)
                    <option value="{{ $p->id }}" @selected((string) old('parent_id', $item->parent_id ?? '') === (string) $p->id)>{{ str_repeat('— ', $p->depth()) }}{{ $p->label_fa }} / {{ $p->label_en }}</option>
                @endforeach
            </select></div>
        <div class="form-field"><label for="sort_order">{{ tr('ترتیب نمایش', 'Display order') }}</label><input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="label_fa">{{ tr('عنوان (فارسی)', 'Label (FA)') }}</label><input id="label_fa" name="label_fa" required value="{{ old('label_fa', $item->label_fa ?? '') }}"></div>
        <div class="form-field"><label for="label_en">{{ tr('عنوان (انگلیسی)', 'Label (EN)') }}</label><input id="label_en" name="label_en" required dir="ltr" value="{{ old('label_en', $item->label_en ?? '') }}"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label for="url">{{ tr('آدرس', 'URL') }}</label><input id="url" name="url" required dir="ltr" placeholder="/services" value="{{ old('url', $item->url ?? '') }}"></div>
        <div class="form-field"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))> {{ tr('فعال', 'Active') }}</label></div>
    </div>
    <div class="form-actions"><button class="button" type="submit">{{ tr('ذخیره', 'Save') }}</button></div>
</form>
@endsection
