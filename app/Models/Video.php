<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Support\PublicMedia;

class Video extends Model
{
    protected $fillable = [
        'slug', 'title_fa', 'title_en', 'description_fa', 'description_en', 'video_url',
        'thumbnail_url', 'duration_seconds', 'category', 'tags', 'service_id',
        'is_published', 'published_at', 'thumbnail_path', 'video_path',
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

    public function getThumbnailImageUrlAttribute()
    {
        return PublicMedia::url($this->thumbnail_path) ?: $this->thumbnail_url;
    }

    public function getUploadedVideoUrlAttribute()
    {
        return PublicMedia::url($this->video_path);
    }

    public function isUploadedVideo(): bool
    {
        return !empty($this->video_path);
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Only well-known video hosts are accepted; the page embeds an iframe from
     * the host's privacy-enhanced embed URL, never the raw user-supplied link.
     */
    public static function embedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $parts = parse_url(trim($url));
        if (!is_array($parts) || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])) {
            return null;
        }

        $host = strtolower(preg_replace('/^www\./', '', $parts['host']));
        $path = $parts['path'] ?? '';

        if ($host === 'youtu.be') {
            $id = trim($path, '/');

            return preg_match('/^[A-Za-z0-9_-]{6,20}$/', $id) ? 'https://www.youtube-nocookie.com/embed/'.$id : null;
        }

        if ($host === 'youtube.com' || $host === 'm.youtube.com') {
            parse_str($parts['query'] ?? '', $query);
            $id = $query['v'] ?? null;
            if (!$id && preg_match('#^/(embed|shorts)/([A-Za-z0-9_-]{6,20})#', $path, $m)) {
                $id = $m[2];
            }

            return is_string($id) && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $id) ? 'https://www.youtube-nocookie.com/embed/'.$id : null;
        }

        if ($host === 'vimeo.com' && preg_match('#^/(\d{5,12})#', $path, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        if ($host === 'aparat.com' && preg_match('#^/v/([A-Za-z0-9]{4,20})#', $path, $m)) {
            return 'https://www.aparat.com/video/video/embed/videohash/'.$m[1].'/vt/frame';
        }

        return null;
    }

    public static function supportedHosts(): array
    {
        return ['youtube.com', 'youtu.be', 'vimeo.com', 'aparat.com'];
    }
}
