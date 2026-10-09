<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\PublicMedia;

class Portfolio extends Model
{
    protected $fillable = [
        'slug', 'title_fa', 'title_en', 'client_name', 'challenge_fa', 'challenge_en',
        'solution_fa', 'solution_en', 'result_fa', 'result_en', 'technologies',
        'image_url', 'project_url', 'completed_at', 'is_published', 'media_path',
    ];

    protected $casts = [
        'technologies' => 'array',
        'completed_at' => 'date',
        'is_published' => 'boolean',
    ];

    public function getMediaUrlAttribute()
    {
        return PublicMedia::url($this->media_path) ?: $this->image_url;
    }
}
