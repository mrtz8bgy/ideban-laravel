@extends('layouts.admin')
@section('title', __('site.manage').' — '.__('site.'.$type))
@section('admin-content')
@php
    $columns = [
        'categories' => ['name_fa', 'slug', 'is_active'],
        'services' => ['title_fa', 'category_id', 'is_active'],
        'plans' => ['name_fa', 'service_id', 'price_type', 'setup_fee', 'recurring_fee', 'is_active'],
        'portfolio' => ['title_fa', 'slug', 'is_published'],
        'prices' => ['title_fa', 'service_id', 'price_type', 'amount', 'show_amount', 'is_active'],
        'addons' => ['name_fa', 'service_id', 'price_type', 'amount', 'is_active'],
    ][$type];
@endphp
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.manage') }}</span><h1>{{ __('site.'.$type) }}</h1></div><a class="button button-small" href="{{ route('admin.catalog.create', $type) }}">＋ {{ __('site.add_new') }}</a></div>
<div class="table-wrap"><table><thead><tr>@foreach ($columns as $column)<th>{{ __('fields.'.$column) }}</th>@endforeach<th></th></tr></thead><tbody>
@forelse ($items as $item)
    <tr>@foreach ($columns as $column)
        <td>
            @if (in_array($column, ['is_active', 'is_published', 'show_amount']))
                {{ $item->$column ? (app()->getLocale() === 'fa' ? 'بله' : 'Yes') : (app()->getLocale() === 'fa' ? 'خیر' : 'No') }}
            @elseif (in_array($column, ['category_id', 'service_id']))
                {{ $item->category ? $item->category->{'name_'.app()->getLocale()} : ($item->service ? $item->service->{'title_'.app()->getLocale()} : '—') }}
            @elseif (in_array($column, ['setup_fee', 'recurring_fee', 'amount']) && $item->$column !== null)
                {{ number_format($item->$column) }}
            @else
                {{ $item->$column ?: '—' }}
            @endif
        </td>
    @endforeach
    <td class="table-actions"><a href="{{ route('admin.catalog.edit', [$type, $item->id]) }}">{{ __('site.edit') }}</a><form method="post" action="{{ route('admin.catalog.destroy', [$type, $item->id]) }}" onsubmit="return confirm('{{ __('site.delete') }}؟')">@csrf @method('DELETE')<div class="form-actions"><button class="link-danger" type="submit">{{ __('site.delete') }}</button></div>
</form></td></tr>
@empty<tr><td colspan="{{ count($columns) + 1 }}">{{ __('site.no_items') }}</td></tr>@endforelse
</tbody></table></div>
{{ $items->links() }}
@endsection
