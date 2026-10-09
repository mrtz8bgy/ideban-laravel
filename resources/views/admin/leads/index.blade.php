@extends('layouts.admin')
@section('title', __('site.leads'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.manage') }}</span><h1>{{ __('site.leads') }}</h1></div></div>
<div class="lead-list">
@forelse ($leads as $lead)
    <article class="lead-card">
        <div class="lead-header"><div><h2>{{ $lead->name }} @if ($lead->company)<small>· {{ $lead->company }}</small>@endif</h2><p><a href="tel:{{ $lead->phone }}" dir="ltr">{{ $lead->phone }}</a> @if ($lead->email)· <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>@endif</p></div><span>{{ $lead->created_at->format('Y-m-d H:i') }}</span></div>
        <p><strong>{{ __('site.service') }}:</strong> {{ optional($lead->service)->{'title_'.app()->getLocale()} ?: '—' }} @if ($lead->plan)· <strong>{{ app()->getLocale() === 'fa' ? 'پلن' : 'Plan' }}:</strong> {{ $lead->plan->{'name_'.app()->getLocale()} }}@endif · <strong>{{ __('site.message') }}:</strong> {{ $lead->message ?: '—' }}</p>
        <form class="lead-update" method="post" action="{{ route('admin.leads.update', $lead) }}">
            @csrf @method('PATCH')
            <label>{{ __('site.status') }}<select name="stage">@foreach ($stages as $stage)<option value="{{ $stage }}" {{ $lead->stage === $stage ? 'selected' : '' }}>{{ __('stages.'.$stage) }}</option>@endforeach</select></label>
            <label>{{ __('site.assigned_to') }}<input type="number" min="1" name="assigned_to" value="{{ old('assigned_to', $lead->assigned_to) }}"></label>
            <label>{{ __('site.follow_up') }}<input type="datetime-local" name="follow_up_at" value="{{ old('follow_up_at', optional($lead->follow_up_at)->format('Y-m-d\TH:i')) }}"></label>
            <label>{{ __('site.expected_value') }}<input type="number" min="0" name="expected_value" value="{{ old('expected_value', $lead->expected_value) }}"></label>
            <label class="note-field">{{ __('site.sales_note') }}<textarea name="sales_note" rows="2">{{ old('sales_note', $lead->sales_note) }}</textarea></label>
            <button class="button button-small" type="submit">{{ __('site.save') }}</button>
        </form>
    </article>
@empty<div class="empty-state">{{ __('site.no_items') }}</div>@endforelse
</div>
{{ $leads->links() }}
@endsection
