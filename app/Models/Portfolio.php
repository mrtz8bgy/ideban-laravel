<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'slug', 'title_fa', 'title_en', 'client_name', 'challenge_fa', 'challenge_en',
        'solution_fa', 'solution_en', 'result_fa', 'result_en', 'technologies',
        'image_url', 'project_url', 'completed_at', 'is_published',
    ];

    protected $casts = [
        'technologies' => 'array',
        'completed_at' => 'date',
        'is_published' => 'boolean',
    ];
}
