<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeItem extends Model
{
    protected $fillable = [
        'resume_id', 'type', 'title_fa', 'title_en', 'organization_fa', 'organization_en',
        'period', 'description_fa', 'description_en', 'url', 'level', 'sort_order',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
