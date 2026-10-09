@extends('layouts.admin')
@section('title', $customer->name)
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('مشتری', 'Customer') }}</span><h1>{{ $customer->name }}</h1></div><a class="text-link" href="{{ route('admin.customers.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<div class="form-card"><p>{{ tr('شرکت', 'Company') }}: {{ $customer->company ?: '—' }} · {{ tr('نام کاربری', 'Username') }}: <span dir="ltr">{{ $customer->username }}</span> · <span dir="ltr">{{ $customer->phone }}</span> · <span dir="ltr">{{ $customer->email }}</span></p></div>
<h2>{{ tr('سفارش‌ها', 'Orders') }}</h2>
<ul>@forelse ($orders as $o)<li><a href="{{ route('admin.orders.show', $o) }}">{{ $o->reference }}</a> — {{ optional($o->service)->{'title_'.app()->getLocale()} }} ({{ $o->status }})</li>@empty<li>—</li>@endforelse</ul>
<h2>{{ tr('فاکتورها', 'Invoices') }}</h2>
<ul>@forelse ($invoices as $i)<li><a href="{{ route('admin.invoices.show', $i) }}">{{ $i->number }}</a> — {{ number_format($i->total) }} ({{ $i->status }})</li>@empty<li>—</li>@endforelse</ul>
<h2>{{ tr('تیکت‌ها', 'Tickets') }}</h2>
<ul>@forelse ($tickets as $t)<li><a href="{{ route('admin.tickets.show', $t) }}">{{ $t->reference }}</a> — {{ $t->subject }} ({{ $t->status }})</li>@empty<li>—</li>@endforelse</ul>
<h2>{{ tr('دوره‌ها', 'Courses') }}</h2>
<ul>@forelse ($enrollments as $e)<li>{{ $e->course->{'title_'.app()->getLocale()} }} ({{ $e->source }})</li>@empty<li>—</li>@endforelse</ul>
@endsection
