@extends('layouts.app')
@section('title', tr('تیم و رزومه‌ها', 'Team & resumes'))
@section('content')
@php $loc = app()->getLocale(); @endphp
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ tr('تیم و رزومه‌ها', 'Team & resumes') }}</h1><p>{{ tr('متخصصان شبکه پردازان ایده‌بان الماس، با سوابق کاری، تحصیلات و مهارت‌ها.', 'The specialists of Ideban Almas Network Processors, with their experience, education and skills.') }}</p></div></section>
<section class="section"><div class="container">
    @if ($people->isNotEmpty())
        <div class="team-grid">
            @foreach ($people as $person)
                <a class="team-card" href="{{ route('team.show', $person) }}" style="color:inherit">
                    @if ($person->photo_url)<img class="team-avatar" src="{{ $person->photo_url }}" alt="{{ $person->{'name_'.$loc} }}" loading="lazy">
                    @else<span class="team-avatar team-avatar-placeholder" aria-hidden="true">{{ mb_substr($person->{'name_'.$loc}, 0, 1) }}</span>@endif
                    <h2>{{ $person->{'name_'.$loc} }}</h2>
                    <p>{{ $person->{'job_title_'.$loc} }}</p>
                    @if ($person->is_sample)<span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>@endif
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">{{ __('site.no_items') }}</div>
    @endif
</div></section>
@endsection
