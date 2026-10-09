<?php

if (!function_exists('tr')) {
    /** Returns the Persian or English string for the current locale. */
    function tr(string $fa, string $en): string
    {
        return app()->getLocale() === 'fa' ? $fa : $en;
    }
}
