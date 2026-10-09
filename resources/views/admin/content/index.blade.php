@extends('layouts.admin')
@section('title', __('content.admin_content').' — '.$type)
@section('admin-content')
<div class="admin-heading"><div><span class="eyebrow">{{ __('site.manage') }}</span><h1>{{ $type === 'articles' ? __('content.admin_articles') : __('content.admin_videos') }}</h1></div><a class="button button-small" href="{{ route('admin.content.create', $type) }}">＋ {{ __('site.add_new') }}</a></div>
<div class="table-wrap"><table><thead><tr>
    <th>{{ app()->getLocale() === 'fa' ? 'عنوان' : 'Title' }}</th>
    <th>{{ app()->getLocale() === 'fa' ? 'دسته' : 'Category' }}</th>
    <th>{{ app()->getLocale() === 'fa' ? 'منتشر شده' : 'Published' }}</th>
    <th>{{ app()->getLocale() === 'fa' ? 'تاریخ انتشار' : 'Publish date' }}</th>
    <th></th>
</tr></thead><tbody>
@forelse ($items as $item)
    <tr>
        <td>{{ $item->title_fa }}<br><small style="color:var(--muted)" dir="ltr">{{ $item->title_en }}</small></td>
        <td>{{ $item->category ?: '—' }}</td>
        <td>{{ $item->is_published ? (app()->getLocale() === 'fa' ? 'بله' : 'Yes') : (app()->getLocale() === 'fa' ? 'خیر' : 'No') }}</td>
        <td>{{ optional($item->published_at)->format('Y-m-d H:i') ?: '—' }}</td>
        <td class="table-actions">
            <a href="{{ route('admin.content.edit', [$type, $item->id]) }}">{{ __('site.edit') }}</a>
            <form method="post" action="{{ route('admin.content.destroy', [$type, $item->id]) }}" onsubmit="return confirm('{{ __('site.delete') }}؟')">@csrf @method('DELETE')<button class="link-danger" type="submit">{{ __('site.delete') }}</button></form>
        </td>
    </tr>
@empty
    <tr><td colspan="5">{{ __('site.no_items') }}</td></tr>
@endforelse
</tbody></table></div>
<div class="pagination-wrap">{{ $items->links() }}</div>
@endsection
