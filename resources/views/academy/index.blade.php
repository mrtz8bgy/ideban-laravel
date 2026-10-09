@extends('layouts.app')
@section('title', tr('آکادمی', 'Academy'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ tr('آکادمی ایده‌بان', 'Ideban Academy') }}</span><h1 class="gold-text">{{ tr('آموزش فناوری کسب‌وکار', 'Business technology training') }}</h1>
<p>{{ tr('دوره‌های کوتاه و عملی درباره راه‌اندازی، امنیت و نگهداری سامانه‌های آنلاین. دوره‌های نمونه با برچسب «نمونه» مشخص شده‌اند.', 'Short, practical courses on launching, securing and maintaining online systems. Sample courses are labelled "Sample".') }}</p></div></section>
<section class="section"><div class="container">
    <form class="filter-bar" method="get" action="{{ route('academy.index') }}">
        <select name="category"><option value="">{{ tr('همه دسته‌ها', 'All categories') }}</option>@foreach ($categories as $c)<option value="{{ $c }}" {{ request('category') === $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select>
        <select name="price"><option value="">{{ tr('همه', 'All') }}</option><option value="free" {{ request('price') === 'free' ? 'selected' : '' }}>{{ tr('رایگان', 'Free') }}</option><option value="paid" {{ request('price') === 'paid' ? 'selected' : '' }}>{{ tr('پولی', 'Paid') }}</option></select>
        <button class="button button-small" type="submit">{{ tr('فیلتر', 'Filter') }}</button>
    </form>
    <div class="card-grid">
    @forelse ($courses as $course)
        <article class="content-card">
            @if ($course->cover_image_url)<img class="detail-image" src="{{ $course->cover_image_url }}" alt="{{ $course->{'title_'.app()->getLocale()} }}" loading="lazy">@endif
            <span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>
            <span class="meta">{{ $course->category }} · {{ $course->lessons_count }} {{ tr('درس', 'lessons') }}</span>
            <h2><a href="{{ route('academy.show', $course) }}">{{ $course->{'title_'.app()->getLocale()} }}</a></h2>
            <p>{{ $course->{'summary_'.app()->getLocale()} }}</p>
            <p class="price">{{ $course->is_free ? tr('رایگان', 'Free') : number_format($course->price).' '.tr('تومان', 'Toman') }}</p>
        </article>
    @empty
        <div class="empty-state">{{ tr('دوره‌ای برای نمایش وجود ندارد.', 'No courses to show yet.') }}</div>
    @endforelse
    </div>
</div></section>
@endsection
