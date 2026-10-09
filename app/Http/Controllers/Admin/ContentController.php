<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Service;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    /** Article and video management. Type is restricted to these two models. */
    private function config(string $type): array
    {
        abort_unless(in_array($type, ['articles', 'videos'], true), 404);

        return $type === 'articles'
            ? ['model' => Article::class, 'table' => 'articles']
            : ['model' => Video::class, 'table' => 'videos'];
    }

    public function index(string $type)
    {
        $cfg = $this->config($type);
        $items = $cfg['model']::latest()->paginate(20);

        return view('admin.content.index', ['type' => $type, 'items' => $items]);
    }

    public function create(string $type)
    {
        return $this->form($type, null);
    }

    public function store(Request $request, string $type)
    {
        $cfg = $this->config($type);
        $data = $this->validatedData($request, $type);
        $cfg['model']::create($data);

        return redirect()->route('admin.content.index', $type)->with('success', __('site.saved'));
    }

    public function edit(string $type, $id)
    {
        $cfg = $this->config($type);

        return $this->form($type, $cfg['model']::findOrFail($id));
    }

    public function update(Request $request, string $type, $id)
    {
        $cfg = $this->config($type);
        $item = $cfg['model']::findOrFail($id);
        $item->update($this->validatedData($request, $type, $item->id));

        return redirect()->route('admin.content.index', $type)->with('success', __('site.saved'));
    }

    public function destroy(string $type, $id)
    {
        $cfg = $this->config($type);
        $cfg['model']::findOrFail($id)->delete();

        return redirect()->route('admin.content.index', $type)->with('success', __('site.deleted'));
    }

    private function form(string $type, $item)
    {
        return view('admin.content.form', [
            'type' => $type,
            'item' => $item,
            'services' => Service::orderBy('title_fa')->get(),
        ]);
    }

    private function validatedData(Request $request, string $type, $ignoreId = null): array
    {
        $cfg = $this->config($type);
        $common = [
            'title_fa' => ['required', 'string', 'max:190'],
            'title_en' => ['required', 'string', 'max:190'],
            'service_id' => ['nullable', 'exists:services,id'],
            'category' => ['nullable', 'string', 'max:80'],
            'tags' => ['nullable', 'string', 'max:500'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ];
        $slug = ['required', 'string', 'max:190', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique($cfg['table'], 'slug')->ignore($ignoreId)];

        if ($type === 'articles') {
            $rules = [
                'slug' => $slug,
                'excerpt_fa' => ['nullable', 'string', 'max:500'],
                'excerpt_en' => ['nullable', 'string', 'max:500'],
                'body_fa' => ['nullable', 'string', 'max:100000'],
                'body_en' => ['nullable', 'string', 'max:100000'],
                'cover_url' => ['nullable', 'url', 'max:2048'],
                'author_name' => ['nullable', 'string', 'max:120'],
                'meta_title' => ['nullable', 'string', 'max:190'],
                'meta_description' => ['nullable', 'string', 'max:320'],
            ] + $common;
        } else {
            $rules = [
                'slug' => $slug,
                'description_fa' => ['nullable', 'string', 'max:5000'],
                'description_en' => ['nullable', 'string', 'max:5000'],
                'video_url' => ['required', 'string', 'max:2048', function ($attribute, $value, $fail) {
                    if (!Video::embedUrl($value)) {
                        $fail(__('content.invalid_video_url'));
                    }
                }],
                'thumbnail_url' => ['nullable', 'url', 'max:2048'],
                'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:86400'],
            ] + $common;
        }

        $data = $request->validate($rules);
        $data['is_published'] = $request->boolean('is_published');
        $data['tags'] = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn ($t) => trim($t))->filter()->values()->all();
        $data['tags'] = $data['tags'] ?: null;

        if ($type === 'videos') {
            $data['video_url'] = trim($data['video_url']);
        }

        // Publishing without a date means "now" so the item is visible immediately.
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
