@extends('layouts.admin')
@section('title', tr('مشتریان', 'Customers'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('مشتریان', 'Customers') }}</span><h1>{{ tr('فهرست مشتریان', 'Customers') }}</h1></div></div>
<form class="filter-bar" method="get"><input name="q" value="{{ $q }}" placeholder="{{ tr('نام، ایمیل، تلفن، شرکت', 'Name, email, phone, company') }}"><div class="form-actions"><button class="button button-small" type="submit">{{ tr('جستجو', 'Search') }}</button></div>
</form>
<div class="table-wrap"><table><thead><tr><th>{{ tr('نام', 'Name') }}</th><th>{{ tr('شرکت', 'Company') }}</th><th>{{ tr('تلفن', 'Phone') }}</th><th>{{ tr('ایمیل', 'Email') }}</th><th></th></tr></thead><tbody>
@forelse ($customers as $c)<tr><td>{{ $c->name }}</td><td>{{ $c->company }}</td><td dir="ltr">{{ $c->phone }}</td><td dir="ltr">{{ $c->email }}</td><td class="table-actions"><a href="{{ route('admin.customers.show', $c) }}">{{ tr('پرونده', 'Profile') }}</a></td></tr>@empty<tr><td colspan="5">{{ tr('مشتری‌ای ثبت نشده است.', 'No customers yet.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $customers->links() }}
@endsection
