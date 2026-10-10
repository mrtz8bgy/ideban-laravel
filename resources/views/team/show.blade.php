@extends('layouts.app')
@section('title', $resume->{'name_'.app()->getLocale()})
@section('content')
@php $loc = app()->getLocale(); $fa = $loc === 'fa'; @endphp
<section class="section"><div class="container">
    <a class="back-link" href="{{ route('team.index') }}">← {{ tr('همه رزومه‌ها', 'All resumes') }}</a>
    @if ($resume->is_sample)<div class="notice">{{ tr('این رزومه نمونه است و متعلق به یک شخص واقعی نیست.', 'This is a sample resume and does not belong to a real person.') }}</div>@endif
    <div class="resume-head">
        @if ($resume->photo_url)<img class="team-avatar" src="{{ $resume->photo_url }}" alt="{{ $resume->{'name_'.$loc} }}">
        @else<span class="team-avatar team-avatar-placeholder" aria-hidden="true">{{ mb_substr($resume->{'name_'.$loc}, 0, 1) }}</span>@endif
        <div>
            <span class="eyebrow">{{ tr('رزومه', 'Resume') }}</span>
            <h1>{{ $resume->{'name_'.$loc} }}</h1>
            <p class="gold-text">{{ $resume->{'job_title_'.$loc} }}</p>
            <p>{{ implode(' · ', array_filter([$resume->{'location_'.$loc}, $resume->email, $resume->phone])) }}</p>
        </div>
    </div>
    @if ($resume->{'bio_'.$loc})<div class="resume-block"><h2>{{ tr('معرفی', 'About') }}</h2><p>{{ $resume->{'bio_'.$loc} }}</p></div>@endif

    <div class="resume-grid" style="margin-top:22px">
        @foreach (['experience', 'education', 'skill', 'certificate'] as $type)
            @if ($resume->itemsOf($type)->isNotEmpty())
                <div class="resume-block" id="{{ $type }}">
                    <h2>{{ \App\Models\Resume::ITEM_TYPES[$type][$loc] }}</h2>
                    @foreach ($resume->itemsOf($type) as $item)
                        <div class="resume-item">
                            <strong>{{ $item->{'title_'.$loc} }}</strong>
                            @if ($item->{'organization_'.$loc})<span>{{ $item->{'organization_'.$loc} }}</span>@endif
                            @if ($item->period)<span dir="ltr" style="text-align:{{ $fa ? 'right' : 'left' }}">{{ $item->period }}</span>@endif
                            @if ($item->{'description_'.$loc})<span>{{ $item->{'description_'.$loc} }}</span>@endif
                            @if ($type === 'skill' && $item->level !== null)<div class="skill-bar" role="img" aria-label="{{ $item->level }}%"><i style="width:{{ $item->level }}%"></i></div>@endif
                            @if ($item->url)<a class="text-link" href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">{{ tr('مشاهده', 'View') }} ↗</a>@endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
    <div class="resume-print"><button class="button button-small button-secondary" type="button" onclick="window.print()">{{ tr('چاپ / ذخیره PDF', 'Print / save as PDF') }}</button></div>
</div></section>
@endsection
