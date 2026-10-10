@php
    $menuTree = \App\Models\MenuItem::tree('header');
    $menuLocale = app()->getLocale();
@endphp
{{-- Pure CSS navigation: hover/focus opens mega panels on desktop; the checkbox opens the drawer on mobile. --}}
<input type="checkbox" id="nav-toggle" class="nav-toggle-input" aria-hidden="true" tabindex="-1">
<label for="nav-toggle" class="nav-burger" role="button" tabindex="0" aria-controls="main-nav" aria-label="{{ tr('باز کردن منو', 'Open menu') }}">
    <span></span><span></span><span></span>
</label>
<nav class="main-nav" id="main-nav" aria-label="{{ tr('منوی اصلی', 'Main navigation') }}">
    <ul class="menu-root">
        @foreach ($menuTree as $item)
            <li class="menu-item{{ $item->children->isNotEmpty() ? ' has-mega' : '' }}">
                <a href="{{ $item->url }}"@if ($item->children->isNotEmpty()) aria-haspopup="true" @endif>{{ $item->labelFor($menuLocale) }}</a>
                @if ($item->children->isNotEmpty())
                    <div class="mega" role="region" aria-label="{{ $item->labelFor($menuLocale) }}">
                        <div class="container mega-inner">
                            <div class="mega-grid">
                                @foreach ($item->children as $column)
                                    <div class="mega-col">
                                        <a class="mega-title" href="{{ $column->url }}">{{ $column->labelFor($menuLocale) }}</a>
                                        @if ($column->children->isNotEmpty())
                                            <ul class="mega-links">
                                                @foreach ($column->children as $link)
                                                    <li><a href="{{ $link->url }}">{{ $link->labelFor($menuLocale) }}</a></li>
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
