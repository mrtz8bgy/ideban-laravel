<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Resume;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Default header menu (mega menu). Services are listed from the catalogue:
 * Services -> category -> service. Admins can edit everything under Admin > Menu.
 */
class MenuSeeder extends Seeder
{
    public function run()
    {
        MenuItem::where('location', 'header')->delete();

        $make = function (array $row, ?MenuItem $parent = null, int $order = 0): MenuItem {
            return MenuItem::create([
                'location' => 'header',
                'parent_id' => $parent ? $parent->id : null,
                'label_fa' => $row['label_fa'],
                'label_en' => $row['label_en'],
                'url' => $row['url'],
                'sort_order' => $order,
                'is_active' => true,
            ]);
        };

        $make(['label_fa' => 'صفحه اصلی', 'label_en' => 'Home', 'url' => '/'], null, 10);

        $services = $make(['label_fa' => 'خدمات', 'label_en' => 'Services', 'url' => '/services'], null, 20);
        foreach (ServiceCategory::where('is_active', true)->orderBy('sort_order')->get() as $i => $category) {
            $column = $make([
                'label_fa' => $category->name_fa, 'label_en' => $category->name_en,
                'url' => '/services#'.$category->slug,
            ], $services, ($i + 1) * 10);
            $list = Service::where('category_id', $category->id)->where('is_active', true)->orderBy('id')->get();
            foreach ($list as $j => $service) {
                $make([
                    'label_fa' => $service->title_fa, 'label_en' => $service->title_en,
                    'url' => '/services/'.$service->slug,
                ], $column, ($j + 1) * 10);
            }
        }

        $pricing = $make(['label_fa' => 'تعرفه‌ها', 'label_en' => 'Pricing', 'url' => '/pricing'], null, 30);
        $make(['label_fa' => 'بسته‌های قیمتی', 'label_en' => 'Pricing plans', 'url' => '/pricing#plans'], $pricing, 10);
        $make(['label_fa' => 'ماشین‌حساب برآورد', 'label_en' => 'Estimate calculator', 'url' => '/calculator'], $pricing, 20);
        $make(['label_fa' => 'درخواست پیش‌فاکتور', 'label_en' => 'Request a quote', 'url' => '/contact'], $pricing, 30);

        $make(['label_fa' => 'نمونه‌کارها', 'label_en' => 'Portfolio', 'url' => '/portfolio'], null, 40);

        $journal = $make(['label_fa' => 'مجله و ویدیو', 'label_en' => 'Journal & video', 'url' => '/blog'], null, 50);
        $make(['label_fa' => 'مقالات', 'label_en' => 'Articles', 'url' => '/blog'], $journal, 10);
        $make(['label_fa' => 'ویدیوها', 'label_en' => 'Videos', 'url' => '/videos'], $journal, 20);

        $make(['label_fa' => 'آکادمی', 'label_en' => 'Academy', 'url' => '/academy'], null, 60);
        $team = $make(['label_fa' => 'تیم و رزومه‌ها', 'label_en' => 'Team & resumes', 'url' => '/team'], null, 70);
        $make(['label_fa' => 'همه رزومه‌ها', 'label_en' => 'All resumes', 'url' => '/team'], $team, 5);
        $resumeOrder = 10;
        foreach (Resume::published()->orderBy('sort_order')->orderBy('id')->get() as $resume) {
            $column = $make([
                'label_fa' => $resume->name_fa.($resume->is_sample ? ' (نمونه)' : ''),
                'label_en' => $resume->name_en.($resume->is_sample ? ' (Sample)' : ''),
                'url' => '/team/'.$resume->slug,
            ], $team, $resumeOrder);
            $resumeOrder += 10;
            $sections = [
                ['experience', 'سوابق کاری', 'Work experience'],
                ['education', 'تحصیلات', 'Education'],
                ['skill', 'مهارت‌ها', 'Skills'],
                ['certificate', 'گواهینامه‌ها', 'Certificates'],
            ];
            foreach ($sections as $k => [$type, $fa, $en]) {
                if ($resume->itemsOf($type)->isEmpty()) {
                    continue;
                }
                $make(['label_fa' => $fa, 'label_en' => $en, 'url' => '/team/'.$resume->slug.'#'.$type], $column, ($k + 1) * 10);
            }
        }
        $make(['label_fa' => 'تماس', 'label_en' => 'Contact', 'url' => '/contact'], null, 80);
    }
}
