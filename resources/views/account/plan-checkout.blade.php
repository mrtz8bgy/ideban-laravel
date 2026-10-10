@extends('layouts.account')
@section('title', tr('ثبت سفارش بسته', 'Plan checkout'))
@section('account')
@php($loc = app()->getLocale())
<h1 class="gold-text">{{ tr('ثبت سفارش بسته', 'Plan checkout') }}</h1>
<div class="form-card">
    <p class="card-kicker">{{ optional($plan->service)->{'title_'.$loc} ?: __('site.services') }}</p>
    <h2>{{ $plan->{'name_'.$loc} }}</h2>
    @if ($plan->{'description_'.$loc})<p>{{ $plan->{'description_'.$loc} }}</p>@endif
    <table class="price-table">
        <tr><th>{{ tr('نوع قیمت', 'Price type') }}</th><td>{{ $plan->price_type === 'company' ? __('site.price_company') : ($plan->price_type === 'negotiated' ? __('site.price_negotiated') : __('site.quote_only')) }}</td></tr>
        @if ($plan->isQuoteOnly())
            <tr><th>{{ tr('مبلغ', 'Amount') }}</th><td><strong>{{ __('site.quote_only') }}</strong></td></tr>
        @else
            <tr><th>{{ tr('هزینه راه‌اندازی', 'Setup fee') }}</th><td>{{ number_format($plan->setup_fee) }} {{ tr('تومان', 'Toman') }}</td></tr>
            <tr><th>{{ tr('هزینه دوره‌ای', 'Recurring fee') }}</th><td>{{ number_format((int) $plan->recurring_fee) }} {{ tr('تومان', 'Toman') }} {{ $plan->{'recurrence_'.$loc} }}</td></tr>
            @if ($plan->price_type === 'company')
                <tr><th>{{ tr('مبلغ فاکتور اول', 'First invoice total') }}</th><td><strong>{{ number_format($plan->setup_fee + (int) $plan->recurring_fee) }} {{ tr('تومان', 'Toman') }}</strong></td></tr>
            @endif
        @endif
    </table>
    @if ($plan->price_type === 'company' && !$plan->isQuoteOnly())
        <p class="form-hint">{{ tr('بعد از ثبت سفارش، فاکتور صادر می‌شود و می‌توانید از بخش فاکتورها پرداخت کنید.', 'After you place the order, an invoice is issued and you can pay it from the invoices section.') }}</p>
    @elseif ($plan->isQuoteOnly() || $plan->price_type === 'negotiated')
        <p class="form-hint">{{ tr('این بسته قیمت توافقی یا استعلام‌شونده دارد. کارشناسان ما پس از بررسی، فاکتور و مبلغ نهایی را اعلام می‌کنند.', 'This plan is quoted or negotiated. Our team will send the final amount after review.') }}</p>
    @endif
    <form method="post" action="{{ route('account.plans.order', $plan) }}" class="admin-form">
        @csrf
        <div class="form-field"><label for="customer_note">{{ tr('توضیح شما (اختیاری)', 'Your note (optional)') }}</label><textarea id="customer_note" name="customer_note" rows="3">{{ old('customer_note') }}</textarea></div>
        <label class="check-field"><input type="checkbox" name="accept_terms" value="1" required> {{ tr('شرایط و قیمت‌های اعلام‌شده را می‌پذیرم.', 'I accept the terms and the prices shown.') }}</label>
        @error('accept_terms')<small class="field-error">{{ $message }}</small>@enderror
        <div class="form-actions">
            <a class="button button-secondary button-small" href="{{ route('pricing.index') }}">{{ tr('بازگشت', 'Back') }}</a>
            <button class="button button-small" type="submit">{{ tr('ثبت سفارش', 'Place order') }}</button>
        </div>
    </form>
</div>
@endsection
