@extends('layouts.app')
@php($locale = app()->getLocale())
@php($embed = \App\Models\Video::embedUrl($video->video_url))
@section('title', $video->{'title_'.$locale})
@section('meta_description', \Illuminate\Support\Str::limit((string) $video->{'description_'.$locale}, 150))
@section('content')
<section class="page-hero"><div class="container">
    <a class="back-link" href="{{ route('videos.index') }}">{{ $locale === 'fa' ? '← بازگشت به مرکز ویدیو' : '← Back to video center' }}</a>
    <span class="eyebrow">{{ $video->category }}</span>
    <h1>{{ $video->{'title_'.$locale} }}</h1>
</div></section>
<section class="section"><div class="container detail-layout">
    <div>
        @if ($video->isUploadedVideo())
            <video class="video-embed" controls preload="metadata" playsinline style="width:100%;max-height:70vh;background:#000" @if ($video->thumbnail_image_url) poster="{{ $video->thumbnail_image_url }}" @endif src="{{ $video->uploaded_video_url }}"></video>
        @elseif ($embed)
            <div class="video-embed"><iframe src="{{ $embed }}" title="{{ $video->{'title_'.$locale} }}" loading="lazy" allow="accelerometer; encrypted-media; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe></div>
        @else
            <div class="empty-state">{{ __('content.video_unavailable') }}</div>
        @endif
        <div class="prose"><p>{{ $video->{'description_'.$locale} }}</p>
            @if ($embed)<p><a class="text-link" href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer nofollow">{{ __('content.open_on_host') }} ↗</a></p>@endif
        </div>
    </div>
    <aside class="detail-aside">
        @if ($video->service)
            <h2>{{ __('content.related_service') }}</h2>
            <p><a class="text-link" href="{{ route('services.show', $video->service->slug) }}">{{ $video->service->{'title_'.$locale} }} ↗</a></p>
        @endif
        @if ($related->isNotEmpty())
            <h2>{{ __('content.related_videos') }}</h2>
            @foreach ($related as $item)
                <div class="mini-plan"><h3><a href="{{ route('videos.show', $item->slug) }}">{{ $item->{'title_'.$locale} }}</a></h3></div>
            @endforeach
        @endif
    </aside>
</div></section>
@endsection
