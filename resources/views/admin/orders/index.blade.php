@extends('layouts.admin')
@section('title', tr('سفارش‌ها', 'Orders'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('فروش', 'Sales') }}</span><h1>{{ tr('سفارش‌ها', 'Orders') }}</h1></div></div>
<form class="filter-bar" method="get"><select name="status"><option value="">{{ tr('همه وضعیت‌ها', 'All statuses') }}</option>@foreach ($statuses as $s)<option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select><div class="form-actions"><button class="button button-small" type="submit">{{ tr('فیلتر', 'Filter') }}</button></div>
</form>
<div class="table-wrap"><table><thead><tr><th>{{ tr('شماره', 'Ref') }}</th><th>{{ tr('مشتری', 'Customer') }}</th><th>{{ tr('خدمت', 'Service') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th>{{ tr('تاریخ', 'Date') }}</th><th></th></tr></thead><tbody>
@forelse ($orders as $o)<tr><td>{{ $o->reference }}</td><td>{{ optional($o->user)->name }}</td><td>{{ optional($o->service)->{'title_'.app()->getLocale()} }}</td><td>{{ $o->status }}</td><td>{{ $o->created_at->format('Y-m-d') }}</td><td><a href="{{ route('admin.orders.show', $o) }}">{{ tr('جزئیات', 'Details') }}</a></td></tr>@empty<tr><td colspan="6">{{ tr('سفارشی وجود ندارد.', 'No orders.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $orders->links() }}
@endsection
