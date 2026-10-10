<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MenuItem extends Model
{
    /** Maximum nesting: top level (0) -> column (1) -> link (2). */
    public const MAX_DEPTH = 2;

    protected $fillable = ['location', 'parent_id', 'label_fa', 'label_en', 'url', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function depth(): int
    {
        $depth = 0;
        $current = $this;
        while ($current->parent_id) {
            $depth++;
            $current = $current->parent;
            if (!$current || $depth > 10) {
                break;
            }
        }

        return $depth;
    }

    /**
     * Active items of one menu location as a tree. Each node gets a `children` relation.
     */
    public static function tree(string $location = 'header'): Collection
    {
        $all = static::where('location', $location)->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')->get();
        $byParent = $all->groupBy(fn ($item) => $item->parent_id ?? 0);

        $build = function ($parentId) use (&$build, $byParent) {
            return ($byParent[$parentId] ?? collect())->map(function ($item) use (&$build) {
                $item->setRelation('children', $build($item->id));

                return $item;
            })->values();
        };

        return $build(0);
    }

    public function labelFor(?string $locale = null): string
    {
        return (string) $this->{'label_'.($locale ?: app()->getLocale())};
    }
}
