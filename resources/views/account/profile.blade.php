@extends('layouts.account')
@section('title', tr('اطلاعات حساب', 'Profile'))
@section('account')
<h1 class="gold-text">{{ tr('اطلاعات حساب', 'Profile') }}</h1>
<form class="form-card" method="post" action="{{ route('account.profile.update') }}">
    @csrf @method('PUT')
    <div class="form-field"><label for="name">{{ tr('نام', 'Name') }}</label><input id="name" name="name" required value="{{ old('name', $user->name) }}"></div>
    <div class="form-field"><label for="email">{{ tr('ایمیل', 'Email') }}</label><input id="email" type="email" name="email" required dir="ltr" value="{{ old('email', $user->email) }}"></div>
    <div class="form-field"><label for="phone">{{ tr('تلفن', 'Phone') }}</label><input id="phone" name="phone" required dir="ltr" value="{{ old('phone', $user->phone) }}"></div>
    <div class="form-field"><label for="company">{{ tr('شرکت', 'Company') }}</label><input id="company" name="company" value="{{ old('company', $user->company) }}"></div>
    <p class="form-hint">{{ tr('نام کاربری', 'Username') }}: <span dir="ltr">{{ $user->username }}</span></p>
    <button class="button" type="submit">{{ tr('ذخیره', 'Save') }}</button>
</form>
@endsection
