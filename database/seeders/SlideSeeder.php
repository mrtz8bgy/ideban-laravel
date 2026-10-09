<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

/**
 * Sample homepage slides. Images are generic illustrations shipped with the site, not client work.
 * Every slide is marked is_sample so the UI labels it; replace them from the admin panel.
 */
class SlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title_fa' => 'نمونه: زیرساخت فناوری کسب‌وکار شما',
                'title_en' => 'Sample: Technology infrastructure for your business',
                'subtitle_fa' => 'از طراحی و توسعه تا استقرار، امنیت و پشتیبانی',
                'subtitle_en' => 'From design and development to deployment, security and support',
                'button_text_fa' => 'مشاهده خدمات', 'button_text_en' => 'View services',
                'button_url' => '/services', 'image_path' => 'images/hero-gold.jpg',
            ],
            [
                'title_fa' => 'نمونه: برآورد هزینه در چند دقیقه',
                'title_en' => 'Sample: Estimate your costs in minutes',
                'subtitle_fa' => 'بسته و افزودنی‌های موردنیاز را انتخاب کنید؛ مبالغ رسمی و استعلام قیمت به‌روشنی مشخص می‌شوند.',
                'subtitle_en' => 'Choose a plan and add-ons; official amounts and price inquiries are clearly separated.',
                'button_text_fa' => 'ماشین‌حساب', 'button_text_en' => 'Calculator',
                'button_url' => '/calculator', 'image_path' => 'images/portfolio-1.jpg',
            ],
            [
                'title_fa' => 'نمونه: آکادمی و آموزش عملی',
                'title_en' => 'Sample: Academy and hands-on training',
                'subtitle_fa' => 'دوره‌های کوتاه درباره وب، سرور و امنیت. دوره‌های نمونه با برچسب مشخص شده‌اند.',
                'subtitle_en' => 'Short courses on web, servers and security. Sample courses are labelled.',
                'button_text_fa' => 'ورود به آکادمی', 'button_text_en' => 'Open the academy',
                'button_url' => '/academy', 'image_path' => 'images/portfolio-2.jpg',
            ],
        ];

        foreach ($slides as $i => $slide) {
            Slide::updateOrCreate(
                ['title_en' => $slide['title_en']],
                $slide + ['is_sample' => true, 'is_active' => true, 'sort_order' => $i + 1]
            );
        }
    }
}
