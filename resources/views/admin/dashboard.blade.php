@extends('layouts.admin')
@section('title', __('site.dashboard'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.company') }}</span><h1>{{ __('site.dashboard') }}</h1></div></div>
<div class="stats-grid">
    <a href="{{ route('admin.leads.index') }}" class="stat-card"><span>{{ __('site.leads') }}</span><strong>{{ $counts['leads'] }}</strong></a>
    <a href="{{ route('admin.leads.index') }}" class="stat-card"><span>{{ __('site.new_leads') }}</span><strong>{{ $counts['new_leads'] }}</strong></a>
    <a href="{{ route('admin.catalog.index', 'services') }}" class="stat-card"><span>{{ __('site.total_services') }}</span><strong>{{ $counts['services'] }}</strong></a>
    <a href="{{ route('admin.catalog.index', 'plans') }}" class="stat-card"><span>{{ __('site.total_plans') }}</span><strong>{{ $counts['plans'] }}</strong></a>
    <a href="{{ route('admin.catalog.index', 'portfolio') }}" class="stat-card"><span>{{ __('site.published_projects') }}</span><strong>{{ $counts['portfolio'] }}</strong></a>
</div>
<div class="admin-heading"><h2>{{ __('site.leads') }}</h2><a class="button button-small" href="{{ route('admin.leads.index') }}">{{ __('site.manage') }}</a></div>
<div class="table-wrap"><table><thead><tr><th>{{ __('site.name') }}</th><th>{{ __('site.phone') }}</th><th>{{ __('site.service') }}</th><th>{{ __('site.status') }}</th><th>{{ __('site.follow_up') }}</th></tr></thead><tbody>
@forelse ($leads as $lead)<tr><td>{{ $lead->name }}</td><td dir="ltr">{{ $lead->phone }}</td><td>{{ optional($lead->service)->{'title_'.app()->getLocale()} ?: '—' }}{{ $lead->plan ? ' / '.$lead->plan->{'name_'.app()->getLocale()} : '' }}</td><td>{{ __('stages.'.$lead->stage) }}</td><td>{{ optional($lead->follow_up_at)->format('Y-m-d H:i') ?: '—' }}</td></tr>@empty<tr><td colspan="5">{{ __('site.no_items') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
