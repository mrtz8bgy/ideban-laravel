<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class IdebanCatalogSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['slug' => 'web-software', 'name_fa' => 'طراحی سایت و نرم‌افزار', 'name_en' => 'Web & software', 'sort_order' => 1],
            ['slug' => 'devops-infrastructure', 'name_fa' => 'DevOps و زیرساخت', 'name_en' => 'DevOps & infrastructure', 'sort_order' => 2],
            ['slug' => 'it-support', 'name_fa' => 'پشتیبانی IT', 'name_en' => 'IT support', 'sort_order' => 3],
            ['slug' => 'network-security', 'name_fa' => 'شبکه و امنیت', 'name_en' => 'Network & security', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ServiceCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }

        $services = [
            [
                'slug' => 'business-website',
                'title_fa' => 'طراحی سایت شرکتی',
                'title_en' => 'Business website design',
                'category' => 'web-software',
                'summary_fa' => 'طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.',
                'summary_en' => 'A business website designed around your goals and brand.',
                'is_featured' => true,
            ],
            [
                'slug' => 'custom-laravel',
                'title_fa' => 'توسعه نرم‌افزار اختصاصی Laravel',
                'title_en' => 'Custom Laravel development',
                'category' => 'web-software',
                'summary_fa' => 'توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.',
                'summary_en' => 'Custom applications and modules built with Laravel.',
                'is_featured' => true,
            ],
            [
                'slug' => 'deployment-devops',
                'title_fa' => 'استقرار و DevOps',
                'title_en' => 'Deployment & DevOps',
                'category' => 'devops-infrastructure',
                'summary_fa' => 'راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.',
                'summary_en' => 'Server setup, Docker, CI/CD, SSL, and backup workflows.',
                'is_featured' => true,
            ],
            [
                'slug' => 'it-help-desk',
                'title_fa' => 'پشتیبانی IT و Help Desk',
                'title_en' => 'IT support & help desk',
                'category' => 'it-support',
                'summary_fa' => 'پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.',
                'summary_en' => 'Ongoing support for users, systems, and IT infrastructure.',
                'is_featured' => false,
            ],
            [
                'slug' => 'network-security',
                'title_fa' => 'شبکه و امنیت فناوری اطلاعات',
                'title_en' => 'IT network & security',
                'category' => 'network-security',
                'summary_fa' => 'ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.',
                'summary_en' => 'Assessment and improvement of website, server, and network security.',
                'is_featured' => false,
            ],
        ];

        foreach ($services as $service) {
            $categorySlug = $service['category'];
            unset($service['category']);
            $service['category_id'] = ServiceCategory::where('slug', $categorySlug)->value('id');
            $service['description_fa'] = $service['summary_fa'];
            $service['description_en'] = $service['summary_en'];
            Service::firstOrCreate(['slug' => $service['slug']], $service);
        }

        foreach ([
            ['slug' => 'base', 'name_fa' => 'پایه', 'name_en' => 'Base', 'sort_order' => 1],
            ['slug' => 'professional', 'name_fa' => 'حرفه‌ای', 'name_en' => 'Professional', 'sort_order' => 2],
            ['slug' => 'enterprise', 'name_fa' => 'سازمانی', 'name_en' => 'Enterprise', 'sort_order' => 3],
        ] as $plan) {
            $plan['description_fa'] = 'محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.';
            $plan['description_en'] = 'Scope and cost are confirmed after discovery and an approved quotation.';
            $plan['features_fa'] = [];
            $plan['features_en'] = [];
            $plan['price_type'] = 'quote';
            $plan['is_featured'] = true;
            PricingPlan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
