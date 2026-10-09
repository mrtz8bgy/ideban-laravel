@extends('layouts.app')
@section('title', __('content.blog_title'))
@section('meta_description', __('content.blog_intro'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('content.nav_blog') }}</span><h1>{{ __('content.blog_title') }}</h1><p>{{ __('content.blog_intro') }}</p></div></section>
<section class="section"><div class="container">
    <form class="filter-bar" method="get" action="{{ route('blog.index') }}" role="search">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('content.search') }}" maxlength="100" aria-label="{{ __('content.search') }}">
        @if (request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        <button class="button button-small" type="submit">{{ __('content.search_btn') }}</button>
    </form>
    @if ($categories->isNotEmpty())
        <div class="tag-list" style="margin-bottom:28px">
            <a class="chip-link {{ request('category') ? '' : 'is-active' }}" href="{{ route('blog.index', array_filter(['q' => request('q')])) }}">{{ __('content.all') }}</a>
            @foreach ($categories as $category)
                <a class="chip-link {{ request('category') === $category ? 'is-active' : '' }}" href="{{ route('blog.index', array_filter(['category' => $category, 'q' => request('q')])) }}">{{ $category }}</a>
            @endforeach
        </div>
    @endif
    @if ($articles->isNotEmpty())
        <div class="card-grid">
            @foreach ($articles as $article)
                <a class="content-card" href="{{ route('blog.show', $article->slug) }}">
                    <div class="thumb">@if ($article->cover_url)<img src="{{ $article->cover_url }}" alt="" loading="lazy">@else<span>IDE</span>@endif</div>
                    <div class="body">
                        <span class="meta">{{ $article->category ?: __('content.nav_blog') }} · {{ optional($article->published_at)->format('Y-m-d') }}</span>
                        <h3>{{ $article->{'title_'.app()->getLocale()} }}</h3>
                        <p>{{ $article->{'excerpt_'.app()->getLocale()} }}</p>
                        <span class="text-link">{{ __('content.read_more') }} ↗</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $articles->links() }}</div>
    @else
        <div class="empty-state">{{ __('content.no_results') }}</div>
    @endif
</div></section>
@endsection
