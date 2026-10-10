<?php

namespace App\Support;

/**
 * Resolves stored links (menus, slider buttons) against the app's current base URL,
 * so they keep working when the site folder is renamed or moved.
 *
 * - "/services/x" becomes url("/services/x") (base-aware, e.g. http://host/folder/public/services/x)
 * - "/services#net" and "/pricing?x=1#plans" keep their query and fragment
 * - "#anchor", "https://..." and "//host/..." are returned unchanged
 */
class AppLink
{
    public static function resolve(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '' || $url[0] !== '/' || str_starts_with($url, '//')) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $query = parse_url($url, PHP_URL_QUERY);
        $fragment = parse_url($url, PHP_URL_FRAGMENT);

        $href = url($path);
        if ($query) {
            $href .= '?'.$query;
        }
        if ($fragment !== null && $fragment !== false && $fragment !== '') {
            $href .= '#'.$fragment;
        }

        return $href;
    }
}
