(() => {
    const nav = document.getElementById('main-nav');
    const burger = document.querySelector('.nav-burger');
    if (!nav || !burger) return;

    const closeMenus = () => {
        nav.querySelectorAll('.menu-item.is-open').forEach((item) => {
            item.classList.remove('is-open');
            const toggle = item.querySelector('.mega-toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
    };

    burger.addEventListener('click', () => {
        const isOpen = burger.getAttribute('aria-expanded') !== 'true';
        burger.setAttribute('aria-expanded', String(isOpen));
        nav.classList.toggle('is-open', isOpen);
        if (!isOpen) closeMenus();
    });

    nav.addEventListener('click', (event) => {
        const toggle = event.target.closest('.mega-toggle');
        if (toggle) {
            const item = toggle.closest('.menu-item');
            const isOpen = !item.classList.contains('is-open');
            closeMenus();
            item.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
            return;
        }

        if (event.target.closest('a') && window.matchMedia('(max-width: 1600px)').matches) {
            nav.classList.remove('is-open');
            burger.setAttribute('aria-expanded', 'false');
            closeMenus();
        }
    });

    document.addEventListener('click', (event) => {
        if (!nav.contains(event.target) && !burger.contains(event.target)) closeMenus();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        closeMenus();
        nav.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
        burger.focus();
    });
})();
