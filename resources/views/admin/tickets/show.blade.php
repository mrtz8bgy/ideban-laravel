@extends('layouts.admin')
@section('title', $ticket->reference)
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ $ticket->reference }}</span><h1>{{ $ticket->subject }}</h1></div><a class="text-link" href="{{ route('admin.tickets.index') }}">← {{ tr('بازگشت', 'Back') }}</a></div>
<p>{{ tr('مشتری', 'Customer') }}: {{ optional($ticket->user)->name }} · <span dir="ltr">{{ optional($ticket->user)->phone }}</span> · {{ optional($ticket->user)->email }}</p>
<div class="lead-list">
@foreach ($ticket->messages as $m)
    <article class="lead-card"><div class="lead-header"><strong>{{ $m->is_staff ? tr('کارشناس', 'Staff') : tr('مشتری', 'Customer') }} — {{ optional($m->user)->name }}</strong><span>{{ $m->created_at->format('Y-m-d H:i') }}</span></div>
    <p>{{ $m->body }}</p>
    @if ($m->attachment_path)<p><a href="{{ route('admin.tickets.attachment', $m) }}">📎 {{ $m->attachment_name }}</a></p>@endif</article>
@endforeach
</div>
<form class="form-card admin-form" method="post" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data">
    @csrf
    <div class="form-field"><label for="body">{{ tr('پاسخ', 'Reply') }}</label><textarea id="body" name="body" rows="4" required></textarea></div>
    <div class="form-field"><label for="attachment">{{ tr('پیوست', 'Attachment') }}</label><input id="attachment" type="file" name="attachment"></div>
    <div class="form-actions"><button class="button button-small" type="submit">{{ tr('ارسال پاسخ', 'Send reply') }}</button></div>
    </form>
<form class="form-card admin-form" method="post" action="{{ route('admin.tickets.update', $ticket) }}">
    @csrf @method('PATCH')
    <div class="form-row">
        <div class="form-field"><label>{{ tr('وضعیت', 'Status') }}</label><select name="status">@foreach ($statuses as $s)<option value="{{ $s }}" {{ $ticket->status === $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select></div>
        <div class="form-field"><label>{{ tr('اولویت', 'Priority') }}</label><select name="priority">@foreach ($priorities as $p)<option value="{{ $p }}" {{ $ticket->priority === $p ? 'selected' : '' }}>{{ $p }}</option>@endforeach</select></div>
        <div class="form-field"><label>{{ tr('ارجاع به', 'Assign to') }}</label><select name="assigned_to"><option value="">—</option>@foreach ($staff as $u)<option value="{{ $u->id }}" {{ (int) $ticket->assigned_to === $u->id ? 'selected' : '' }}>{{ $u->name }}</option>@endforeach</select></div>
    </div>
    <div class="form-actions"><button class="button button-small" type="submit">{{ tr('ذخیره', 'Save') }}</button></div>
    </form>
@endsection
