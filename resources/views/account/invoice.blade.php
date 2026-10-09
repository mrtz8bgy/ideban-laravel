@extends('layouts.account')
@section('title', $invoice->number)
@section('account')
@php($gateway = \App\Services\Payments\GatewayResolver::online())
<h1 class="gold-text">{{ tr('فاکتور', 'Invoice') }} {{ $invoice->number }}</h1>
<div class="form-card">
    <table class="estimate-table"><tbody>
        @foreach ($invoice->items as $item)<tr><td>{{ $item['title'] }}</td><td>{{ number_format($item['amount']) }} {{ tr('تومان', 'Toman') }}</td></tr>@endforeach
        @if ($invoice->discount)<tr><td>{{ tr('تخفیف', 'Discount') }}</td><td>- {{ number_format($invoice->discount) }}</td></tr>@endif
        @if ($invoice->extra_costs)<tr><td>{{ tr('هزینه‌های جانبی', 'Extra costs') }}</td><td>{{ number_format($invoice->extra_costs) }}</td></tr>@endif
        <tr><th>{{ tr('جمع کل', 'Total') }}</th><th>{{ number_format($invoice->total) }} {{ tr('تومان', 'Toman') }}</th></tr>
        <tr><td>{{ tr('پرداخت‌شده', 'Paid') }}</td><td>{{ number_format($invoice->paidAmount()) }}</td></tr>
    </tbody></table>
    <p>{{ tr('وضعیت', 'Status') }}: <strong>{{ $invoice->status === 'paid' ? tr('پرداخت‌شده', 'Paid') : ($invoice->status === 'cancelled' ? tr('لغو شده', 'Cancelled') : tr('صادر شده', 'Issued')) }}</strong>
    @if ($invoice->valid_until) — {{ tr('معتبر تا', 'Valid until') }} {{ $invoice->valid_until->format('Y-m-d') }}@endif</p>
    @if ($invoice->course_id)<p class="form-hint">{{ tr('پس از تأیید پرداخت، دسترسی به دوره فعال می‌شود.', 'Course access is granted once the payment is confirmed.') }}</p>@endif
</div>

@if ($invoice->isPayable())
    <div class="form-card">
        <h2>{{ tr('پرداخت آنلاین', 'Online payment') }}</h2>
        @if ($gateway)
            <form method="post" action="{{ route('account.invoices.pay', $invoice) }}">@csrf<button class="button" type="submit">{{ tr('پرداخت', 'Pay') }} {{ number_format($invoice->remainingAmount()) }} {{ tr('تومان', 'Toman') }}</button></form>
        @else
            <p class="form-hint">{{ tr('پرداخت آنلاین فعال نیست؛ از کارت به کارت استفاده کنید.', 'Online payment is not enabled; please use bank transfer.') }}</p>
        @endif
    </div>

    <div class="form-card">
        <h2>{{ tr('پرداخت کارت به کارت', 'Bank transfer') }}</h2>
        @if (config('payments.bank.card') || config('payments.bank.iban'))
            <p dir="ltr">{{ config('payments.bank.name') }} — {{ config('payments.bank.owner') }}<br>Card: {{ config('payments.bank.card') }}<br>IBAN: {{ config('payments.bank.iban') }}</p>
        @endif
        <p class="form-hint">{{ tr('شماره فاکتور را در توضیحات واریز ذکر کنید. فایل رسید: JPG، PNG یا PDF تا ۵ مگابایت.', 'Mention the invoice number in the transfer note. Receipt: JPG, PNG or PDF up to 5 MB.') }}</p>
        <form method="post" action="{{ route('account.invoices.receipt', $invoice) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-field"><label for="amount">{{ tr('مبلغ واریزی (تومان)', 'Amount transferred (Toman)') }}</label><input id="amount" type="number" name="amount" min="1000" max="{{ $invoice->remainingAmount() }}" value="{{ $invoice->remainingAmount() }}" required></div>
            <div class="form-field"><label for="receipt">{{ tr('فایل رسید', 'Receipt file') }}</label><input id="receipt" type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" required></div>
            <div class="form-field"><label for="notes">{{ tr('توضیحات', 'Notes') }}</label><input id="notes" name="notes"></div>
            <button class="button button-small" type="submit">{{ tr('ارسال رسید', 'Submit receipt') }}</button>
        </form>
    </div>
@endif

@if ($invoice->payments->isNotEmpty())
    <h2>{{ tr('پرداخت‌ها', 'Payments') }}</h2>
    <div class="table-wrap"><table><tbody>
    @foreach ($invoice->payments as $p)<tr><td>{{ number_format($p->amount) }}</td><td>{{ $p->method === 'online' ? tr('آنلاین', 'Online') : tr('کارت به کارت', 'Bank transfer') }}</td><td>{{ $p->status }}</td><td>{{ optional($p->paid_at)->format('Y-m-d H:i') }}</td></tr>@endforeach
    </tbody></table></div>
@endif
@endsection
