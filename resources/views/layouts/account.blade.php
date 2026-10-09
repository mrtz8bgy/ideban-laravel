@extends('layouts.app')
@section('content')
<section class="admin-main"><div class="container admin-layout">
    <aside class="admin-sidebar">
        <span class="eyebrow">{{ tr('پنل مشتری', 'Customer panel') }}</span>
        <a href="{{ route('account.dashboard') }}">{{ tr('داشبورد', 'Dashboard') }}</a>
        <a href="{{ route('account.orders.index') }}">{{ tr('سفارش‌ها', 'Orders') }}</a>
        <a href="{{ route('account.invoices.index') }}">{{ tr('فاکتورها و پرداخت', 'Invoices & payments') }}</a>
        <a href="{{ route('account.courses') }}">{{ tr('دوره‌های من', 'My courses') }}</a>
        <a href="{{ route('account.tickets.index') }}">{{ tr('تیکت‌های پشتیبانی', 'Support tickets') }}</a>
        <a href="{{ route('account.profile') }}">{{ tr('اطلاعات حساب', 'Profile') }}</a>
        <form method="post" action="{{ route('logout') }}">@csrf<button class="button button-small button-ghost" type="submit">{{ tr('خروج', 'Log out') }}</button></form>
    </aside>
    <section class="admin-content">
        @if ($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
        @yield('account')
    </section>
</div></section>
@endsection
