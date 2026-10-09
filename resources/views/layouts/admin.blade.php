<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>@yield('title', __('site.admin_panel')) | {{ __('site.brand') }}</title><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Vazirmatn:wght@400;600;800&display=swap"><link rel="stylesheet" href="{{ asset('css/ideban.css') }}"></head>
<body class="admin-body">
<header class="site-header"><div class="container nav-wrap"><a class="brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark">I</span><span>{{ __('site.admin_panel') }}</span></a><nav class="main-nav"><a href="{{ route('home') }}">{{ __('site.home') }}</a><a href="{{ route('admin.dashboard') }}">{{ __('site.dashboard') }}</a></nav><div class="nav-actions"><a class="locale-link" href="{{ route('locale.update', app()->getLocale() === 'fa' ? 'en' : 'fa') }}">{{ app()->getLocale() === 'fa' ? 'EN' : 'فا' }}</a><form method="post" action="{{ route('logout') }}">@csrf<button class="button button-small button-ghost" type="submit">{{ __('site.logout') }}</button></form></div></div></header>
<main class="admin-main"><div class="container admin-layout">
    <aside class="admin-sidebar"><span class="eyebrow">{{ __('site.manage') }}</span><a href="{{ route('admin.dashboard') }}">{{ __('site.dashboard') }}</a>@foreach (['categories' => __('site.categories'), 'services' => __('site.services'), 'plans' => __('site.plans'), 'prices' => app()->getLocale() === 'fa' ? 'تعرفه‌ها و منابع' : 'Rates and sources', 'portfolio' => __('site.portfolio')] as $type => $label)<a href="{{ route('admin.catalog.index', $type) }}">{{ $label }}</a>@endforeach<a href="{{ route('admin.content.index', 'articles') }}">{{ __('content.admin_articles') }}</a><a href="{{ route('admin.content.index', 'videos') }}">{{ __('content.admin_videos') }}</a><a href="{{ route('admin.leads.index') }}">{{ __('site.leads') }}</a></aside>
    <section class="admin-content">
        @if (session('success'))<div class="notice notice-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
        @yield('admin-content')
    </section>
</div></main>
</body></html>
