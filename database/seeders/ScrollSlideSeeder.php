<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

/**
 * Three extra homepage slides for the 3D scroll slider. Images are original generated illustrations.
 */
class ScrollSlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title_fa' => 'مرکز داده و زیرساخت پایدار', 'title_en' => 'Data centre and resilient infrastructure',
                'subtitle_fa' => 'طراحی رَک، مسیرهای کابل‌کشی و تهویه برای اتاق سرور و مرکز داده سازمان.',
                'subtitle_en' => 'Rack, cable path and cooling design for server rooms and data centres.',
                'button_text_fa' => 'خدمات شبکه', 'button_text_en' => 'Network services',
                'button_url' => '/services/active-network-design', 'image_path' => 'images/samples/scroll-datacenter.jpg', 'sort_order' => 40,
            ],
            [
                'title_fa' => 'فیبر نوری، سرعت بدون وقفه', 'title_en' => 'Fiber optics, speed without interruption',
                'subtitle_fa' => 'جوشکاری و تست لینک‌های فیبر بین ساختمان‌ها و طبقات با گزارش مستند.',
                'subtitle_en' => 'Splicing and tested fiber links between buildings and floors, with documented reports.',
                'button_text_fa' => 'نصب فیبر نوری', 'button_text_en' => 'Fiber installation',
                'button_url' => '/services/fiber-optic-installation', 'image_path' => 'images/samples/scroll-fiber.jpg', 'sort_order' => 50,
            ],
            [
                'title_fa' => 'امنیت و نظارت هوشمند', 'title_en' => 'Smart security and surveillance',
                'subtitle_fa' => 'دوربین IP، ضبط‌کننده شبکه‌ای و مدیریت یکپارچه تردد برای ساختمان شما.',
                'subtitle_en' => 'IP cameras, network video recorders and unified access management for your building.',
                'button_text_fa' => 'دوربین مداربسته', 'button_text_en' => 'CCTV installation',
                'button_url' => '/services/cctv-installation', 'image_path' => 'images/samples/scroll-cctv.jpg', 'sort_order' => 60,
            ],
        ];

        foreach ($slides as $row) {
            Slide::updateOrCreate(
                ['image_path' => $row['image_path']],
                $row + ['subtitle_fa' => $row['subtitle_fa'], 'subtitle_en' => $row['subtitle_en'], 'is_active' => true, 'is_sample' => false]
            );
        }
    }
}
