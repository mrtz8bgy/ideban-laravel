@extends('layouts.account')
@section('title', tr('تیکت‌ها', 'Tickets'))
@section('account')
<div class="admin-heading"><h1 class="gold-text">{{ tr('تیکت‌های پشتیبانی', 'Support tickets') }}</h1><a class="button button-small" href="{{ route('account.tickets.create') }}">{{ tr('تیکت جدید', 'New ticket') }}</a></div>
<div class="table-wrap"><table><thead><tr><th>{{ tr('شماره', 'Ref') }}</th><th>{{ tr('موضوع', 'Subject') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th>{{ tr('آخرین بروزرسانی', 'Last update') }}</th><th></th></tr></thead><tbody>
@forelse ($tickets as $t)<tr><td>{{ $t->reference }}</td><td>{{ $t->subject }}</td><td>{{ $t->status }} @if ($t->unread_count)<span class="ticket-unread">{{ tr($t->unread_count.' پاسخ جدید', $t->unread_count.' new reply/replies') }}</span>@endif</td><td>{{ optional($t->last_reply_at)->format('Y-m-d H:i') }}</td><td><a href="{{ route('account.tickets.show', $t) }}">{{ tr('مشاهده گفتگو', 'Open conversation') }}</a></td></tr>@empty<tr><td colspan="5">{{ tr('تیکتی ثبت نشده است.', 'No tickets yet.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $tickets->links() }}
@endsection
