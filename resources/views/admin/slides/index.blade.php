@extends('layouts.admin')
@section('title', tr('اسلایدر صفحه اصلی', 'Homepage slider'))
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ tr('محتوا', 'Content') }}</span><h1>{{ tr('اسلایدر صفحه اصلی', 'Homepage slider') }}</h1></div><a class="button button-small" href="{{ route('admin.slides.create') }}">＋ {{ tr('اسلاید جدید', 'New slide') }}</a></div>
<p class="notice">{{ tr('اسلایدهای فعال به ترتیب نمایش داده می‌شوند. تصاویر نمونه با برچسب «نمونه» مشخص شده‌اند؛ پیش از انتشار، آن‌ها را با تصاویر واقعی جایگزین کنید.', 'Active slides appear in the order shown. Sample images are labelled "Sample"; replace them with real images before launch.') }}</p>
<div class="table-wrap"><table><thead><tr><th>{{ tr('تصویر', 'Image') }}</th><th>{{ tr('عنوان', 'Title') }}</th><th>{{ tr('ترتیب', 'Order') }}</th><th>{{ tr('وضعیت', 'Status') }}</th><th></th></tr></thead><tbody>
@forelse ($slides as $slide)
<tr>
    <td><img src="{{ $slide->imageUrl() }}" alt="" style="width:110px;height:64px;object-fit:cover;border-radius:6px"></td>
    <td>{{ $slide->{'title_'.app()->getLocale()} }} @if ($slide->is_sample)<span class="sample-tag">{{ tr('نمونه', 'Sample') }}</span>@endif</td>
    <td>{{ $slide->sort_order }}</td>
    <td>{{ $slide->is_active ? tr('فعال', 'Active') : tr('غیرفعال', 'Inactive') }}</td>
    <td class="table-actions"><a href="{{ route('admin.slides.edit', $slide) }}">{{ tr('ویرایش', 'Edit') }}</a>
        <form method="post" action="{{ route('admin.slides.destroy', $slide) }}" onsubmit="return confirm('{{ tr('حذف شود؟', 'Delete?') }}')">@csrf @method('DELETE')<div class="form-actions"><button class="link-danger" type="submit">{{ tr('حذف', 'Delete') }}</button></div>
</form></td>
</tr>
@empty<tr><td colspan="5">{{ tr('اسلایدی ثبت نشده است.', 'No slides yet.') }}</td></tr>@endforelse
</tbody></table></div>
@endsection
