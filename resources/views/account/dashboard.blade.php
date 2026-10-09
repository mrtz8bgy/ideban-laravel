@extends('layouts.account')
@section('title', tr('داشبورد', 'Dashboard'))
@section('account')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('خوش آمدید', 'Welcome') }}</span><h1 class="gold-text">{{ auth()->user()->name }}</h1></div><a class="button button-small" href="{{ route('calculator.index') }}">{{ tr('برآورد جدید', 'New estimate') }}</a></div>
<div class="stats-grid">
    <div class="stat-card"><strong>{{ $orders->count() }}</strong><span>{{ tr('آخرین سفارش‌ها', 'Recent orders') }}</span></div>
    <div class="stat-card"><strong>{{ $invoices->where('status', 'issued')->count() }}</strong><span>{{ tr('فاکتور باز', 'Open invoices') }}</span></div>
    <div class="stat-card"><strong>{{ $openTickets }}</strong><span>{{ tr('تیکت باز', 'Open tickets') }}</span></div>
    <div class="stat-card"><strong>{{ $enrollments->count() }}</strong><span>{{ tr('دوره‌های فعال', 'Active courses') }}</span></div>
</div>
<h2>{{ tr('آخرین سفارش‌ها', 'Recent orders') }}</h2>
<div class="table-wrap"><table><tbody>
@forelse ($orders as $o)<tr><td><a href="{{ route('account.orders.show', $o) }}">{{ $o->reference }}</a></td><td>{{ optional($o->service)->{'title_'.app()->getLocale()} }}</td><td>{{ $o->status }}</td></tr>@empty<tr><td>{{ tr('سفارشی ثبت نشده است.', 'No orders yet.') }}</td></tr>@endforelse
</tbody></table></div>
<h2>{{ tr('فاکتورها', 'Invoices') }}</h2>
<div class="table-wrap"><table><tbody>
@forelse ($invoices as $i)<tr><td><a href="{{ route('account.invoices.show', $i) }}">{{ $i->number }}</a></td><td>{{ number_format($i->total) }} {{ tr('تومان', 'Toman') }}</td><td>{{ $i->status }}</td></tr>@empty<tr><td>{{ tr('فاکتوری وجود ندارد.', 'No invoices.') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
