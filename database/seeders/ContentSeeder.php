<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

/**
 * Two general, non-client educational articles so the journal is not empty
 * on first launch. They contain no claims about projects, prices or customers.
 */
class ContentSeeder extends Seeder
{
    public function run()
    {
        Article::firstOrCreate(['slug' => 'website-backup-checklist'], [
            'title_fa' => 'چک‌لیست بکاپ‌گیری از وب‌سایت',
            'title_en' => 'A practical website backup checklist',
            'excerpt_fa' => 'بکاپ فقط زمانی ارزش دارد که بتوان آن را بازیابی کرد. این فهرست کوتاه را پیش از هر به‌روزرسانی مرور کنید.',
            'excerpt_en' => 'A backup is only useful if you can restore it. Review this short list before every update.',
            'body_fa' => "## چرا بکاپ کافی نیست؟\n\nبکاپی که هرگز آزمایش بازیابی نشده، فقط یک فایل است. هدف این است که در بدترین حالت بتوانید سایت را در زمان قابل قبول برگردانید.\n\n## فهرست پیشنهادی\n\n- بکاپ از پایگاه داده و فایل‌های آپلودی به‌صورت جداگانه تهیه شود.\n- نسخه‌ها در مکانی غیر از سرور اصلی نگهداری شوند.\n- حداقل یک بار در ماه بازیابی آزمایشی انجام شود.\n- دسترسی به فایل‌های بکاپ محدود به افراد مجاز باشد.\n\n## نکته امنیتی\n\nفایل `.env` و کلیدهای دسترسی را هرگز داخل بکاپ عمومی یا مخازن کد قرار ندهید.",
            'body_en' => "## Why a backup alone is not enough\n\nA backup that has never been restored is only a file. The goal is to bring the site back within an acceptable time when something goes wrong.\n\n## Suggested checklist\n\n- Back up the database and uploaded files separately.\n- Store copies away from the production server.\n- Run a test restore at least once a month.\n- Limit access to backup files to authorised people.\n\n## Security note\n\nNever place `.env` files or access keys in public backups or code repositories.",
            'cover_url' => '/images/article-backup.jpg',
            'category' => 'امنیت و نگهداری / Security & maintenance',
            'tags' => ['backup', 'security'],
            'author_name' => 'Ideban Almas',
            'is_published' => true,
            'published_at' => now()->subDays(7),
        ]);

        Article::firstOrCreate(['slug' => 'choosing-a-website-platform'], [
            'title_fa' => 'انتخاب بستر مناسب برای وب‌سایت کسب‌وکار',
            'title_en' => 'Choosing the right platform for a business website',
            'excerpt_fa' => 'پیش از انتخاب قالب یا ابزار، نیاز، بودجه، توان نگهداری و مسیر رشد را مشخص کنید.',
            'excerpt_en' => 'Before choosing a theme or tool, define your needs, budget, maintenance capacity and growth path.',
            'body_fa' => "## سه پرسش کلیدی\n\n۱. سایت باید چه کاری انجام دهد: معرفی، فروش یا پشتیبانی؟\n۲. چه کسی بعداً محتوا و امکانات را به‌روز می‌کند؟\n۳. چه حجمی از داده و ترافیک را پیش‌بینی می‌کنید؟\n\n## مقایسه گزینه‌ها\n\nمعمولاً وب‌سایت معرفی ساده، فروشگاه آنلاین و نرم‌افزار سفارشی نیازهای متفاوتی دارند. انتخاب ابزار باید بر پایه همین نیازها باشد، نه صرفاً محبوبیت آن.",
            'body_en' => "## Three key questions\n\n1. What should the site do: inform, sell or support?\n2. Who will update content and features later?\n3. What volume of data and traffic do you expect?\n\n## Comparing options\n\nA simple brochure site, an online shop and custom software have different requirements. Pick the tool based on those requirements rather than popularity alone.",
            'cover_url' => '/images/article-platform.jpg',
            'category' => 'راهنما / Guides',
            'tags' => ['website', 'planning'],
            'author_name' => 'Ideban Almas',
            'is_published' => true,
            'published_at' => now()->subDays(3),
        ]);
    }
}
