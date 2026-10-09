@extends('layouts.app')
@section('title', $lesson->{'title_'.app()->getLocale()})
@section('content')
@php($loc = app()->getLocale())
<section class="section"><div class="container detail-layout">
    <article>
        <p><a href="{{ route('academy.show', $course) }}">← {{ $course->{'title_'.$loc} }}</a></p>
        <h1 class="gold-text">{{ $lesson->{'title_'.$loc} }}</h1>
        @if ($lesson->source === 'upload')
            <video class="video-embed" controls controlsList="nodownload" preload="metadata" playsinline style="width:100%;max-height:70vh;background:#000" src="{{ route('academy.stream', $lesson) }}"></video>
        @elseif ($embed)
            <div class="video-embed"><iframe src="{{ $embed }}" title="{{ $lesson->{'title_'.$loc} }}" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>
        @else
            <div class="empty-state">{{ tr('ویدیوی این درس هنوز اضافه نشده است.', 'The video for this lesson has not been added yet.') }}</div>
        @endif
        @if ($enrolled)
            <form method="post" action="{{ route('academy.complete', $lesson) }}" class="form-hint">@csrf
                <button class="button button-small" type="submit" {{ $completed ? 'disabled' : '' }}>{{ $completed ? tr('کامل شد ✓', 'Completed ✓') : tr('علامت‌گذاری به‌عنوان کامل‌شده', 'Mark as complete') }}</button>
            </form>
        @endif
    </article>
    <aside class="form-card">
        <h2>{{ tr('سرفصل‌ها', 'Lessons') }}</h2>
        <ol>
        @foreach ($course->lessons as $item)
            <li>
                @if ($enrolled || $item->is_free_preview)
                    <a href="{{ route('academy.lesson', [$course, $item]) }}">{{ $item->{'title_'.$loc} }}</a>
                @else
                    {{ $item->{'title_'.$loc} }} 🔒
                @endif
            </li>
        @endforeach
        </ol>
    </aside>
</div></section>
@endsection
