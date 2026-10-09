<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Attaches the bundled sample images to categories, plans and portfolio items.
 * These are generic illustrations, not client work. Portfolio items keep their "Demo" label.
 */
class MediaSampleSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'web-software' => 'images/samples/category-web-software.jpg',
            'devops-infrastructure' => 'images/samples/category-devops-infrastructure.jpg',
            'it-support' => 'images/samples/category-it-support.jpg',
            'network-security' => 'images/samples/category-network-security.jpg',
        ];
        foreach ($categories as $slug => $path) {
            ServiceCategory::where('slug', $slug)->update(['media_path' => $path]);
        }

        $plans = [
            'base' => 'images/samples/plan-basic.jpg',
            'professional' => 'images/samples/plan-professional.jpg',
            'enterprise' => 'images/samples/plan-enterprise.jpg',
        ];
        foreach ($plans as $slug => $path) {
            PricingPlan::where('slug', $slug)->update(['media_path' => $path]);
        }

        $portfolios = [
            'sample-online-store' => 'images/samples/portfolio-sample-store.jpg',
            'sample-docker-deployment' => 'images/samples/portfolio-sample-cloud.jpg',
            'sample-security-hardening' => 'images/samples/portfolio-sample-security.jpg',
        ];
        foreach ($portfolios as $slug => $path) {
            Portfolio::where('slug', $slug)->update(['media_path' => $path]);
        }
    }
}
