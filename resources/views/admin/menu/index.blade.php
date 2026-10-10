@extends('layouts.admin')
@section('title', tr('منوی سایت', 'Site menu'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('مدیریت', 'Manage') }}</span><h1>{{ tr('منوی سایت (مگا منو)', 'Site menu (mega menu)') }}</h1><p>{{ tr('حداکثر سه سطح: منوی اصلی ← ستون ← لینک. زیرمنوها در مگا منو نمایش داده می‌شوند.', 'Up to three levels: top item → column → link. Children appear in the mega menu.') }}</p></div><a class="button button-small" href="{{ route('admin.menu.create') }}">{{ tr('افزودن منو', 'Add item') }}</a></div>
@if (session('success'))<p class="flash">{{ session('success') }}</p>@endif
<div class="table-wrap"><table>
    <thead><tr><th>{{ tr('عنوان (فارسی)', 'Label (FA)') }}</th><th>{{ tr('عنوان (انگلیسی)', 'Label (EN)') }}</th><th>{{ tr('آدرس', 'URL') }}</th><th>{{ tr('ترتیب', 'Order') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead>
    <tbody>
    @forelse ($items as $item)
        <tr>
            <td style="padding-inline-start:{{ 1 + $item->level * 1.5 }}rem">{{ $item->level > 0 ? '↳ ' : '' }}{{ $item->label_fa }}</td>
            <td dir="ltr">{{ $item->label_en }}</td>
            <td dir="ltr">{{ $item->url }}</td>
            <td>{{ $item->sort_order }}</td>
            <td>{{ $item->is_active ? tr('فعال', 'Active') : tr('غیرفعال', 'Hidden') }}</td>
            <td class="table-actions">
                <a href="{{ route('admin.menu.edit', $item) }}">{{ tr('ویرایش', 'Edit') }}</a>
                <form method="post" action="{{ route('admin.menu.destroy', $item) }}" onsubmit="return confirm('{{ tr('حذف شود؟ زیرمنوها هم حذف می‌شوند.', 'Delete? Children will be deleted too.') }}')">@csrf @method('DELETE')<button class="link-danger" type="submit">{{ tr('حذف', 'Delete') }}</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6">{{ tr('هنوز منویی ثبت نشده است.', 'No menu items yet.') }}</td></tr>
    @endforelse
    </tbody>
</table></div>
@endsection
