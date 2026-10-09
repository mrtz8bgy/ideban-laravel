<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'slug', 'title_fa', 'title_en', 'excerpt_fa', 'excerpt_en', 'body_fa', 'body_en',
        'category', 'tags', 'cover_url', 'author_name', 'service_id', 'meta_title',
        'meta_description', 'is_published', 'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Render the admin-authored Markdown-lite body. Raw HTML is escaped,
     * and h2 headings receive anchors so the table of contents can link to them.
     */
    public function renderedBody($locale)
    {
        $body = (string) $this->{'body_'.$locale};
        if ($body === '') {
            return ['html' => '', 'toc' => []];
        }

        $html = Str::markdown($body, [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);

        $toc = [];
        $html = preg_replace_callback('/<h2>(.*?)<\/h2>/su', function ($m) use (&$toc) {
            $text = trim(strip_tags($m[1]));
            $id = 'sec-'.(count($toc) + 1);
            $toc[] = ['id' => $id, 'text' => $text];

            return '<h2 id="'.$id.'">'.$m[1].'</h2>';
        }, $html);

        return ['html' => $html, 'toc' => $toc];
    }
}
