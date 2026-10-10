<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    public const ITEM_TYPES = [
        'experience' => ['fa' => 'سوابق کاری', 'en' => 'Work experience'],
        'education' => ['fa' => 'تحصیلات', 'en' => 'Education'],
        'skill' => ['fa' => 'مهارت‌ها', 'en' => 'Skills'],
        'certificate' => ['fa' => 'گواهینامه‌ها', 'en' => 'Certificates'],
    ];

    /** Form labels per item type (FA / EN). Keys: title, org, period, desc, level, url. */
    public const ITEM_FIELDS = [
        'education' => [
            'title' => ['fa' => 'مدرک / رشته تحصیلی', 'en' => 'Degree / field of study'],
            'org' => ['fa' => 'دانشگاه / مدرسه', 'en' => 'University / school'],
            'period' => ['fa' => 'سال‌های تحصیل (مثلاً ۱۴۰۰ – ۱۴۰۳)', 'en' => 'Years (e.g. 2017 – 2021)'],
            'desc' => ['fa' => 'توضیح (معدل، پروژه، افتخارات)', 'en' => 'Notes (GPA, projects, honours)'],
            'show_level' => false, 'show_url' => false,
        ],
        'experience' => [
            'title' => ['fa' => 'سمت / عنوان شغلی', 'en' => 'Job title'],
            'org' => ['fa' => 'شرکت / کارفرما', 'en' => 'Company / employer'],
            'period' => ['fa' => 'بازه زمانی (مثلاً ۱۴۰۱ – اکنون)', 'en' => 'Period (e.g. 2022 – present)'],
            'desc' => ['fa' => 'شرح وظایف و دستاوردها', 'en' => 'Responsibilities and achievements'],
            'show_level' => false, 'show_url' => false,
        ],
        'certificate' => [
            'title' => ['fa' => 'نام گواهینامه', 'en' => 'Certificate name'],
            'org' => ['fa' => 'صادرکننده', 'en' => 'Issuing organisation'],
            'period' => ['fa' => 'سال صدور (مثلاً ۱۴۰۲)', 'en' => 'Issue year (e.g. 2023)'],
            'desc' => ['fa' => 'توضیح (اختیاری)', 'en' => 'Notes (optional)'],
            'show_level' => false, 'show_url' => true,
        ],
        'skill' => [
            'title' => ['fa' => 'نام مهارت', 'en' => 'Skill name'],
            'org' => ['fa' => 'حوزه / دسته (اختیاری)', 'en' => 'Area / category (optional)'],
            'period' => ['fa' => 'مدت تجربه (اختیاری)', 'en' => 'Experience length (optional)'],
            'desc' => ['fa' => 'توضیح (اختیاری)', 'en' => 'Notes (optional)'],
            'show_level' => true, 'show_url' => false,
        ],
    ];

    protected $fillable = [
        'slug', 'name_fa', 'name_en', 'job_title_fa', 'job_title_en', 'bio_fa', 'bio_en',
        'email', 'phone', 'location_fa', 'location_en', 'photo_path', 'is_sample',
        'is_published', 'sort_order',
    ];

    protected $casts = ['is_sample' => 'boolean', 'is_published' => 'boolean'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function items()
    {
        return $this->hasMany(ResumeItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function itemsOf(string $type)
    {
        return $this->items->where('type', $type)->values();
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return PublicMedia::url($this->photo_path);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
