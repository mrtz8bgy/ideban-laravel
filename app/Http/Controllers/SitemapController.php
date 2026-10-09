<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Service;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $urls = collect([
            route('home'),
            route('services.index'),
            route('pricing.index'),
            route('portfolio.index'),
            route('contact'),
        ])->merge(
            Service::where('is_active', true)->get()->map(function ($service) {
                return route('services.show', $service->slug);
            })
        )->merge(
            Portfolio::where('is_published', true)->get()->map(function ($portfolio) {
                return route('portfolio.show', $portfolio->slug);
            })
        );

        $entries = $urls->map(function ($url) {
            return '<url><loc>'.e($url).'</loc></url>';
        })->implode('');

        return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$entries.'</urlset>', 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function robots()
    {
        $contents = "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".route('sitemap')."\n";

        return response($contents, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
