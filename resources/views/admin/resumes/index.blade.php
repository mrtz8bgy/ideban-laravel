@extends('layouts.admin')
@section('title', tr('رزومه‌ها', 'Resumes'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('مدیریت', 'Manage') }}</span><h1>{{ tr('رزومه‌های کارمندان', 'Staff resumes') }}</h1></div><a class="button button-small" href="{{ route('admin.resumes.create') }}">{{ tr('افزودن رزومه', 'Add resume') }}</a></div>
<div class="table-wrap"><table>
    <thead><tr><th>{{ tr('نام', 'Name') }}</th><th>{{ tr('سمت', 'Title') }}</th><th>{{ tr('موارد', 'Entries') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead>
    <tbody>
    @forelse ($resumes as $r)
        <tr>
            <td>{{ $r->{'name_'.app()->getLocale()} }}@if ($r->is_sample) <span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>@endif</td>
            <td>{{ $r->{'job_title_'.app()->getLocale()} }}</td>
            <td>{{ $r->items_count }}</td>
            <td>{{ $r->is_published ? tr('منتشر شده', 'Published') : tr('پیش‌نویس', 'Draft') }}</td>
            <td class="table-actions">
                <a href="{{ route('admin.resumes.edit', $r) }}">{{ tr('ویرایش', 'Edit') }}</a>
                @if ($r->is_published)<a href="{{ route('team.show', $r) }}" target="_blank" rel="noopener">{{ tr('مشاهده', 'View') }}</a>@endif
                <form method="post" action="{{ route('admin.resumes.destroy', $r) }}" onsubmit="return confirm('{{ tr('حذف شود؟', 'Delete?') }}')">@csrf @method('DELETE')<button class="link-danger" type="submit">{{ tr('حذف', 'Delete') }}</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">{{ tr('هنوز رزومه‌ای ثبت نشده است.', 'No resumes yet.') }}</td></tr>
    @endforelse
    </tbody>
</table></div>
@endsection
