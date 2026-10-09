@extends('layouts.app')

@section('title', __('site.home'))
@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">{{ __('site.company') }}</span>
            <h1>{{ __('site.hero_title') }}</h1>
            <p>{{ __('site.hero_text') }}</p>
            <div class="button-row">
                <a class="button" href="{{ route('contact') }}">{{ __('site.request_quote') }} <span aria-hidden="true">↗</span></a>
                <a class="button button-ghost" href="{{ route('services.index') }}">{{ __('site.view_services') }}</a>
            </div>
            <p class="hero-tagline">{{ __('site.tagline') }}</p>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
            <div class="art-core"><span>IDE</span><b>01</b></div>
            <div class="art-chip chip-one">WEB</div><div class="art-chip chip-two">CLOUD</div><div class="art-chip chip-three">SECURE</div>
        </div>
    </div>
</section>
<section class="intro-band"><div class="container intro-band-inner"><div><span class="eyebrow">{{ __('site.company') }}</span><h2>{{ __('site.founder_name') }}</h2><span class="founder-title">{{ __('site.founder_title') }}</span></div><p>{{ __('site.about_intro') }}</p></div></section>
<section class="section">
    <div class="container">
        <div class="section-heading"><div><span class="eyebrow">{{ __('site.company') }}</span><h2>{{ __('site.featured_services') }}</h2></div><a class="text-link" href="{{ route('services.index') }}">{{ __('site.all_services') }} ←</a></div>
        @if ($services->isNotEmpty())
            <div class="card-grid">
                @foreach ($services as $service)
                    <article class="service-card">
                        <span class="card-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="card-kicker">{{ optional($service->category)->{'name_'.app()->getLocale()} }}</p>
                        <h3>{{ $service->{'title_'.app()->getLocale()} }}</h3>
                        <p>{{ $service->{'summary_'.app()->getLocale()} }}</p>
                        <a class="text-link" href="{{ route('services.show', $service->slug) }}">{{ __('site.details') }} <span aria-hidden="true">↗</span></a>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">{{ __('site.no_items') }}</div>
        @endif
    </div>
</section>
<section class="section section-tint">
    <div class="container">
        <div class="section-heading"><div><span class="eyebrow">{{ __('site.pricing') }}</span><h2>{{ __('site.featured_plans') }}</h2></div><a class="text-link" href="{{ route('pricing.index') }}">{{ __('site.all_plans') }} ←</a></div>
        @if ($plans->isNotEmpty())
            <div class="card-grid">
                @foreach ($plans as $plan)
                    <article class="plan-card {{ $plan->is_featured ? 'plan-highlight' : '' }}">
                        <p class="card-kicker">{{ optional($plan->service)->{'title_'.app()->getLocale()} }}</p>
                        <h3>{{ $plan->{'name_'.app()->getLocale()} }}</h3>
                        <p>{{ $plan->{'description_'.app()->getLocale()} }}</p>
                        <strong class="price">{{ $plan->price_type !== 'quote' && $plan->setup_fee !== null ? number_format($plan->setup_fee).' '.__('site.currency') : __('site.quote_only') }}</strong>
                        <a class="button button-outline" href="{{ route('contact', ['plan' => $plan->slug]) }}">{{ __('site.request') }}</a>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">{{ __('site.no_items') }}</div>
        @endif
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-heading"><div><span class="eyebrow">{{ __('site.portfolio') }}</span><h2>{{ __('site.recent_work') }}</h2></div><a class="text-link" href="{{ route('portfolio.index') }}">{{ __('site.all_portfolio') }} ←</a></div>
        @if ($portfolios->isNotEmpty())
            <div class="portfolio-grid">
                @foreach ($portfolios as $portfolio)
                    <a class="work-card" href="{{ route('portfolio.show', $portfolio->slug) }}">
                        @if ($portfolio->image_url)<img src="{{ $portfolio->image_url }}" alt="{{ $portfolio->{'title_'.app()->getLocale()} }}" loading="lazy">@else<div class="work-placeholder">IDE<span>•</span>WORK</div>@endif
                        <div><h3>{{ $portfolio->{'title_'.app()->getLocale()} }}</h3><span>{{ __('site.details') }} ↗</span></div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">{{ __('site.no_items') }}</div>
        @endif
    </div>
</section>
@if ($videos->isNotEmpty() || $articles->isNotEmpty())
<section class="section section-tint">
    <div class="container">
        <div class="section-heading"><div><span class="eyebrow">{{ __('content.nav_videos') }} · {{ __('content.nav_blog') }}</span><h2>{{ app()->getLocale() === 'fa' ? 'آموزش و تحلیل، برای تصمیم‌های بهتر' : 'Learn and decide with confidence' }}</h2></div><a class="text-link" href="{{ route('videos.index') }}">{{ __('content.nav_videos') }} ←</a></div>
        <div class="card-grid">
            @foreach ($videos as $video)
                <a class="content-card" href="{{ route('videos.show', $video->slug) }}"><div class="thumb">@if ($video->thumbnail_url)<img src="{{ $video->thumbnail_url }}" alt="" loading="lazy">@else<span>IDE • TV</span>@endif<div class="play-badge"><span aria-hidden="true">▶</span></div></div><div class="body"><h3>{{ $video->{'title_'.app()->getLocale()} }}</h3><p>{{ $video->{'description_'.app()->getLocale()} }}</p></div></a>
            @endforeach
            @foreach ($articles as $article)
                <a class="content-card" href="{{ route('blog.show', $article->slug) }}"><div class="thumb">@if ($article->cover_url)<img src="{{ $article->cover_url }}" alt="" loading="lazy">@else<span>IDE</span>@endif</div><div class="body"><span class="meta">{{ $article->category }}</span><h3>{{ $article->{'title_'.app()->getLocale()} }}</h3><p>{{ $article->{'excerpt_'.app()->getLocale()} }}</p></div></a>
            @endforeach
        </div>
    </div>
</section>
@endif
<section class="section process-section">
    <div class="container">
        <span class="eyebrow">{{ app()->getLocale() === 'fa' ? 'مسیر همکاری' : 'How we work' }}</span>
        <h2>{{ app()->getLocale() === 'fa' ? 'از نیازسنجی تا پشتیبانی، قدم‌به‌قدم' : 'A clear path from discovery to support' }}</h2>
        <div class="steps">
            @foreach ((app()->getLocale() === 'fa' ? ['شنیدن نیاز و شناخت کسب‌وکار', 'پیشنهاد راهکار و برآورد شفاف', 'اجرا، تحویل و همراهی'] : ['Understand your goals', 'Recommend a clear scope and estimate', 'Build, deliver, and support']) as $step)
                <div class="step"><b>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</b><p>{{ $step }}</p></div>
            @endforeach
        </div>
        <a class="button" href="{{ route('contact') }}">{{ __('site.contact') }} <span aria-hidden="true">↗</span></a>
    </div>
</section>
@endsection
