@extends('layouts.admin')
@section('title', $invoice->number)
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('فاکتور', 'Invoice') }}</span><h1>{{ $invoice->number }}</h1></div><a class="text-link" href="{{ route('admin.invoices.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<div class="form-card">
    <p><strong>{{ tr('مشتری', 'Customer') }}:</strong> {{ optional($invoice->user)->name }} · <span dir="ltr">{{ optional($invoice->user)->phone }}</span></p>
    <table class="estimate-table"><tbody>
        @foreach ($invoice->items as $item)<tr><td>{{ $item['title'] }}</td><td>{{ number_format($item['amount']) }}</td></tr>@endforeach
        <tr><td>{{ tr('تخفیف', 'Discount') }}</td><td>{{ number_format($invoice->discount) }} @if ($invoice->notes)<small>({{ $invoice->notes }})</small>@endif</td></tr>
        <tr><td>{{ tr('هزینه جانبی', 'Extra') }}</td><td>{{ number_format($invoice->extra_costs) }}</td></tr>
        <tr><th>{{ tr('جمع', 'Total') }}</th><th>{{ number_format($invoice->total) }} {{ tr('تومان', 'Toman') }}</th></tr>
    </tbody></table>
    <p>{{ tr('وضعیت', 'Status') }}: <strong>{{ $invoice->status }}</strong> · {{ tr('پرداخت‌شده', 'Paid') }}: {{ number_format($invoice->paidAmount()) }}</p>
</div>
@unless ($invoice->isPaid())
<form class="form-card admin-form" method="post" action="{{ route('admin.invoices.update', $invoice) }}">@csrf @method('PATCH')
    <div class="form-field"><label for="status">{{ tr('وضعیت', 'Status') }}</label><select id="status" name="status"><option value="issued" {{ $invoice->status === 'issued' ? 'selected' : '' }}>{{ tr('صادر شده', 'Issued') }}</option><option value="cancelled" {{ $invoice->status === 'cancelled' ? 'selected' : '' }}>{{ tr('لغو', 'Cancelled') }}</option></select></div>
    <button class="button button-small" type="submit">{{ tr('ذخیره', 'Save') }}</button>
</form>
@endunless
<h2>{{ tr('پرداخت‌ها', 'Payments') }}</h2>
<div class="table-wrap"><table><tbody>
@forelse ($invoice->payments as $p)<tr><td>{{ number_format($p->amount) }}</td><td>{{ $p->method }}</td><td>{{ $p->status }}</td><td>{{ $p->reference_id }}</td><td><a href="{{ route('admin.payments.index', ['status' => $p->status]) }}">{{ tr('مدیریت', 'Manage') }}</a></td></tr>@empty<tr><td>{{ tr('پرداختی ثبت نشده است.', 'No payments.') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
