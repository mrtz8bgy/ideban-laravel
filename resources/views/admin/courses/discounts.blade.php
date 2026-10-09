@extends('layouts.admin')
@section('title', tr('کدهای تخفیف', 'Discount codes'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('آکادمی', 'Academy') }}</span><h1>{{ tr('کدهای تخفیف', 'Discount codes') }}</h1></div></div>
<div class="table-wrap"><table><thead><tr><th>{{ tr('کد', 'Code') }}</th><th>{{ tr('نوع', 'Type') }}</th><th>{{ tr('مقدار', 'Value') }}</th><th>{{ tr('دوره', 'Course') }}</th><th>{{ tr('استفاده', 'Used') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead><tbody>
@forelse ($codes as $c)<tr><td dir="ltr">{{ $c->code }}</td><td>{{ $c->type === 'percent' ? '%' : tr('تومان', 'Toman') }}</td><td>{{ number_format($c->value) }}</td><td>{{ optional($c->course)->{'title_'.app()->getLocale()} ?? tr('همه دوره‌ها', 'All courses') }}</td><td>{{ $c->used_count }} / {{ $c->max_uses ?? '∞' }}</td><td>{{ $c->is_active ? tr('فعال', 'Active') : tr('غیرفعال', 'Inactive') }}</td>
<td class="table-actions"><form method="post" action="{{ route('admin.discounts.toggle', $c) }}">@csrf @method('PATCH')<button type="submit">{{ $c->is_active ? tr('غیرفعال', 'Disable') : tr('فعال', 'Enable') }}</button></form>
<form method="post" action="{{ route('admin.discounts.destroy', $c) }}">@csrf @method('DELETE')<button class="link-danger" type="submit">{{ tr('حذف', 'Delete') }}</button></form></td></tr>@empty<tr><td colspan="7">{{ tr('کدی ثبت نشده است.', 'No codes.') }}</td></tr>@endforelse
</tbody></table></div>
<h2>{{ tr('کد جدید', 'New code') }}</h2>
<form class="form-card admin-form" method="post" action="{{ route('admin.discounts.store') }}">
    @csrf
    <div class="form-row">
        <div class="form-field"><label>{{ tr('کد (انگلیسی)', 'Code (Latin)') }}</label><input name="code" required dir="ltr"></div>
        <div class="form-field"><label>{{ tr('نوع', 'Type') }}</label><select name="type"><option value="percent">{{ tr('درصد', 'Percent') }}</option><option value="amount">{{ tr('مبلغ ثابت (تومان)', 'Fixed amount (Toman)') }}</option></select></div>
        <div class="form-field"><label>{{ tr('مقدار', 'Value') }}</label><input type="number" min="1" name="value" required></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>{{ tr('دوره (اختیاری)', 'Course (optional)') }}</label><select name="course_id"><option value="">{{ tr('همه دوره‌ها', 'All courses') }}</option>@foreach ($courses as $c)<option value="{{ $c->id }}">{{ $c->{'title_'.app()->getLocale()} }}</option>@endforeach</select></div>
        <div class="form-field"><label>{{ tr('تاریخ انقضا', 'Expires at') }}</label><input type="date" name="expires_at"></div>
        <div class="form-field"><label>{{ tr('سقف استفاده', 'Max uses') }}</label><input type="number" min="1" name="max_uses"></div>
    </div>
    <button class="button button-small" type="submit">{{ tr('ساخت کد', 'Create code') }}</button>
</form>
@endsection
