<?php

namespace Database\Seeders;

use App\Models\Resume;
use Illuminate\Database\Seeder;

/**
 * Sample resumes. These people are FICTIONAL examples for layout and testing only.
 * Replace or delete them in the admin panel (Resumes) before publishing real staff profiles.
 */
class ResumeSampleSeeder extends Seeder
{
    public function run()
    {
        $samples = [
            [
                'slug' => 'sample-network-engineer',
                'name_fa' => 'نمونه: نیلوفر کاویانی', 'name_en' => 'Sample: Niloufar Kaviani',
                'job_title_fa' => 'مهندس شبکه و امنیت (نمونه)', 'job_title_en' => 'Network & security engineer (sample)',
                'bio_fa' => 'این رزومه نمونه است. مهندس شبکه با تجربه طراحی و پیاده‌سازی زیرساخت‌های سازمانی، مدیریت فایروال و پایش امنیت.',
                'bio_en' => 'This is a sample resume. Network engineer experienced in designing enterprise infrastructure, firewall management and security monitoring.',
                'location_fa' => 'تهران', 'location_en' => 'Tehran', 'email' => 'sample.network@example.com', 'phone' => null,
                'sort_order' => 1,
                'items' => [
                    ['experience', 'مهندس ارشد شبکه (نمونه)', 'Senior network engineer (sample)', 'شرکت نمونه A', 'Sample Company A', '2021 – 2026', 'طراحی شبکه VLAN و مدیریت فایروال.', 'Designed VLAN networks and managed firewalls.', null, null],
                    ['experience', 'کارشناس شبکه (نمونه)', 'Network specialist (sample)', 'شرکت نمونه B', 'Sample Company B', '2018 – 2021', 'پشتیبانی و نگهداری تجهیزات شبکه.', 'Supported and maintained network equipment.', null, null],
                    ['education', 'کارشناسی مهندسی فناوری اطلاعات (نمونه)', 'BSc in IT engineering (sample)', 'دانشگاه نمونه', 'Sample University', '2014 – 2018', null, null, null, null],
                    ['skill', 'پیکربندی فایروال', 'Firewall configuration', null, null, null, null, null, null, 90],
                    ['skill', 'مسیریابی و سوئیچینگ', 'Routing & switching', null, null, null, null, null, null, 85],
                    ['certificate', 'گواهی نمونه شبکه', 'Sample networking certificate', 'مرکز آموزشی نمونه', 'Sample Training Center', '2023', null, null, 'https://example.com/certificate-sample', null],
                ],
            ],
            [
                'slug' => 'sample-laravel-developer',
                'name_fa' => 'نمونه: آرش موسوی', 'name_en' => 'Sample: Arash Mousavi',
                'job_title_fa' => 'توسعه‌دهنده ارشد Laravel (نمونه)', 'job_title_en' => 'Senior Laravel developer (sample)',
                'bio_fa' => 'این رزومه نمونه است. توسعه‌دهنده وب با تمرکز بر Laravel، MySQL و طراحی رابط کاربری فارسی و انگلیسی.',
                'bio_en' => 'This is a sample resume. Web developer focused on Laravel, MySQL and bilingual (Persian and English) interfaces.',
                'location_fa' => 'اصفهان', 'location_en' => 'Isfahan', 'email' => 'sample.dev@example.com', 'phone' => null,
                'sort_order' => 2,
                'items' => [
                    ['experience', 'توسعه‌دهنده ارشد وب (نمونه)', 'Senior web developer (sample)', 'استودیو نمونه C', 'Sample Studio C', '2022 – 2026', 'توسعه سامانه‌های مدیریت محتوا و فروشگاه.', 'Built content management and e-commerce systems.', null, null],
                    ['education', 'کارشناسی مهندسی نرم‌افزار (نمونه)', 'BSc in software engineering (sample)', 'دانشگاه نمونه', 'Sample University', '2017 – 2021', null, null, null, null],
                    ['skill', 'Laravel و PHP', 'Laravel & PHP', null, null, null, null, null, null, 92],
                    ['skill', 'MySQL و طراحی پایگاه داده', 'MySQL & database design', null, null, null, null, null, null, 88],
                ],
            ],
        ];

        foreach ($samples as $index => $data) {
            $items = $data['items'];
            unset($data['items']);
            $data['is_sample'] = true;
            $data['is_published'] = true;

            $resume = Resume::updateOrCreate(['slug' => $data['slug']], $data);
            $resume->items()->delete();
            foreach ($items as $i => $row) {
                [$type, $titleFa, $titleEn, $orgFa, $orgEn, $period, $descFa, $descEn, $url, $level] = $row;
                $resume->items()->create([
                    'type' => $type, 'title_fa' => $titleFa, 'title_en' => $titleEn,
                    'organization_fa' => $orgFa, 'organization_en' => $orgEn, 'period' => $period,
                    'description_fa' => $descFa, 'description_en' => $descEn, 'url' => $url,
                    'level' => $level, 'sort_order' => ($i + 1) * 10,
                ]);
            }
        }
    }
}
