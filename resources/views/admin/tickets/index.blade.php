@extends('layouts.admin')
@section('title', tr('تیکت‌ها', 'Tickets'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('پشتیبانی', 'Support') }}</span><h1>{{ tr('تیکت‌های پشتیبانی', 'Support tickets') }}</h1></div></div>
<form class="filter-bar" method="get"><select name="status"><option value="">{{ tr('همه', 'All') }}</option>@foreach ($statuses as $s)<option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select><button class="button button-small" type="submit">{{ tr('فیلتر', 'Filter') }}</button></form>
<div class="table-wrap"><table><thead><tr><th>{{ tr('شماره', 'Ref') }}</th><th>{{ tr('موضوع', 'Subject') }}</th><th>{{ tr('مشتری', 'Customer') }}</th><th>{{ tr('اولویت', 'Priority') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead><tbody>
@forelse ($tickets as $t)<tr><td>{{ $t->reference }}</td><td>{{ $t->subject }}</td><td>{{ optional($t->user)->name }}</td><td>{{ $t->priority }}</td><td>{{ $t->status }}</td><td><a href="{{ route('admin.tickets.show', $t) }}">{{ tr('باز کردن', 'Open') }}</a></td></tr>@empty<tr><td colspan="6">{{ tr('تیکتی وجود ندارد.', 'No tickets.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $tickets->links() }}
@endsection
