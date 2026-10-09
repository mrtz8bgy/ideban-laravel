@extends('layouts.app')
@section('title', __('site.pricing'))
@section('content')
<?php $locale = app()->getLocale(); ?>
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ __('site.pricing') }}</h1><p>{{ __('site.pricing_intro') }}</p></div></section>
<section class="section"><div class="container">
    <div class="card-grid">
        @forelse ($plans as $plan)
            <article class="plan-card {{ $plan->is_featured ? 'plan-highlight' : '' }}">
                <p class="card-kicker">{{ optional($plan->service)->{'title_'.app()->getLocale()} ?: __('site.services') }}</p>
                <h2>{{ $plan->{'name_'.app()->getLocale()} }}</h2><p>{{ $plan->{'description_'.app()->getLocale()} }}</p>
                <div class="plan-costs">
                    <div><span>{{ __('site.setup') }}</span><strong>{{ $plan->price_type !== 'quote' && $plan->setup_fee !== null ? number_format($plan->setup_fee).' '.__('site.currency') : __('site.quote_only') }}</strong></div>
                    <div><span>{{ __('site.recurring') }}</span><strong>{{ $plan->price_type !== 'quote' && $plan->recurring_fee !== null ? number_format($plan->recurring_fee).' '.__('site.currency') : __('site.quote_only') }} {{ $plan->{'recurrence_'.app()->getLocale()} }}</strong></div>
                    <div><span>{{ app()->getLocale() === 'fa' ? 'نوع قیمت' : 'Price type' }}</span><strong>{{ $plan->price_type === 'company' ? __('site.price_company') : ($plan->price_type === 'negotiated' ? __('site.price_negotiated') : __('site.quote_only')) }}</strong></div>
                </div>
                @if (is_array($plan->{'features_'.app()->getLocale()}))
                    <ul class="feature-list">@foreach ($plan->{'features_'.app()->getLocale()} as $feature)<li>{{ $feature }}</li>@endforeach</ul>
                @endif
                <a class="button button-outline button-full" href="{{ route('contact', ['plan' => $plan->slug]) }}">{{ __('site.request') }}</a>
            </article>
        @empty
            <div class="empty-state">{{ __('site.no_items') }}</div>
        @endforelse
    </div>
    @if ($prices->isNotEmpty())
        <div class="section-heading price-heading"><div><h2>{{ app()->getLocale() === 'fa' ? 'تعرفه خدمات' : 'Service rates' }}</h2></div></div>
        <div class="table-wrap"><table><thead><tr><th>{{ __('site.service') }}</th><th>{{ app()->getLocale() === 'fa' ? 'نوع تعرفه' : 'Price type' }}</th><th>{{ app()->getLocale() === 'fa' ? 'مبلغ' : 'Amount' }}</th><th>{{ app()->getLocale() === 'fa' ? 'واحد' : 'Unit' }}</th><th>{{ app()->getLocale() === 'fa' ? 'سال تعرفه' : 'Tariff year' }}</th><th>{{ app()->getLocale() === 'fa' ? 'منبع' : 'Source' }}</th><th>{{ app()->getLocale() === 'fa' ? 'اعتبار' : 'Valid until' }}</th></tr></thead><tbody>
        @foreach ($prices as $price)
            <?php
                $displayAmount = '—';
                if ($price->show_amount) {
                    if ($price->amount !== null) $displayAmount = number_format($price->amount).' '.__('site.currency');
                    elseif ($price->minimum_amount !== null || $price->maximum_amount !== null) $displayAmount = ($price->minimum_amount !== null ? number_format($price->minimum_amount) : '—').' – '.($price->maximum_amount !== null ? number_format($price->maximum_amount) : '—').' '.__('site.currency');
                } else {
                    $displayAmount = __('site.quote_only');
                }
            ?>
            <tr><td>{{ $price->{'title_'.$locale} }}@if ($price->service)<br><small style="color:var(--muted)">{{ $price->service->{'title_'.$locale} }}</small>@endif</td><td>{{ $price->price_type === 'official' ? __('site.price_official') : ($price->price_type === 'company' ? __('site.price_company') : ($price->price_type === 'negotiated' ? __('site.price_negotiated') : __('site.quote_only'))) }}</td><td>{{ $displayAmount }}</td><td>{{ $price->{'unit_'.$locale} }}</td><td>{{ $price->tariff_year ?: '—' }}</td><td>@if ($price->source_url)<a href="{{ $price->source_url }}" target="_blank" rel="noopener noreferrer">{{ $price->source_name ?: $price->source_url }}</a>@else{{ $price->source_name ?: '—' }}@endif</td><td>{{ optional($price->valid_until)->format('Y-m-d') ?: '—' }}</td></tr>
        @endforeach
        </tbody></table></div>
    @endif
</div></section>
@endsection
