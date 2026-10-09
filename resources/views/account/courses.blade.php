@extends('layouts.account')
@section('title', tr('دوره‌های من', 'My courses'))
@section('account')
<h1 class="gold-text">{{ tr('دوره‌های من', 'My courses') }}</h1>
<div class="card-grid">
@forelse ($enrollments as $e)
    <article class="content-card"><h2>{{ $e->course->{'title_'.app()->getLocale()} }}</h2>
        <p>{{ $e->course->lessons->count() }} {{ tr('درس', 'lessons') }}</p>
        <a class="button button-small" href="{{ $e->lastLesson ? route('academy.lesson', [$e->course, $e->lastLesson]) : route('academy.show', $e->course) }}">{{ tr('ادامه یادگیری', 'Continue') }}</a></article>
@empty
    <div class="empty-state">{{ tr('هنوز دوره‌ای ندارید. ', 'You have no courses yet. ') }}<a href="{{ route('academy.index') }}">{{ tr('مشاهده آکادمی', 'Browse the academy') }}</a></div>
@endforelse
</div>
@endsection
