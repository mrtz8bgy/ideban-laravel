<section class="section showcase-3d">
    <div class="container showcase-grid">
        <div class="showcase-copy">
            <span class="eyebrow">{{ tr('نمای سه‌بعدی', '3D showcase') }}</span>
            <h2>{{ tr('زیرساخت خود را در سه بعد ببینید', 'See your infrastructure in 3D') }}</h2>
            <p>{{ tr('از طراحی شبکه تا نصب فیبر و نظارت، هر لایه از زیرساخت با دقت طراحی و اجرا می‌شود.', 'From network design to fiber installation and monitoring, every layer is designed and built with care.') }}</p>
            <a class="button button-small" href="{{ route('services.index') }}">{{ __('site.services') }} ↗</a>
        </div>
        <div class="showcase-stage">
            <div class="showcase-card" data-tilt>
                <video class="showcase-video" muted loop playsinline autoplay preload="metadata" poster="{{ asset('images/samples/showcase-poster.jpg') }}" aria-label="{{ tr('ویدیوی نمایشی زیرساخت', 'Infrastructure showcase video') }}">
                    <source src="{{ asset('videos/ideban-showcase.mp4') }}" type="video/mp4">
                </video>
                <span class="sample-tag showcase-tag">{{ tr('نمونه تصویری', 'Illustrative sample') }}</span>
            </div>
        </div>
    </div>
</section>
<script>
(function () {
    var card = document.querySelector('[data-tilt]');
    if (!card) return;
    var video = card.querySelector('video');
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        video.removeAttribute('autoplay');
        video.pause();
        return;
    }
    var ticking = false;
    function tilt() {
        ticking = false;
        var r = card.getBoundingClientRect();
        var vh = window.innerHeight || 1;
        var p = Math.max(-1, Math.min(1, (r.top + r.height / 2 - vh / 2) / vh));
        card.style.setProperty('--rx', (-p * 9).toFixed(2) + 'deg');
        card.style.setProperty('--ry', (p * -16).toFixed(2) + 'deg');
    }
    window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(tilt); } }, { passive: true });
    window.addEventListener('resize', tilt);
    card.addEventListener('pointermove', function (e) {
        var r = card.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - 0.5, y = (e.clientY - r.top) / r.height - 0.5;
        card.style.setProperty('--px', (x * 10).toFixed(2) + 'deg');
        card.style.setProperty('--py', (-y * 10).toFixed(2) + 'deg');
    });
    card.addEventListener('pointerleave', function () { card.style.setProperty('--px', '0deg'); card.style.setProperty('--py', '0deg'); });
    tilt();
})();
</script>
