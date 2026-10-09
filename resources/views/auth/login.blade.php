@extends('layouts.app')
@section('title', __('site.login_title'))
@section('content')
<section class="section auth-section"><div class="form-card auth-card"><span class="eyebrow">{{ __('site.admin_panel') }}</span><h1>{{ __('site.login_title') }}</h1>
    @if ($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
    <form method="post" action="{{ route('login.store') }}">
        @csrf
        <div class="form-field"><label for="email">{{ __('site.email_address') }}</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus></div>
        <div class="form-field"><label for="password">{{ __('site.password') }}</label><input id="password" type="password" name="password" required autocomplete="current-password"></div>
        <label class="check-field"><input type="checkbox" name="remember" value="1"> {{ __('site.remember_me') }}</label>
        <button class="button button-full" type="submit">{{ __('site.login') }}</button>
    </form>
</div></section>
@endsection
