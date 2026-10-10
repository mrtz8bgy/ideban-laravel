@extends('layouts.app')
@section('title', tr('جستجو', 'Search'))
@section('content')
@php $loc = app()->getLocale(); @endphp
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ tr('جستجو در سایت', 'Search the site') }}</h1><p>{{ tr('خدمات، بسته‌های قیمتی، نمونه‌کارها، مقالات و رزومه‌ها را جستجو کنید.', 'Search services, pricing plans, portfolio, articles and resumes.') }}</p></div></section>
<section class="section"><div class="container">
    <form class="search-form" method="get" action="{{ route('search') }}" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="{{ tr('مثلاً: لاراول، امنیت، پشتیبانی', 'e.g. Laravel, security, support') }}" minlength="2" required aria-label="{{ tr('عبارت جستجو', 'Search term') }}">
        <button class="button button-small" type="submit">{{ tr('جستجو', 'Search') }}</button>
    </form>
    @if ($q === '')
        <div class="empty-state">{{ tr('یک عبارت وارد کنید.', 'Enter a search term.') }}</div>
    @elseif (mb_strlen($q) < 2)
        <div class="empty-state">{{ tr('حداقل ۲ حرف وارد کنید.', 'Enter at least 2 characters.') }}</div>
    @elseif ($total === 0)
        <div class="empty-state">{{ str_replace(':q', $q, tr('نتیجه‌ای برای «:q» پیدا نشد.', 'No results for ":q".')) }}</div>
    @else
        <p class="form-hint">{{ tr($total.' نتیجه برای «'.$q.'»', $total.' results for "'.$q.'"') }}</p>

        @if ($results['services']->isNotEmpty())
            <div class="result-group"><h2>{{ tr('خدمات', 'Services') }}</h2><div class="result-list">
                @foreach ($results['services'] as $s)
                    <a href="{{ route('services.show', $s->slug) }}"><span>{{ $s->{'title_'.$loc} }}</span><small>{{ $s->{'summary_'.$loc} }}</small></a>
                @endforeach
            </div></div>
        @endif
        @if ($results['plans']->isNotEmpty())
            <div class="result-group"><h2>{{ tr('بسته‌های قیمتی', 'Pricing plans') }}</h2><div class="result-list">
                @foreach ($results['plans'] as $p)
                    <a href="{{ route('pricing.index') }}"><span>{{ $p->{'name_'.$loc} }}</span><small>{{ tr('مشاهده تعرفه', 'View pricing') }}</small></a>
                @endforeach
            </div></div>
        @endif
        @if ($results['portfolio']->isNotEmpty())
            <div class="result-group"><h2>{{ tr('نمونه‌کارها', 'Portfolio') }}</h2><div class="result-list">
                @foreach ($results['portfolio'] as $w)
                    <a href="{{ route('portfolio.show', $w->slug) }}"><span>{{ $w->{'title_'.$loc} }}</span><small>{{ $w->{'challenge_'.$loc} }}</small></a>
                @endforeach
            </div></div>
        @endif
        @if ($results['articles']->isNotEmpty())
            <div class="result-group"><h2>{{ tr('مقالات', 'Articles') }}</h2><div class="result-list">
                @foreach ($results['articles'] as $a)
                    <a href="{{ route('blog.show', $a->slug) }}"><span>{{ $a->{'title_'.$loc} }}</span><small>{{ $a->{'excerpt_'.$loc} }}</small></a>
                @endforeach
            </div></div>
        @endif
        @if ($results['team']->isNotEmpty())
            <div class="result-group"><h2>{{ tr('رزومه‌ها', 'Resumes') }}</h2><div class="result-list">
                @foreach ($results['team'] as $t)
                    <a href="{{ route('team.show', $t) }}"><span>{{ $t->{'name_'.$loc} }}</span><small>{{ $t->{'job_title_'.$loc} }}</small></a>
                @endforeach
            </div></div>
        @endif
    @endif
</div></section>
@endsection
