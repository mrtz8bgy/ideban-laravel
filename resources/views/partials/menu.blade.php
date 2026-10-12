@php
    $menuTree = \App\Models\MenuItem::tree('header');
    $menuLocale = app()->getLocale();
@endphp
<button type="button" class="nav-burger" aria-controls="main-nav" aria-expanded="false" aria-label="{{ tr('باز کردن منو', 'Open menu') }}">
    <span></span><span></span><span></span>
</button>
<nav class="main-nav" id="main-nav" aria-label="{{ tr('منوی اصلی', 'Main navigation') }}">
    <ul class="menu-root">
        @foreach ($menuTree as $item)
            <li class="menu-item{{ $item->children->isNotEmpty() ? ' has-mega' : '' }}">
                <div class="menu-item-heading">
                    <a href="{{ \App\Support\AppLink::resolve($item->url) }}">{{ $item->labelFor($menuLocale) }}</a>
                    @if ($item->children->isNotEmpty())
                        <button type="button" class="mega-toggle" aria-controls="mega-panel-{{ $item->id }}" aria-expanded="false" aria-label="{{ tr('نمایش زیرمنوی ', 'Toggle submenu for ').$item->labelFor($menuLocale) }}">
                            <span aria-hidden="true">⌄</span>
                        </button>
                    @endif
                </div>
                @if ($item->children->isNotEmpty())
                    <div class="mega" id="mega-panel-{{ $item->id }}" role="region" aria-label="{{ $item->labelFor($menuLocale) }}">
                        <div class="container mega-inner">
                            <div class="mega-grid">
                                @foreach ($item->children as $column)
                                    <div class="mega-col">
                                        <a class="mega-title" href="{{ \App\Support\AppLink::resolve($column->url) }}">{{ $column->labelFor($menuLocale) }}</a>
                                        @if ($column->children->isNotEmpty())
                                            <ul class="mega-links">
                                                @foreach ($column->children as $link)
                                                    <li><a href="{{ \App\Support\AppLink::resolve($link->url) }}">{{ $link->labelFor($menuLocale) }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
