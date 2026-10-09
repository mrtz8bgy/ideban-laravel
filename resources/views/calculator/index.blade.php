@extends('layouts.app')
@section('title', tr('ماشین‌حساب برآورد', 'Estimate calculator'))
@section('content')
@php($loc = app()->getLocale())
<section class="page-hero"><div class="container"><span class="eyebrow">{{ tr('برآورد هزینه', 'Cost estimate') }}</span><h1 class="gold-text">{{ tr('ماشین‌حساب برآورد', 'Estimate calculator') }}</h1>
<p>{{ tr('خدمت، بسته و افزودنی‌های موردنیاز را انتخاب کنید. مبالغ فقط برای تعرفه‌های منتشرشده (شرکتی یا توافقی) محاسبه می‌شود؛ بقیه موارد به‌صورت «استعلام قیمت» ثبت می‌شوند.', 'Pick a service, plan and add-ons. Only company-published or negotiated amounts are calculated; everything else is listed as a price inquiry.') }}</p></div></section>
<section class="section"><div class="container calculator-layout">
    <form class="form-card" method="post" action="{{ route('calculator.estimate') }}" id="calc-form">
        @csrf
        <div class="form-field"><label for="service_id">{{ tr('خدمت', 'Service') }}</label>
            <select id="service_id" name="service_id" required>
                @foreach ($services as $s)<option value="{{ $s->id }}" {{ (string) old('service_id', $service->id ?? '') === (string) $s->id ? 'selected' : '' }}>{{ $s->{'title_'.$loc} }}</option>@endforeach
            </select></div>
        <div class="form-field"><label for="plan_id">{{ tr('بسته', 'Plan') }}</label>
            <select id="plan_id" name="plan_id">
                <option value="">{{ tr('بدون بسته', 'No plan') }}</option>
                @foreach ($plans as $p)<option value="{{ $p->id }}" {{ (string) ($plan->id ?? '') === (string) $p->id ? 'selected' : '' }}>{{ $p->{'name_'.$loc} }}</option>@endforeach
            </select></div>
        @foreach ($services as $s)
            <fieldset class="calc-group" data-service="{{ $s->id }}">
                <legend>{{ tr('افزودنی‌ها', 'Add-ons') }}</legend>
                @foreach ($s->addons as $a)
                    <label class="check-field"><input type="checkbox" name="addon_ids[]" value="{{ $a->id }}" {{ collect($addons ?? [])->contains('id', $a->id) ? 'checked' : '' }}> {{ $a->{'name_'.$loc} }}</label>
                @endforeach
            </fieldset>
        @endforeach
        <button class="button" type="submit">{{ tr('محاسبه', 'Calculate') }}</button>
    </form>
    <script>
        (function () {
            var select = document.getElementById('service_id');
            var groups = document.querySelectorAll('.calc-group');
            function sync() {
                groups.forEach(function (g) {
                    var active = g.getAttribute('data-service') === select.value;
                    g.hidden = !active;
                    g.querySelectorAll('input').forEach(function (el) { el.disabled = !active; });
                });
            }
            select.addEventListener('change', sync);
            sync();
        })();
    </script>

    <aside class="form-card">
        @if ($result)
            <h2>{{ tr('نتیجه برآورد', 'Estimate') }}</h2>
            <table class="estimate-table"><tbody>
            @foreach ($result['lines'] as $line)
                <tr><td>{{ $line['label'] }}</td><td>@if ($line['quote'])<span class="badge-quote">{{ tr('استعلام قیمت', 'Price inquiry') }}</span>@else{{ number_format((int) $line['amount']) }} {{ tr('تومان', 'Toman') }}@if (!empty($line['recurring'])) <small>+ {{ number_format((int) $line['recurring']) }} / {{ tr('دوره', 'period') }}</small>@endif @endif</td></tr>
            @endforeach
            </tbody></table>
            @if ($result['needs_quote'])
                <p class="notice">{{ tr('برخی موارد نیاز به استعلام قیمت دارند؛ مجموع نهایی پس از بررسی اعلام می‌شود.', 'Some items need a price inquiry; the final total is confirmed after review.') }}</p>
            @else
                <p><strong>{{ tr('هزینه راه‌اندازی', 'Setup') }}:</strong> <span class="price">{{ number_format($result['setup']) }} {{ tr('تومان', 'Toman') }}</span>
                @if ($result['recurring'])<br><strong>{{ tr('هزینه دوره‌ای', 'Recurring') }}:</strong> {{ number_format($result['recurring']) }} {{ tr('تومان', 'Toman') }}@endif</p>
            @endif
            <p class="form-hint">{{ tr('این برآورد بر اساس تعرفه‌های ثبت‌شده در سامانه است و قرارداد نهایی نیست.', 'This estimate uses the rates recorded in the system and is not a final contract.') }}</p>

            <form method="post" action="{{ route('calculator.quote') }}" class="quote-form">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="plan_id" value="{{ $plan->id ?? '' }}">
                @foreach ($addons as $a)<input type="hidden" name="addon_ids[]" value="{{ $a->id }}">@endforeach
                <div class="form-field"><label for="name">{{ tr('نام', 'Name') }}</label><input id="name" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}"></div>
                <div class="form-field"><label for="phone">{{ tr('تلفن', 'Phone') }}</label><input id="phone" name="phone" required dir="ltr" value="{{ old('phone', auth()->user()->phone ?? '') }}"></div>
                <div class="form-field"><label for="email">{{ tr('ایمیل', 'Email') }}</label><input id="email" type="email" name="email" dir="ltr" value="{{ old('email', auth()->user()->email ?? '') }}"></div>
                <div class="form-field"><label for="company">{{ tr('شرکت', 'Company') }}</label><input id="company" name="company" value="{{ old('company', auth()->user()->company ?? '') }}"></div>
                <div class="form-field"><label for="message">{{ tr('توضیحات', 'Notes') }}</label><textarea id="message" name="message" rows="3">{{ old('message') }}</textarea></div>
                <div class="honeypot" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
                <button class="button button-full" type="submit">{{ tr('ثبت درخواست پیش‌فاکتور', 'Request a quote') }}</button>
            </form>
        @else
            <h2>{{ tr('راهنما', 'How it works') }}</h2>
            <p>{{ tr('نتیجه برآورد پس از محاسبه اینجا نمایش داده می‌شود.', 'Your estimate appears here after calculation.') }}</p>
        @endif
    </aside>
</div></section>
@endsection
