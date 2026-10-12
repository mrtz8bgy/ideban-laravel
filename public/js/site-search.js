(() => {
    const form = document.querySelector('[data-search-form]');
    const results = document.getElementById('search-results');
    if (!form || !results) return;

    const input = form.querySelector('input[name="q"]');
    if (!input) return;

    let timeout;
    let request;

    const updateAddress = (term, push) => {
        const url = new URL(window.location.href);
        if (term) url.searchParams.set('q', term);
        else url.searchParams.delete('q');
        window.history[push ? 'pushState' : 'replaceState']({}, '', url);
    };

    const search = async (term, pushAddress = false, updateHistory = true) => {
        const query = term.trim();
        if (request) request.abort();

        if (updateHistory) updateAddress(query, pushAddress);

        const endpoint = new URL(form.dataset.searchEndpoint, window.location.href);
        if (query) endpoint.searchParams.set('q', query);
        else endpoint.searchParams.delete('q');

        const controller = new AbortController();
        request = controller;
        results.setAttribute('aria-busy', 'true');
        results.classList.add('is-searching');

        try {
            const response = await fetch(endpoint, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
                signal: controller.signal,
            });
            if (!response.ok) throw new Error(`Search request failed (${response.status}).`);

            const payload = await response.json();
            if (typeof payload.html !== 'string') throw new Error('The search response was invalid.');
            results.innerHTML = payload.html;
        } catch (error) {
            if (error.name === 'AbortError') return;
            results.replaceChildren();
            const message = document.createElement('div');
            message.className = 'empty-state';
            message.textContent = document.documentElement.lang === 'fa'
                ? 'در دریافت نتایج جستجو خطایی رخ داد. دوباره تلاش کنید.'
                : 'Search results could not be loaded. Please try again.';
            results.append(message);
            console.error(error);
        } finally {
            if (request === controller) {
                request = null;
                results.removeAttribute('aria-busy');
                results.classList.remove('is-searching');
            }
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        clearTimeout(timeout);
        search(input.value, true);
    });

    input.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = window.setTimeout(() => search(input.value), 250);
    });

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);
        input.value = url.searchParams.get('q') || '';
        search(input.value, false, false);
    });
})();
