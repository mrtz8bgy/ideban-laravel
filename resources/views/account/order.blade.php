@extends('layouts.account')
@section('title', $order->reference)
@section('account')
<h1 class="gold-text">{{ $order->reference }}</h1>
<div class="form-card">
    <p><strong>{{ tr('خدمت', 'Service') }}:</strong> {{ optional($order->service)->{'title_'.app()->getLocale()} }}</p>
    <p><strong>{{ tr('بسته', 'Plan') }}:</strong> {{ optional($order->plan)->{'name_'.app()->getLocale()} ?? '—' }}</p>
    <p><strong>{{ tr('افزودنی‌ها', 'Add-ons') }}:</strong> {{ $addons->map(fn ($a) => $a->{'name_'.app()->getLocale()})->implode('، ') ?: '—' }}</p>
    <p><strong>{{ tr('وضعیت', 'Status') }}:</strong> {{ $order->status }} — {{ $order->progress_percent }}%</p>
    @if ($order->estimate_setup !== null)<p><strong>{{ tr('برآورد راه‌اندازی', 'Estimated setup') }}:</strong> {{ number_format($order->estimate_setup) }} {{ tr('تومان', 'Toman') }}</p>@endif
    @if ($order->staff_note)<p class="notice">{{ $order->staff_note }}</p>@endif
</div>
<h2>{{ tr('فاکتورهای این سفارش', 'Invoices for this order') }}</h2>
<ul>@forelse ($order->invoices as $i)<li><a href="{{ route('account.invoices.show', $i) }}">{{ $i->number }}</a> — {{ number_format($i->total) }} {{ tr('تومان', 'Toman') }} ({{ $i->status }})</li>@empty<li>{{ tr('هنوز فاکتوری صادر نشده است.', 'No invoices yet.') }}</li>@endforelse</ul>
@endsection
