<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('site.company')) | {{ __('site.brand') }}</title>
    <meta name="description" content="@yield('meta_description', __('site.tagline'))">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#101d3b">
    <link rel="stylesheet" href="{{ asset('css/ideban.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark">I</span><span>{{ __('site.brand') }}</span></a>
        <nav class="main-nav" aria-label="{{ app()->getLocale() === 'fa' ? 'منوی اصلی' : 'Main navigation' }}">
            <a href="{{ route('home') }}">{{ __('site.home') }}</a>
            <a href="{{ route('services.index') }}">{{ __('site.services') }}</a>
            <a href="{{ route('pricing.index') }}">{{ __('site.pricing') }}</a>
            <a href="{{ route('portfolio.index') }}">{{ __('site.portfolio') }}</a>
        </nav>
        <div class="nav-actions">
            <a class="locale-link" href="{{ route('locale.update', app()->getLocale() === 'fa' ? 'en' : 'fa') }}">{{ app()->getLocale() === 'fa' ? 'EN' : 'فا' }}</a>
            <a class="button button-small" href="{{ route('contact') }}">{{ __('site.contact') }}</a>
        </div>
    </div>
</header>
<main>
    @if (session('success'))
        <div class="container"><div class="notice notice-success">{{ session('success') }}</div></div>
    @endif
    @yield('content')
</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div><a class="brand brand-light" href="{{ route('home') }}"><span class="brand-mark">I</span><span>{{ __('site.company') }}</span></a><p>{{ __('site.footer_text') }}</p></div>
        <div><strong>{{ __('site.services') }}</strong><a href="{{ route('services.index') }}">{{ __('site.all_services') }}</a><a href="{{ route('pricing.index') }}">{{ __('site.pricing') }}</a></div>
        <div><strong>{{ __('site.contact') }}</strong><a href="tel:09104927131" dir="ltr">09104927131</a><a href="{{ route('contact') }}">{{ __('site.request_quote') }}</a></div>
    </div>
    <div class="container footer-bottom"><span>© {{ date('Y') }} {{ __('site.company') }} — {{ __('site.rights') }}</span><a href="{{ route('login') }}">{{ __('site.login') }}</a></div>
</footer>
</body>
</html>
