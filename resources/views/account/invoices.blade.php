@extends('layouts.account')
@section('title', tr('فاکتورها', 'Invoices'))
@section('account')
<h1 class="gold-text">{{ tr('فاکتورها و پرداخت', 'Invoices & payments') }}</h1>
<div class="table-wrap"><table><thead><tr><th>{{ tr('شماره', 'Number') }}</th><th>{{ tr('مبلغ', 'Total') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead><tbody>
@forelse ($invoices as $i)<tr><td>{{ $i->number }}</td><td>{{ number_format($i->total) }} {{ tr('تومان', 'Toman') }}</td><td>{{ $i->status }}</td><td><a href="{{ route('account.invoices.show', $i) }}">{{ tr('مشاهده', 'View') }}</a></td></tr>@empty<tr><td colspan="4">{{ tr('فاکتوری وجود ندارد.', 'No invoices.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $invoices->links() }}
@endsection
