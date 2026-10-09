@extends('layouts.account')
@section('title', tr('تیکت جدید', 'New ticket'))
@section('account')
<h1 class="gold-text">{{ tr('تیکت جدید', 'New ticket') }}</h1>
<form class="form-card" method="post" action="{{ route('account.tickets.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-field"><label for="subject">{{ tr('موضوع', 'Subject') }}</label><input id="subject" name="subject" required value="{{ old('subject') }}"></div>
    <div class="form-field"><label for="category">{{ tr('دسته', 'Category') }}</label><select id="category" name="category">
        @foreach (['general' => tr('عمومی', 'General'), 'technical' => tr('فنی', 'Technical'), 'billing' => tr('مالی', 'Billing'), 'project' => tr('پروژه', 'Project'), 'hosting' => tr('میزبانی', 'Hosting'), 'security' => tr('امنیت', 'Security')] as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-field"><label for="priority">{{ tr('اولویت', 'Priority') }}</label><select id="priority" name="priority">
        @foreach (['low' => tr('کم', 'Low'), 'normal' => tr('معمولی', 'Normal'), 'high' => tr('بالا', 'High'), 'urgent' => tr('فوری', 'Urgent')] as $k => $v)<option value="{{ $k }}" {{ $k === 'normal' ? 'selected' : '' }}>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-field"><label for="body">{{ tr('شرح مشکل', 'Describe the issue') }}</label><textarea id="body" name="body" rows="6" required>{{ old('body') }}</textarea></div>
    <div class="form-field"><label for="attachment">{{ tr('پیوست (اختیاری، حداکثر ۱۰ مگابایت)', 'Attachment (optional, max 10 MB)') }}</label><input id="attachment" type="file" name="attachment"></div>
    <button class="button" type="submit">{{ tr('ثبت تیکت', 'Submit ticket') }}</button>
</form>
@endsection
