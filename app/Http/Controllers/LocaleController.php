<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request, $locale)
    {
        abort_unless(in_array($locale, ['fa', 'en'], true), 404);
        $request->session()->put('locale', $locale);

        $refererPath = parse_url($request->headers->get('referer', ''), PHP_URL_PATH);
        $refererQuery = parse_url($request->headers->get('referer', ''), PHP_URL_QUERY);
        $target = is_string($refererPath) && substr($refererPath, 0, 1) === '/'
            ? $refererPath.($refererQuery ? '?'.$refererQuery : '')
            : route('home');

        if (strpos($target, 'http://') !== 0 && strpos($target, 'https://') !== 0) {
            $target = $request->getSchemeAndHttpHost().$target;
        }

        return redirect()->to($target);
    }
}
