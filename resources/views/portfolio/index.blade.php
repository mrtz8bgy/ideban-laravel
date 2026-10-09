@extends('layouts.app')
@section('title', __('site.portfolio'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ __('site.portfolio') }}</h1><p>{{ __('site.portfolio_intro') }}</p></div></section>
<section class="section"><div class="container">
    @if ($portfolios->isNotEmpty())
        <div class="portfolio-grid">
            @foreach ($portfolios as $portfolio)
                <a class="work-card" href="{{ route('portfolio.show', $portfolio->slug) }}">
                    @if ($portfolio->image_url)<img src="{{ $portfolio->image_url }}" alt="{{ $portfolio->{'title_'.app()->getLocale()} }}" loading="lazy">@else<div class="work-placeholder">IDE<span>•</span>WORK</div>@endif
                    <div><div>@if (str_contains((string) $portfolio->client_name, 'Demo'))<span class="sample-tag">{{ app()->getLocale() === 'fa' ? 'نمونه نمایشی' : 'Sample' }}</span>@endif<h2>{{ $portfolio->{'title_'.app()->getLocale()} }}</h2></div><span>{{ __('site.details') }} ↗</span></div>
                </a>
            @endforeach
        </div>
        {{ $portfolios->links() }}
    @else
        <div class="empty-state">{{ __('site.no_items') }}</div>
    @endif
</div></section>
@endsection
