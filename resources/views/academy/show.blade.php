@extends('layouts.app')
@section('title', $course->{'title_'.app()->getLocale()})
@section('content')
@php($loc = app()->getLocale())
<section class="page-hero"><div class="container">
    <span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>
    <h1 class="gold-text">{{ $course->{'title_'.$loc} }}</h1>
    <p>{{ $course->{'summary_'.$loc} }}</p>
    <p class="meta">{{ $course->{'instructor_'.$loc} }} · {{ $course->level }} · {{ $course->lessons->count() }} {{ tr('درس', 'lessons') }}</p>
</div></section>
<section class="section"><div class="container detail-layout">
    <article class="prose">
        @if ($course->cover_image_url)<img class="detail-image" src="{{ $course->cover_image_url }}" alt="{{ $course->{'title_'.$loc} }}" loading="lazy">@endif
        <h2>{{ tr('توضیحات دوره', 'About this course') }}</h2>
        <p>{{ $course->{'description_'.$loc} }}</p>
        @if ($course->{'prerequisite_'.$loc})<p><strong>{{ tr('پیش‌نیاز', 'Prerequisite') }}:</strong> {{ $course->{'prerequisite_'.$loc} }}</p>@endif
        <h2>{{ tr('سرفصل‌ها', 'Lessons') }}</h2>
        <ol>
        @foreach ($course->lessons as $lesson)
            <li>
                @if ($enrolled || $lesson->is_free_preview)
                    <a href="{{ route('academy.lesson', [$course, $lesson]) }}">{{ $lesson->{'title_'.$loc} }}</a>
                @else
                    {{ $lesson->{'title_'.$loc} }} <small>🔒</small>
                @endif
                @if ($lesson->source === 'none')<small>({{ tr('ویدیو هنوز اضافه نشده', 'video not added yet') }})</small>@endif
            </li>
        @endforeach
        </ol>
    </article>
    <aside class="form-card">
        <p class="price">{{ $course->is_free ? tr('رایگان', 'Free') : number_format($course->price).' '.tr('تومان', 'Toman') }}</p>
        @if ($enrolled)
            <p class="notice notice-success">{{ tr('این دوره در کتابخانه شماست.', 'This course is in your library.') }}</p>
            <a class="button button-full" href="{{ route('account.courses') }}">{{ tr('دوره‌های من', 'My courses') }}</a>
        @elseif (auth()->check() && auth()->user()->isCustomer())
            <form method="post" action="{{ route('academy.enroll', $course) }}">
                @csrf
                @unless ($course->is_free)
                    <div class="form-field"><label for="discount_code">{{ tr('کد تخفیف (اختیاری)', 'Discount code (optional)') }}</label><input id="discount_code" name="discount_code" dir="ltr"></div>
                @endunless
                <button class="button button-full" type="submit">{{ $course->is_free ? tr('افزودن رایگان', 'Add for free') : tr('صدور فاکتور و خرید', 'Get invoice and buy') }}</button>
            </form>
        @else
            <a class="button button-full" href="{{ route('login') }}">{{ tr('ورود برای ثبت‌نام در دوره', 'Log in to enroll') }}</a>
            <p class="form-hint"><a href="{{ route('register') }}">{{ tr('ساخت حساب کاربری', 'Create an account') }}</a></p>
        @endif
    </aside>
</div></section>
@endsection
