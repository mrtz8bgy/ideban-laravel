@extends('layouts.admin')
@section('title', tr('پرداخت‌ها', 'Payments'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('مالی', 'Finance') }}</span><h1>{{ tr('پرداخت‌ها و رسیدها', 'Payments and receipts') }}</h1></div></div>
<form class="filter-bar" method="get"><select name="status">@foreach (['pending' => tr('در انتظار بررسی', 'Pending'), 'paid' => tr('تأییدشده', 'Paid'), 'failed' => tr('ناموفق / ردشده', 'Failed / rejected')] as $k => $v)<option value="{{ $k }}" {{ $status === $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select><div class="form-actions"><button class="button button-small" type="submit">{{ tr('فیلتر', 'Filter') }}</button></div>
</form>
<div class="table-wrap"><table><thead><tr><th>{{ tr('فاکتور', 'Invoice') }}</th><th>{{ tr('مشتری', 'Customer') }}</th><th>{{ tr('مبلغ', 'Amount') }}</th><th>{{ tr('روش', 'Method') }}</th><th>{{ tr('توضیح', 'Notes') }}</th><th></th></tr></thead><tbody>
@forelse ($payments as $p)
<tr>
    <td><a href="{{ route('admin.invoices.show', $p->invoice) }}">{{ optional($p->invoice)->number }}</a></td>
    <td>{{ optional(optional($p->invoice)->user)->name }}</td>
    <td>{{ number_format($p->amount) }}</td>
    <td>{{ $p->method === 'online' ? tr('آنلاین', 'Online') : tr('کارت به کارت', 'Bank transfer') }}</td>
    <td>{{ $p->notes }}</td>
    <td class="table-actions">
        @if ($p->receipt_path)<a href="{{ route('admin.payments.receipt', $p) }}">{{ tr('رسید', 'Receipt') }}</a>@endif
        @if ($p->status === 'pending' && $p->method === 'bank_transfer')
            <form method="post" action="{{ route('admin.payments.approve', $p) }}">@csrf<div class="form-actions"><button class="button button-small" type="submit">{{ tr('تأیید', 'Approve') }}</button></div>
</form>
            <form method="post" action="{{ route('admin.payments.reject', $p) }}">@csrf<div class="form-actions"><button class="link-danger" type="submit">{{ tr('رد', 'Reject') }}</button></div>
</form>
        @endif
    </td>
</tr>
@empty<tr><td colspan="6">{{ tr('موردی وجود ندارد.', 'Nothing here.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $payments->links() }}
@endsection
