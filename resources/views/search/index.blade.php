@extends('layouts.app')
@section('title', tr('جستجو', 'Search'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ tr('جستجو در سایت', 'Search the site') }}</h1><p>{{ tr('منوها، خدمات، تعرفه‌ها، نمونه‌کارها، مقاله‌ها، ویدئوها، دوره‌ها و رزومه‌ها را جستجو کنید.', 'Search menus, services, rates, portfolio, articles, videos, courses and resumes.') }}</p></div></section>
<section class="section"><div class="container">
    <form class="search-form" method="get" action="{{ route('search') }}" role="search" data-search-form data-search-endpoint="{{ route('search.results') }}">
        <input type="search" name="q" value="{{ $q }}" placeholder="{{ tr('مثلاً: لاراول، امنیت، پشتیبانی', 'e.g. Laravel, security, support') }}" minlength="2" maxlength="100" required aria-label="{{ tr('عبارت جستجو', 'Search term') }}" autocomplete="off">
        <button class="button button-small" type="submit">{{ tr('جستجو', 'Search') }}</button>
    </form>
    <div id="search-results" aria-live="polite" aria-atomic="true">
        @include('search.results')
    </div>
</div></section>
<script src="{{ asset('js/site-search.js') }}?v={{ @filemtime(public_path('js/site-search.js')) }}" defer></script>
@endsection
