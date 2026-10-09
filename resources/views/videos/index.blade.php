@extends('layouts.app')
@section('title', __('content.videos_title'))
@section('meta_description', __('content.videos_intro'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('content.nav_videos') }}</span><h1>{{ __('content.videos_title') }}</h1><p>{{ __('content.videos_intro') }}</p></div></section>
<section class="section"><div class="container">
    <form class="filter-bar" method="get" action="{{ route('videos.index') }}" role="search">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('content.search') }}" maxlength="100" aria-label="{{ __('content.search') }}">
        @if (request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        <button class="button button-small" type="submit">{{ __('content.search_btn') }}</button>
    </form>
    @if ($categories->isNotEmpty())
        <div class="tag-list" style="margin-bottom:28px">
            <a class="chip-link {{ request('category') ? '' : 'is-active' }}" href="{{ route('videos.index', array_filter(['q' => request('q')])) }}">{{ __('content.all') }}</a>
            @foreach ($categories as $category)
                <a class="chip-link {{ request('category') === $category ? 'is-active' : '' }}" href="{{ route('videos.index', array_filter(['category' => $category, 'q' => request('q')])) }}">{{ $category }}</a>
            @endforeach
        </div>
    @endif
    @if ($videos->isNotEmpty())
        <div class="card-grid">
            @foreach ($videos as $video)
                <a class="content-card" href="{{ route('videos.show', $video->slug) }}">
                    <div class="thumb">@if ($video->thumbnail_url)<img src="{{ $video->thumbnail_url }}" alt="" loading="lazy">@else<span>IDE • TV</span>@endif<div class="play-badge"><span aria-hidden="true">▶</span></div></div>
                    <div class="body">
                        <span class="meta">{{ $video->category ?: __('content.nav_videos') }}@if ($video->duration_seconds) · {{ ceil($video->duration_seconds / 60) }} {{ __('content.minutes') }}@endif</span>
                        <h3>{{ $video->{'title_'.app()->getLocale()} }}</h3>
                        <p>{{ $video->{'description_'.app()->getLocale()} }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $videos->links() }}</div>
    @else
        <div class="empty-state">{{ __('content.no_results') }}</div>
    @endif
</div></section>
@endsection
