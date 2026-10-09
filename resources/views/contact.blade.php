@extends('layouts.app')
@section('title', __('site.contact'))
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ __('site.contact') }}</h1><p>{{ __('site.contact_intro') }}</p></div></section>
<section class="section"><div class="container contact-layout">
    <div class="contact-copy"><span class="eyebrow">{{ app()->getLocale() === 'fa' ? 'بیایید گفتگو کنیم' : 'Let’s talk' }}</span><h2>{{ app()->getLocale() === 'fa' ? 'هر پروژه از یک گفت‌وگوی خوب شروع می‌شود.' : 'Every successful project starts with a conversation.' }}</h2><p>{{ __('site.hero_text') }}</p><p class="form-hint">{{ __('site.onsite_notice') }}</p><a class="contact-phone" href="tel:09104927131" dir="ltr">09104927131</a></div>
    <form class="form-card" method="post" action="{{ route('contact.store') }}">
        @csrf
        <div class="form-field"><label for="name">{{ __('site.name') }}</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="150" autocomplete="name">@error('name')<small class="field-error">{{ $message }}</small>@enderror</div>
        <div class="form-row">
            <div class="form-field"><label for="phone">{{ __('site.phone') }}</label><input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel" dir="ltr">@error('phone')<small class="field-error">{{ $message }}</small>@enderror</div>
            <div class="form-field"><label for="company">{{ __('site.company_name') }}</label><input id="company" name="company" value="{{ old('company') }}" maxlength="150" autocomplete="organization"></div>
        </div>
        <div class="form-field"><label for="email">{{ __('site.email') }}</label><input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="190" autocomplete="email">@error('email')<small class="field-error">{{ $message }}</small>@enderror</div>
        <div class="form-field"><label for="service_id">{{ __('site.service') }}</label><select id="service_id" name="service_id"><option value="">{{ __('site.select_service') }}</option>@foreach ($services as $service)<option value="{{ $service->id }}" {{ old('service_id', request('service')) == $service->id ? 'selected' : '' }}>{{ $service->{'title_'.app()->getLocale()} }}</option>@endforeach</select>@error('service_id')<small class="field-error">{{ $message }}</small>@enderror</div>
        @if ($selectedPlan)<input type="hidden" name="plan_id" value="{{ $selectedPlan->id }}"><div class="form-hint">{{ app()->getLocale() === 'fa' ? 'پلن انتخاب‌شده:' : 'Selected plan:' }} {{ $selectedPlan->{'name_'.app()->getLocale()} }}</div>@endif
        <div class="form-field"><label for="message">{{ __('site.message') }}</label><textarea id="message" name="message" rows="5" maxlength="5000">{{ old('message') }}</textarea>@error('message')<small class="field-error">{{ $message }}</small>@enderror</div>
        <div class="honeypot" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
        <button class="button button-full" type="submit">{{ __('site.send_request') }} <span aria-hidden="true">↗</span></button>
    </form>
</div></section>
@endsection
