@extends('layouts.app')
@section('title', $portfolio->{'title_'.app()->getLocale()})
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('portfolio.index') }}">← {{ __('site.portfolio') }}</a><span class="eyebrow">{{ __('site.portfolio') }}</span><h1>{{ $portfolio->{'title_'.app()->getLocale()} }}</h1>@if ($portfolio->client_name)<p>{{ $portfolio->client_name }}</p>@endif</div></section>
<section class="section"><div class="container detail-layout">
    <article class="prose">
        @if ($portfolio->media_url)<img class="detail-image" src="{{ $portfolio->media_url }}" alt="{{ $portfolio->{'title_'.app()->getLocale()} }}">@endif
        @if ($portfolio->{'challenge_'.app()->getLocale()})<h2>{{ __('site.challenge') }}</h2><p>{{ $portfolio->{'challenge_'.app()->getLocale()} }}</p>@endif
        @if ($portfolio->{'solution_'.app()->getLocale()})<h2>{{ __('site.solution') }}</h2><p>{{ $portfolio->{'solution_'.app()->getLocale()} }}</p>@endif
        @if ($portfolio->{'result_'.app()->getLocale()})<h2>{{ __('site.result') }}</h2><p>{{ $portfolio->{'result_'.app()->getLocale()} }}</p>@endif
        @if ($portfolio->technologies)<h2>{{ __('site.technologies') }}</h2><div class="tag-list">@foreach ($portfolio->technologies as $technology)<span>{{ $technology }}</span>@endforeach</div>@endif
    </article>
    <aside class="detail-aside"><h2>{{ __('site.request') }}</h2><p>{{ __('site.hero_text') }}</p><a class="button button-full" href="{{ route('contact') }}">{{ __('site.contact') }}</a>@if ($portfolio->project_url)<a class="text-link external-link" href="{{ $portfolio->project_url }}" target="_blank" rel="noopener noreferrer">{{ app()->getLocale() === 'fa' ? 'مشاهده پروژه' : 'Visit project' }} ↗</a>@endif</aside>
</div></section>
@endsection
