@extends('layouts.admin')
@section('title', tr('دوره‌ها', 'Courses'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('آکادمی', 'Academy') }}</span><h1>{{ tr('دوره‌ها', 'Courses') }}</h1></div><div class="button-row"><a class="button button-small" href="{{ route('admin.courses.create') }}">＋ {{ tr('دوره جدید', 'New course') }}</a><a class="button button-small button-ghost" href="{{ route('admin.discounts.index') }}">{{ tr('کدهای تخفیف', 'Discount codes') }}</a></div></div>
<div class="table-wrap"><table><thead><tr><th>{{ tr('عنوان', 'Title') }}</th><th>{{ tr('دسته', 'Category') }}</th><th>{{ tr('قیمت', 'Price') }}</th><th>{{ tr('درس‌ها', 'Lessons') }}</th><th>{{ tr('منتشر شده', 'Published') }}</th><th></th></tr></thead><tbody>
@forelse ($courses as $c)<tr><td>{{ $c->{'title_'.app()->getLocale()} }} @if (str_contains($c->slug, 'sample'))<span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>@endif</td><td>{{ $c->category }}</td><td>{{ $c->is_free ? tr('رایگان', 'Free') : number_format($c->price) }}</td><td>{{ $c->lessons_count }}</td><td>{{ $c->is_published ? tr('بله', 'Yes') : tr('خیر', 'No') }}</td>
<td class="table-actions"><a href="{{ route('admin.courses.lessons', $c) }}">{{ tr('درس‌ها و ویدیو', 'Lessons & video') }}</a><a href="{{ route('admin.courses.edit', $c) }}">{{ tr('ویرایش', 'Edit') }}</a></td></tr>@empty<tr><td colspan="6">{{ tr('دوره‌ای ثبت نشده است.', 'No courses.') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
