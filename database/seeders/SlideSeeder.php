<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

/**
 * Homepage slides for the company's main service areas. Admins can edit or replace them
 * from the admin panel (Homepage slider).
 */
class SlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title_fa' => 'شبکه پسیو و اکتیو، پایه ارتباطات سازمان شما',
                'title_en' => 'Passive & active networks, the base of your organisation',
                'subtitle_fa' => 'طراحی و پیاده‌سازی زیرساخت شبکه؛ از کابل‌کشی و سوئیچ تا Wi-Fi سازمانی و پایش.',
                'subtitle_en' => 'Design and deployment from cabling and switches to enterprise Wi-Fi and monitoring.',
                'button_text_fa' => 'خدمات شبکه', 'button_text_en' => 'Network services',
                'button_url' => '/services', 'image_path' => 'images/samples/category-passive-active-network.jpg',
            ],
            [
                'title_fa' => 'کابل‌کشی و فیبر نوری با تست و گزارش کامل',
                'title_en' => 'Cabling & fiber optics with full testing and reports',
                'subtitle_fa' => 'جوشکاری فیبر، تست OTDR و مستندسازی هر لینک برای اتصال ساختمان‌ها و طبقات.',
                'subtitle_en' => 'Fiber splicing, OTDR testing and documentation of every link between buildings and floors.',
                'button_text_fa' => 'کابل‌کشی و فیبر', 'button_text_en' => 'Cabling & fiber',
                'button_url' => '/services', 'image_path' => 'images/samples/category-cabling-fiber.jpg',
            ],
            [
                'title_fa' => 'دوربین مداربسته و کنترل تردد، امنیت ساختمان شما',
                'title_en' => 'CCTV and access control for your building',
                'subtitle_fa' => 'طراحی و نصب دوربین IP، ضبط‌کننده شبکه‌ای، کارت‌خوان و سیستم اعلام سرقت با دسترسی از موبایل.',
                'subtitle_en' => 'Design and installation of IP cameras, NVRs, card readers and alarms with mobile access.',
                'button_text_fa' => 'سیستم‌های حفاظتی', 'button_text_en' => 'Security systems',
                'button_url' => '/services', 'image_path' => 'images/samples/category-cctv-security.jpg',
            ],
        ];

        foreach ($slides as $i => $slide) {
            Slide::updateOrCreate(
                ['title_en' => $slide['title_en']],
                $slide + ['is_sample' => false, 'is_active' => true, 'sort_order' => $i + 1]
            );
        }
    }
}
