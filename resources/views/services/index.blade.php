@extends('layouts.app')
@section('title', __('site.services'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ __('site.services') }}</h1><p>{{ __('site.services_intro') }}</p></div></section>
<section class="section"><div class="container">
    @forelse ($categories as $category)
        @if ($category->services->isNotEmpty())
            <div id="{{ $category->slug }}" class="section-heading category-heading"><div>@if ($category->media_url)<img class="detail-image" src="{{ $category->media_url }}" alt="{{ $category->{'name_'.app()->getLocale()} }}" loading="lazy">@endif<span class="eyebrow">{{ $category->{'name_'.app()->getLocale()} }}</span><h2>{{ $category->{'name_'.app()->getLocale()} }}</h2></div></div>
            <div class="card-grid">
                @foreach ($category->services as $service)
                    <article class="service-card">@if ($service->media_url)<img class="detail-image" src="{{ $service->media_url }}" alt="{{ $service->{'title_'.app()->getLocale()} }}" loading="lazy">@endif<span class="card-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $service->{'title_'.app()->getLocale()} }}</h3><p>{{ $service->{'summary_'.app()->getLocale()} }}</p><a class="text-link" href="{{ route('services.show', $service->slug) }}">{{ __('site.details') }} ↗</a></article>
                @endforeach
            </div>
        @endif
    @empty
        <div class="empty-state">{{ __('site.no_items') }}</div>
    @endforelse
</div></section>
@endsection
