@extends('layouts.account')
@section('title', tr('سفارش‌ها', 'Orders'))
@section('account')
<h1 class="gold-text">{{ tr('سفارش‌ها', 'Orders') }}</h1>
<div class="table-wrap"><table><thead><tr><th>{{ tr('شماره', 'Ref') }}</th><th>{{ tr('خدمت', 'Service') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th>{{ tr('پیشرفت', 'Progress') }}</th><th></th></tr></thead><tbody>
@forelse ($orders as $o)<tr><td>{{ $o->reference }}</td><td>{{ optional($o->service)->{'title_'.app()->getLocale()} }}</td><td>{{ $o->status }}</td><td>{{ $o->progress_percent }}%</td><td><a href="{{ route('account.orders.show', $o) }}">{{ tr('جزئیات', 'Details') }}</a></td></tr>@empty<tr><td colspan="5">{{ tr('سفارشی ثبت نشده است.', 'No orders yet.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $orders->links() }}
@endsection
