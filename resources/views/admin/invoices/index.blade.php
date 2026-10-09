@extends('layouts.admin')
@section('title', tr('فاکتورها', 'Invoices'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('مالی', 'Finance') }}</span><h1>{{ tr('فاکتورها', 'Invoices') }}</h1></div></div>
<div class="table-wrap"><table><thead><tr><th>{{ tr('شماره', 'Number') }}</th><th>{{ tr('مشتری', 'Customer') }}</th><th>{{ tr('نوع', 'Type') }}</th><th>{{ tr('مبلغ', 'Total') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead><tbody>
@forelse ($invoices as $i)<tr><td>{{ $i->number }}</td><td>{{ optional($i->user)->name }}</td><td>{{ $i->course_id ? tr('دوره', 'Course') : tr('سفارش', 'Order') }}</td><td>{{ number_format($i->total) }}</td><td>{{ $i->status }}</td><td class="table-actions"><a href="{{ route('admin.invoices.show', $i) }}">{{ tr('جزئیات', 'Details') }}</a></td></tr>@empty<tr><td colspan="6">{{ tr('فاکتوری وجود ندارد.', 'No invoices.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $invoices->links() }}
@endsection
