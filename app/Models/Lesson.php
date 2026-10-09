<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /** upload = video stored privately on this server; external = link to a video host; none = not yet added */
    public const SOURCES = ['none', 'upload', 'external'];

    protected $fillable = [
        'course_id', 'title_fa', 'title_en', 'sort_order', 'source', 'file_path', 'external_url',
        'duration_seconds', 'is_free_preview', 'is_published',
    ];

    protected $casts = ['is_free_preview' => 'boolean', 'is_published' => 'boolean'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /** Embed URL for an external lesson, or null. Upload lessons are served through the protected stream route. */
    public function externalEmbedUrl(): ?string
    {
        return $this->source === 'external' ? Video::embedUrl($this->external_url) : null;
    }
}
