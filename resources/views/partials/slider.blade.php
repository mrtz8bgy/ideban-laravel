@php($loc = app()->getLocale())
@if ($slides->isNotEmpty())
<section class="home-slider" aria-roledescription="carousel" aria-label="{{ tr('اسلایدر', 'Slider') }}" data-slider>
    <div class="slider-track">
        @foreach ($slides as $i => $slide)
            <figure class="slide {{ $i === 0 ? 'is-current' : '' }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
                <img src="{{ $slide->imageUrl() }}" alt="" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                <div class="slide-caption container">
                    @if ($slide->is_sample)<span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>@endif
                    <h2 class="gold-text">{{ $slide->{'title_'.$loc} }}</h2>
                    @if ($slide->{'subtitle_'.$loc})<p>{{ $slide->{'subtitle_'.$loc} }}</p>@endif
                    @if ($slide->button_url && $slide->{'button_text_'.$loc})
                        <a class="button button-small" href="{{ $slide->button_url }}">{{ $slide->{'button_text_'.$loc} }}</a>
                    @endif
                </div>
            </figure>
        @endforeach
    </div>
    @if ($slides->count() > 1)
        <button type="button" class="slider-nav slider-prev" data-prev aria-label="{{ tr('اسلاید قبلی', 'Previous slide') }}">‹</button>
        <button type="button" class="slider-nav slider-next" data-next aria-label="{{ tr('اسلاید بعدی', 'Next slide') }}">›</button>
        <div class="slider-dots">@foreach ($slides as $i => $slide)<button type="button" data-dot="{{ $i }}" class="{{ $i === 0 ? 'is-active' : '' }}" aria-label="{{ $i + 1 }}"></button>@endforeach</div>
    @endif
</section>
<script>
(function () {
    var root = document.querySelector('[data-slider]');
    if (!root) return;
    var slides = root.querySelectorAll('.slide');
    var dots = root.querySelectorAll('[data-dot]');
    var current = 0, timer = null;
    function show(n) {
        if (slides.length < 2) return;
        current = (n + slides.length) % slides.length;
        slides.forEach(function (s, i) {
            s.classList.toggle('is-current', i === current);
            s.setAttribute('aria-hidden', i === current ? 'false' : 'true');
        });
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });
    }
    function start() { if (slides.length > 1) timer = setInterval(function () { show(current + 1); }, 6000); }
    function stop() { clearInterval(timer); }
    var prev = root.querySelector('[data-prev]'), next = root.querySelector('[data-next]');
    if (prev) prev.addEventListener('click', function () { stop(); show(current - 1); start(); });
    if (next) next.addEventListener('click', function () { stop(); show(current + 1); start(); });
    dots.forEach(function (d) { d.addEventListener('click', function () { stop(); show(parseInt(d.getAttribute('data-dot'), 10)); start(); }); });
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    start();
})();
</script>
@endif
