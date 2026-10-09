/* Adds the rotating gold border to cards. Falls back to a JS-driven angle where @property is unsupported. */
(function () {
    var selector = '.service-card, .plan-card, .work-card, .content-card, .stat-card, .lead-card, .card';
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var nodes = document.querySelectorAll(selector);
    nodes.forEach(function (el) { el.classList.add('gold-frame'); });
    if (reduce || (window.CSS && CSS.registerProperty)) return;

    var angle = 0;
    function tick() {
        angle = (angle + 0.9) % 360;
        nodes.forEach(function (el) { el.style.setProperty('--gold-angle', angle + 'deg'); });
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();
