<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DiscountCode;
use App\Models\Lesson;
use App\Models\Service;
use App\Models\ServiceAddon;
use Illuminate\Database\Seeder;

/**
 * Sample commerce and academy data. Everything here is labelled as sample.
 * No add-on has a public amount (quote only), and no video is added: lessons start with source "none".
 */
class CommerceSeeder extends Seeder
{
    public function run()
    {
        $web = Service::where('slug', 'business-website')->first();
        $security = Service::where('slug', 'network-security')->first();

        if ($web) {
            foreach ([
                ['نمونه: ساخت فرم و صفحه فرود', 'Sample: landing page and forms', 'quote'],
                ['نمونه: اتصال درگاه پرداخت', 'Sample: payment gateway integration', 'quote'],
                ['نمونه: پشتیبانی ماهانه', 'Sample: monthly support', 'quote'],
            ] as $i => [$fa, $en, $type]) {
                ServiceAddon::firstOrCreate(
                    ['service_id' => $web->id, 'name_en' => $en],
                    ['name_fa' => $fa, 'price_type' => $type, 'amount' => null, 'is_active' => true, 'sort_order' => $i + 1]
                );
            }
        }

        if ($security) {
            ServiceAddon::firstOrCreate(
                ['service_id' => $security->id, 'name_en' => 'Sample: security audit'],
                ['name_fa' => 'نمونه: ممیزی امنیتی', 'price_type' => 'quote', 'amount' => null, 'is_active' => true, 'sort_order' => 1]
            );
        }

        $courses = [
            [
                'slug' => 'sample-website-launch-basics',
                'title_fa' => 'نمونه: مبانی راه‌اندازی وب‌سایت کسب‌وکار',
                'title_en' => 'Sample: Business website launch basics',
                'instructor_fa' => 'مربی نمونه', 'instructor_en' => 'Sample instructor',
                'summary_fa' => 'دوره نمونه برای آشنایی با دامنه، میزبانی و انتشار اولین وب‌سایت.',
                'summary_en' => 'Sample course on domains, hosting and publishing a first website.',
                'category' => 'شروع کسب‌وکار اینترنتی', 'level' => 'beginner', 'price' => 0, 'is_free' => true,
                'lessons' => ['معرفی دوره (نمونه)', 'انتخاب دامنه و میزبانی (نمونه)'],
                'lessons_en' => ['Course introduction (sample)', 'Choosing a domain and hosting (sample)'],
            ],
            [
                'slug' => 'sample-linux-server-security',
                'title_fa' => 'نمونه: امنیت پایه سرور لینوکس',
                'title_en' => 'Sample: Linux server security basics',
                'instructor_fa' => 'مربی نمونه', 'instructor_en' => 'Sample instructor',
                'summary_fa' => 'دوره نمونه درباره سخت‌سازی پایه سرور و به‌روزرسانی امن.',
                'summary_en' => 'Sample course on basic server hardening and safe updates.',
                'category' => 'سرور و Linux', 'level' => 'intermediate', 'price' => 1500000, 'is_free' => false,
                'lessons' => ['مقدمه امنیت سرور (نمونه)', 'کلیدهای SSH و فایروال (نمونه)'],
                'lessons_en' => ['Server security introduction (sample)', 'SSH keys and firewall (sample)'],
            ],
        ];

        foreach ($courses as $index => $data) {
            $lessons = $data['lessons'];
            $lessonsEn = $data['lessons_en'];
            unset($data['lessons'], $data['lessons_en']);

            $course = \App\Models\Course::updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['is_published' => true, 'sort_order' => $index + 1, 'duration_minutes' => 60]
            );

            foreach ($lessons as $i => $fa) {
                Lesson::firstOrCreate(
                    ['course_id' => $course->id, 'sort_order' => $i + 1],
                    [
                        'title_fa' => $fa, 'title_en' => $lessonsEn[$i], 'source' => 'none',
                        'is_free_preview' => $i === 0, 'is_published' => true,
                    ]
                );
            }
        }

        DiscountCode::firstOrCreate(
            ['code' => 'SAMPLE10'],
            ['type' => 'percent', 'value' => 10, 'is_active' => true, 'max_uses' => null]
        );
    }
}
