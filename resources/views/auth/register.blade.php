@extends('layouts.app')
@section('title', tr('ثبت‌نام مشتری', 'Customer sign-up'))
@section('content')
<section class="section auth-section"><div class="form-card auth-card"><span class="eyebrow">{{ tr('پنل مشتری', 'Customer panel') }}</span><h1 class="gold-text">{{ tr('ثبت‌نام', 'Sign up') }}</h1>
    @if ($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
    <form method="post" action="{{ route('register.store') }}">
        @csrf
        <div class="form-field"><label for="name">{{ tr('نام و نام خانوادگی', 'Full name') }}</label><input id="name" name="name" required value="{{ old('name') }}"></div>
        <div class="form-field"><label for="username">{{ tr('نام کاربری (انگلیسی)', 'Username (Latin letters)') }}</label><input id="username" name="username" required dir="ltr" value="{{ old('username') }}"></div>
        <div class="form-field"><label for="email">{{ tr('ایمیل', 'Email') }}</label><input id="email" type="email" name="email" required dir="ltr" value="{{ old('email') }}"></div>
        <div class="form-field"><label for="phone">{{ tr('تلفن همراه', 'Mobile phone') }}</label><input id="phone" name="phone" required dir="ltr" value="{{ old('phone') }}"></div>
        <div class="form-field"><label for="company">{{ tr('نام شرکت (اختیاری)', 'Company (optional)') }}</label><input id="company" name="company" value="{{ old('company') }}"></div>
        <div class="form-field"><label for="password">{{ tr('رمز عبور (حداقل ۱۰ کاراکتر)', 'Password (min. 10 characters)') }}</label><input id="password" type="password" name="password" required autocomplete="new-password"></div>
        <div class="form-field"><label for="password_confirmation">{{ tr('تکرار رمز عبور', 'Confirm password') }}</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
        <div class="honeypot" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
        <button class="button button-full" type="submit">{{ tr('ساخت حساب', 'Create account') }}</button>
    </form>
    <p class="form-hint">{{ tr('حساب دارید؟', 'Already have an account?') }} <a href="{{ route('login') }}">{{ tr('ورود', 'Log in') }}</a></p>
</div></section>
@endsection
