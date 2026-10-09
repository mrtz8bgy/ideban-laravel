@extends('layouts.app')
@php($locale = app()->getLocale())
@php($rendered = $article->renderedBody($locale))
@section('title', $article->{'title_'.$locale})
@section('meta_description', $article->meta_description ?: $article->{'excerpt_'.$locale})
@section('content')
<section class="page-hero"><div class="container">
    <a class="back-link" href="{{ route('blog.index') }}">{{ $locale === 'fa' ? '← بازگشت به مجله' : '← Back to journal' }}</a>
    <span class="eyebrow">{{ $article->category }}</span>
    <h1>{{ $article->{'title_'.$locale} }}</h1>
    <p>{{ $article->{'excerpt_'.$locale} }}</p>
    <p class="form-hint">{{ __('content.published') }}: {{ optional($article->published_at)->format('Y-m-d') }}@if ($article->author_name) · {{ __('content.by') }} {{ $article->author_name }}@endif</p>
</div></section>
<section class="section"><div class="container detail-layout">
    <div>
        @if ($article->cover_url)<img class="detail-image" src="{{ $article->cover_url }}" alt="" loading="lazy">@endif
        @if (count($rendered['toc']) > 2)
            <nav class="form-card" style="margin-bottom:30px;padding:22px" aria-label="{{ __('content.toc') }}">
                <strong style="color:var(--gold-light)">{{ __('content.toc') }}</strong>
                <ol class="feature-list">@foreach ($rendered['toc'] as $item)<li><a href="#{{ $item['id'] }}">{{ $item['text'] }}</a></li>@endforeach</ol>
            </nav>
        @endif
        <article class="article-body">{!! $rendered['html'] !!}</article>
        <div class="form-card" style="margin-top:44px"><p>{{ __('content.article_cta') }}</p><a class="button" href="{{ route('contact') }}">{{ __('site.request_quote') }} ↗</a></div>
    </div>
    <aside class="detail-aside">
        @if ($article->service)
            <h2>{{ __('content.related_service') }}</h2>
            <p><a class="text-link" href="{{ route('services.show', $article->service->slug) }}">{{ $article->service->{'title_'.$locale} }} ↗</a></p>
        @endif
        @if ($related->isNotEmpty())
            <h2>{{ __('content.related') }}</h2>
            @foreach ($related as $item)
                <div class="mini-plan"><h3><a href="{{ route('blog.show', $item->slug) }}">{{ $item->{'title_'.$locale} }}</a></h3></div>
            @endforeach
        @endif
    </aside>
</div></section>
@endsection
