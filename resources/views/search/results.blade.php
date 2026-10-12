@if ($q === '')
    <div class="empty-state">{{ tr('یک عبارت وارد کنید.', 'Enter a search term.') }}</div>
@elseif (mb_strlen($q) < 2)
    <div class="empty-state">{{ tr('حداقل ۲ حرف وارد کنید.', 'Enter at least 2 characters.') }}</div>
@elseif ($total === 0)
    <div class="empty-state">{{ str_replace(':q', $q, tr('نتیجه‌ای برای «:q» پیدا نشد.', 'No results for ":q".')) }}</div>
@else
    <p class="form-hint">{{ tr($total.' نتیجه برای «'.$q.'»', $total.' results for "'.$q.'"') }}</p>
    @foreach ($groups as $group)
        <section class="result-group">
            <h2>{{ $group['label'] }}</h2>
            <div class="result-list">
                @foreach ($group['items'] as $item)
                    <a href="{{ $item['url'] }}"><span>{{ $item['title'] }}</span><small>{{ $item['description'] }}</small></a>
                @endforeach
            </div>
        </section>
    @endforeach
@endif
