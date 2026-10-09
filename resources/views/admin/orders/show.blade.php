@extends('layouts.admin')
@section('title', $order->reference)
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('سفارش', 'Order') }}</span><h1>{{ $order->reference }}</h1></div><a class="text-link" href="{{ route('admin.orders.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<div class="form-card">
    <p><strong>{{ tr('مشتری', 'Customer') }}:</strong> {{ optional($order->user)->name }} · <span dir="ltr">{{ optional($order->user)->phone }}</span> · {{ optional($order->user)->company }}</p>
    <p><strong>{{ tr('خدمت', 'Service') }}:</strong> {{ optional($order->service)->{'title_'.app()->getLocale()} }} · <strong>{{ tr('بسته', 'Plan') }}:</strong> {{ optional($order->plan)->{'name_'.app()->getLocale()} ?? '—' }}</p>
    <p><strong>{{ tr('افزودنی‌ها', 'Add-ons') }}:</strong> {{ $addons->map(fn ($a) => $a->{'name_'.app()->getLocale()})->implode('، ') ?: '—' }}</p>
    <p><strong>{{ tr('یادداشت مشتری', 'Customer note') }}:</strong> {{ $order->customer_note ?: '—' }}</p>
    <p><strong>{{ tr('برآورد سیستم', 'System estimate') }}:</strong> {{ $order->estimate_setup !== null ? number_format($order->estimate_setup).' '.tr('تومان', 'Toman') : tr('استعلام قیمت', 'Price inquiry') }}</p>
</div>
<form class="form-card admin-form" method="post" action="{{ route('admin.orders.update', $order) }}">
    @csrf @method('PATCH')
    <div class="form-field"><label for="status">{{ tr('وضعیت', 'Status') }}</label><select id="status" name="status">@foreach ($statuses as $s)<option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select></div>
    <div class="form-field"><label for="progress">{{ tr('درصد پیشرفت', 'Progress %') }}</label><input id="progress" type="number" min="0" max="100" name="progress_percent" value="{{ $order->progress_percent }}"></div>
    <div class="form-field"><label for="staff_note">{{ tr('یادداشت داخلی / برای مشتری', 'Internal / customer-visible note') }}</label><textarea id="staff_note" name="staff_note" rows="3">{{ $order->staff_note }}</textarea></div>
    <div class="form-actions"><button class="button button-small" type="submit">{{ tr('ذخیره', 'Save') }}</button></div>
    </form>
<form class="form-card admin-form" method="post" action="{{ route('admin.orders.invoice', $order) }}">
    @csrf
    <h2>{{ tr('صدور فاکتور (مبالغ را کارشناس وارد می‌کند)', 'Issue invoice (amounts entered by staff)') }}</h2>
    @for ($i = 0; $i < 4; $i++)
        <div class="form-row"><div class="form-field"><label>{{ tr('شرح', 'Item') }}</label><input name="items[{{ $i }}][title]" {{ $i === 0 ? 'required' : '' }} value="{{ $i === 0 ? optional($order->service)->{'title_'.app()->getLocale()} : '' }}"></div>
        <div class="form-field"><label>{{ tr('مبلغ (تومان)', 'Amount (Toman)') }}</label><input type="number" min="0" name="items[{{ $i }}][amount]" {{ $i === 0 ? 'required' : '' }}></div></div>
    @endfor
    <div class="form-row"><div class="form-field"><label>{{ tr('هزینه جانبی', 'Extra costs') }}</label><input type="number" min="0" name="extra_costs" value="0"></div>
    <div class="form-field"><label>{{ tr('اعتبار (روز)', 'Valid (days)') }}</label><input type="number" min="1" max="90" name="days" value="14"></div></div>
    <div class="form-actions"><button class="button button-small" type="submit">{{ tr('صدور فاکتور', 'Issue invoice') }}</button></div>
    </form>
<h2>{{ tr('فاکتورها', 'Invoices') }}</h2>
<ul>@forelse ($order->invoices as $i)<li><a href="{{ route('admin.invoices.show', $i) }}">{{ $i->number }}</a> — {{ number_format($i->total) }} ({{ $i->status }})</li>@empty<li>—</li>@endforelse</ul>
@endsection
