<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Clearly labelled demonstration records. Portfolio entries carry "Demo" in client_name
 * and show a "Sample" badge on the site. Replace them with real projects only after
 * you have permission to publish them.
 */
class DemoContentSeeder extends Seeder
{
    public function run()
    {
        $portfolios = [
            [
                'slug' => 'sample-online-store',
                'title_fa' => 'نمونه: فروشگاه آنلاین با پرداخت امن',
                'title_en' => 'Sample: online store with secure checkout',
                'client_name' => 'Demo (نمونه نمایشی)',
                'challenge_fa' => 'فروشگاه قدیمی بدون مدیریت موجودی و با سرعت پایین بارگذاری.',
                'challenge_en' => 'A legacy store with no stock management and slow page loads.',
                'solution_fa' => 'بازطراحی رابط کاربری، ساختار محصول و بهینه‌سازی تصاویر و کش.',
                'solution_en' => 'Redesigned UI, product structure, image optimisation and caching.',
                'result_fa' => 'این نتیجه نمایشی است؛ نتایج واقعی را پس از تأیید مشتری وارد کنید.',
                'result_en' => 'Illustrative outcome only; add real measured results after client approval.',
                'technologies' => ['WordPress', 'WooCommerce', 'Redis'],
                'image_url' => '/images/portfolio-1.jpg',
                'completed_at' => '2026-06-01',
                'is_published' => true,
            ],
            [
                'slug' => 'sample-docker-deployment',
                'title_fa' => 'نمونه: استقرار خودکار با Docker و CI/CD',
                'title_en' => 'Sample: automated Docker deployment with CI/CD',
                'client_name' => 'Demo (نمونه نمایشی)',
                'challenge_fa' => 'استقرار دستی و زمان‌بر نسخه‌های جدید نرم‌افزار.',
                'challenge_en' => 'Manual, time-consuming release process.',
                'solution_fa' => 'Docker Compose، پایپ‌لاین CI/CD و مانیتورینگ ساده سرور.',
                'solution_en' => 'Docker Compose, a CI/CD pipeline and simple server monitoring.',
                'result_fa' => 'نمونه آموزشی؛ مدت زمان استقرار را پس از اندازه‌گیری واقعی ثبت کنید.',
                'result_en' => 'Educational sample; record real deployment times after measuring.',
                'technologies' => ['Docker', 'GitLab CI', 'Nginx', 'Linux'],
                'image_url' => '/images/portfolio-2.jpg',
                'completed_at' => '2026-07-15',
                'is_published' => true,
            ],
            [
                'slug' => 'sample-security-hardening',
                'title_fa' => 'نمونه: امن‌سازی سرور و بکاپ خودکار',
                'title_en' => 'Sample: server hardening and automated backups',
                'client_name' => 'Demo (نمونه نمایشی)',
                'challenge_fa' => 'دسترسی‌های باز، نبود بکاپ منظم و نبود بازیابی آزموده‌شده.',
                'challenge_en' => 'Open access rules, no regular backups and no tested restore.',
                'solution_fa' => 'سخت‌سازی SSH، فایروال، به‌روزرسانی خودکار و بکاپ روزانه با تست بازیابی.',
                'solution_en' => 'SSH hardening, firewall rules, automatic updates and daily backups with restore tests.',
                'result_fa' => 'نمونه نمایشی؛ نتیجه را پس از ممیزی واقعی وارد کنید.',
                'result_en' => 'Demo sample; enter real audit results here.',
                'technologies' => ['Ubuntu', 'UFW', 'Fail2ban', 'Restic'],
                'image_url' => '/images/portfolio-3.jpg',
                'completed_at' => '2026-08-10',
                'is_published' => true,
            ],
        ];

        foreach ($portfolios as $row) {
            Portfolio::updateOrCreate(['slug' => $row['slug']], $row);
        }

        $service = Service::where('slug', 'business-website')->first();
        $leads = [
            ['name' => 'نمونه مشتری ۱ (Demo)', 'company' => 'Demo Co.', 'phone' => '09120000001', 'stage' => 'new', 'message' => 'داده نمونه برای نمایش پنل فروش.'],
            ['name' => 'نمونه مشتری ۲ (Demo)', 'company' => 'Demo Retail', 'phone' => '09120000002', 'stage' => 'proposal', 'expected_value' => 45000000, 'message' => 'داده نمونه برای نمایش پیگیری.'],
        ];
        foreach ($leads as $row) {
            Lead::firstOrCreate(['phone' => $row['phone']], $row + [
                'source' => 'sample',
                'service_id' => $service ? $service->id : null,
            ]);
        }
    }
}
