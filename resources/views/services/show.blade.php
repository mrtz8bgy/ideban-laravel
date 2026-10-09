@extends('layouts.app')
@section('title', $service->{'title_'.app()->getLocale()})
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('services.index') }}">← {{ __('site.services') }}</a><span class="eyebrow">{{ optional($service->category)->{'name_'.app()->getLocale()} }}</span><h1>{{ $service->{'title_'.app()->getLocale()} }}</h1><p>{{ $service->{'summary_'.app()->getLocale()} }}</p></div></section>
<section class="section"><div class="container detail-layout">
    <article class="prose">@if ($service->media_url)<img class="detail-image" src="{{ $service->media_url }}" alt="{{ $service->{'title_'.app()->getLocale()} }}">@endif<h2>{{ __('site.details') }}</h2><p>{{ $service->{'description_'.app()->getLocale()} }}</p>
        @if ($service->{'included_'.app()->getLocale()})
            <h3>{{ __('site.included') }}</h3><ul>@foreach ($service->{'included_'.app()->getLocale()} as $item)<li>{{ $item }}</li>@endforeach</ul>
        @endif
        @if ($service->{'excluded_'.app()->getLocale()})
            <h3>{{ __('site.excluded') }}</h3><ul>@foreach ($service->{'excluded_'.app()->getLocale()} as $item)<li>{{ $item }}</li>@endforeach</ul>
        @endif
        @if ($service->delivery_days)<p><strong>{{ __('site.delivery') }}:</strong> {{ $service->delivery_days }} {{ __('site.days') }}</p>@endif
    </article>
    <aside class="detail-aside"><h2>{{ __('site.plans') }}</h2>
        @forelse ($service->plans as $plan)
            <div class="mini-plan">@if ($plan->media_url)<img class="detail-image" src="{{ $plan->media_url }}" alt="" loading="lazy">@endif<h3>{{ $plan->{'name_'.app()->getLocale()} }}</h3><p>{{ $plan->{'description_'.app()->getLocale()} }}</p><strong>{{ $plan->setup_fee !== null || $plan->recurring_fee !== null ? number_format($plan->setup_fee !== null ? $plan->setup_fee : $plan->recurring_fee).' '.__('site.currency') : __('site.quote_only') }}</strong></div>
        @empty<p>{{ __('site.quote_only') }}</p>@endforelse
        @if ($service->addons->isNotEmpty())
            <h2>{{ __('site.addons') }}</h2>
            @foreach ($service->addons as $addon)
                <div class="mini-plan">@if ($addon->media_url)<img class="detail-image" src="{{ $addon->media_url }}" alt="" loading="lazy">@endif<h3>{{ $addon->{'name_'.app()->getLocale()} }}</h3><p>{{ $addon->{'description_'.app()->getLocale()} }}</p></div>
            @endforeach
        @endif
        <a class="button button-full" href="{{ route('contact', ['service' => $service->id]) }}">{{ __('site.request_quote') }}</a>
    </aside>
</div></section>
@endsection
