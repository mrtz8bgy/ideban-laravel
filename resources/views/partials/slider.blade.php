@php($loc = app()->getLocale())
@if ($slides->isNotEmpty())
@php($scroll3d = $slides->count() > 1)
@if ($scroll3d)<div class="scroll3d" data-scroll3d style="--n: {{ $slides->count() }}"><div class="scroll3d-sticky">@endif
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
                        <a class="button button-small" href="{{ \App\Support\AppLink::resolve($slide->button_url) }}">{{ $slide->{'button_text_'.$loc} }}</a>
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
@if ($scroll3d)</div></div>@endif
<script>
(function () {
    var root = document.querySelector('[data-slider]');
    if (!root) return;
    var slides = root.querySelectorAll('.slide');
    var dots = root.querySelectorAll('[data-dot]');
    var prevBtn = root.querySelector('[data-prev]'), nextBtn = root.querySelector('[data-next]');

    function initPointerEffects() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches
            || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

        var frame = null;
        root.addEventListener('pointermove', function (event) {
            if (event.pointerType !== 'mouse') return;
            var rect = root.getBoundingClientRect();
            var x = Math.max(-1, Math.min(1, ((event.clientX - rect.left) / rect.width) * 2 - 1));
            var y = Math.max(-1, Math.min(1, ((event.clientY - rect.top) / rect.height) * 2 - 1));

            if (frame) cancelAnimationFrame(frame);
            frame = requestAnimationFrame(function () {
                root.classList.add('is-pointer-active');
                root.style.setProperty('--pointer-x', ((x + 1) * 50).toFixed(2) + '%');
                root.style.setProperty('--pointer-y', ((y + 1) * 50).toFixed(2) + '%');
                root.style.setProperty('--pointer-shift-x', x.toFixed(3));
                root.style.setProperty('--pointer-shift-y', y.toFixed(3));
                root.style.setProperty('--tilt-x', (x * 4).toFixed(2) + 'deg');
                root.style.setProperty('--tilt-y', (y * -4).toFixed(2) + 'deg');
                frame = null;
            });
        });
        root.addEventListener('pointerleave', function () {
            if (frame) cancelAnimationFrame(frame);
            frame = null;
            root.classList.remove('is-pointer-active');
            root.style.removeProperty('--pointer-x');
            root.style.removeProperty('--pointer-y');
            root.style.removeProperty('--pointer-shift-x');
            root.style.removeProperty('--pointer-shift-y');
            root.style.removeProperty('--tilt-x');
            root.style.removeProperty('--tilt-y');
        });
    }

    // Scroll mode: the slider stays pinned while the page scrolls, and each slide turns in 3D.
    function initScroll3d(wrap) {
        var n = slides.length;
        var cur = -1;
        wrap.classList.add('is-3d');
        function span() { return Math.max(1, wrap.offsetHeight - window.innerHeight); }
        function update() {
            var top = wrap.getBoundingClientRect().top;
            var p = Math.min(1, Math.max(0, -top / span()));
            var pos = p * (n - 1);
            slides.forEach(function (s, i) {
                var d = i - pos;
                s.style.setProperty('--d', d.toFixed(3));
                s.style.setProperty('--ad', Math.min(1, Math.abs(d)).toFixed(3));
            });
            var idx = Math.round(pos);
            if (idx !== cur) {
                cur = idx;
                slides.forEach(function (s, i) {
                    s.classList.toggle('is-current', i === idx);
                    s.setAttribute('aria-hidden', i === idx ? 'false' : 'true');
                });
                dots.forEach(function (d, i) { d.classList.toggle('is-active', i === idx); });
            }
        }
        var ticking = false;
        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () { ticking = false; update(); });
        }
        function goTo(i) {
            var top = wrap.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({ top: top + span() * (i / (n - 1)), behavior: 'smooth' });
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', update);
        dots.forEach(function (d) { d.addEventListener('click', function () { goTo(parseInt(d.getAttribute('data-dot'), 10)); }); });
        if (prevBtn) prevBtn.addEventListener('click', function () { goTo(Math.max(0, cur - 1)); });
        if (nextBtn) nextBtn.addEventListener('click', function () { goTo(Math.min(n - 1, cur + 1)); });
        update();
    }

    initPointerEffects();
    var wrap = root.closest('[data-scroll3d]');
    if (wrap && slides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        initScroll3d(wrap);
        return;
    }
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
