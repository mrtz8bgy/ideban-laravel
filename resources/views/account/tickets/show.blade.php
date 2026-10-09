@extends('layouts.account')
@section('title', $ticket->reference)
@section('account')
<h1 class="gold-text">{{ $ticket->subject }}</h1>
<p class="form-hint">{{ $ticket->reference }} · {{ $ticket->status }} · {{ tr('دسته', 'Category') }}: {{ $ticket->category }} · {{ tr('اولویت', 'Priority') }}: {{ $ticket->priority }}</p>
<div class="lead-list">
@foreach ($ticket->messages as $m)
    <article class="lead-card {{ $m->is_staff ? 'staff-msg' : '' }}">
        <div class="lead-header"><strong>{{ $m->is_staff ? tr('پشتیبانی ایده‌بان', 'Ideban support') : tr('شما', 'You') }}</strong><span>{{ $m->created_at->format('Y-m-d H:i') }}</span></div>
        <p>{{ $m->body }}</p>
        @if ($m->attachment_path)<p><a href="{{ route('account.tickets.attachment', $m) }}">📎 {{ $m->attachment_name }}</a></p>@endif
    </article>
@endforeach
</div>
@if ($ticket->status !== 'closed')
<form class="form-card" method="post" action="{{ route('account.tickets.reply', $ticket) }}" enctype="multipart/form-data">
    @csrf
    <div class="form-field"><label for="body">{{ tr('پاسخ', 'Reply') }}</label><textarea id="body" name="body" rows="4" required></textarea></div>
    <div class="form-field"><label for="attachment">{{ tr('پیوست', 'Attachment') }}</label><input id="attachment" type="file" name="attachment"></div>
    <button class="button button-small" type="submit">{{ tr('ارسال', 'Send') }}</button>
</form>
@else
<p class="notice">{{ tr('این تیکت بسته شده است. برای ادامه گفتگو، تیکت جدیدی ثبت کنید.', 'This ticket is closed. Create a new ticket to continue the conversation.') }}</p>
@endif
@endsection
