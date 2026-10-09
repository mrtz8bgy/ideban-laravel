<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Service;
use App\Models\Video;
use App\Support\PublicMedia;
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
        $this->storeMedia($request, $type, $data);
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
        $data = $this->validatedData($request, $type, $item);
        $mediaToDelete = $this->storeMedia($request, $type, $data, $item);
        $item->update($data);
        foreach ($mediaToDelete as $path) {
            PublicMedia::delete($path);
        }

        return redirect()->route('admin.content.index', $type)->with('success', __('site.saved'));
    }

    public function destroy(string $type, $id)
    {
        $cfg = $this->config($type);
        $item = $cfg['model']::findOrFail($id);
        if ($type === 'articles') {
            $paths = [$item->cover_path];
        } else {
            $paths = [$item->thumbnail_path, $item->video_path];
        }
        $item->delete();
        foreach ($paths as $path) {
            PublicMedia::delete($path);
        }

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

    private function validatedData(Request $request, string $type, $item = null): array
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
        $ignoreId = $item ? $item->id : null;
        $slug = ['required', 'string', 'max:190', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique($cfg['table'], 'slug')->ignore($ignoreId)];

        if ($type === 'articles') {
            $rules = [
                'slug' => $slug,
                'excerpt_fa' => ['nullable', 'string', 'max:500'],
                'excerpt_en' => ['nullable', 'string', 'max:500'],
                'body_fa' => ['nullable', 'string', 'max:100000'],
                'body_en' => ['nullable', 'string', 'max:100000'],
                'cover_url' => ['nullable', 'url', 'max:2048'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
                'author_name' => ['nullable', 'string', 'max:120'],
                'meta_title' => ['nullable', 'string', 'max:190'],
                'meta_description' => ['nullable', 'string', 'max:320'],
            ] + $common;
        } else {
            $rules = [
                'slug' => $slug,
                'description_fa' => ['nullable', 'string', 'max:5000'],
                'description_en' => ['nullable', 'string', 'max:5000'],
                'video_url' => ['nullable', 'string', 'max:2048', function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '' && !Video::embedUrl($value)) {
                        $fail(__('content.invalid_video_url'));
                    }
                }],
                'thumbnail_url' => ['nullable', 'url', 'max:2048'],
                'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:86400'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
                'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:'.((int) config('content.public_video_max_mb') * 1024)],
                'remove_video' => ['nullable', 'boolean'],
            ] + $common;
        }

        $data = $request->validate($rules);
        unset($data['media_file'], $data['video_file'], $data['remove_media'], $data['remove_video']);
        if ($type === 'videos' && !$request->hasFile('video_file') && empty($data['video_url']) && !($item && $item->video_path) && !$request->boolean('remove_video')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'video_url' => [__('content.video_url_or_upload_required')],
            ]);
        }
        $data['is_published'] = $request->boolean('is_published');
        $data['tags'] = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn ($t) => trim($t))->filter()->values()->all();
        $data['tags'] = $data['tags'] ?: null;

        if ($type === 'videos' && !empty($data['video_url'])) {
            $data['video_url'] = trim($data['video_url']);
        }

        // Publishing without a date means "now" so the item is visible immediately.
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    private function storeMedia(Request $request, string $type, array &$data, $item = null): array
    {
        $imageDirectory = $type === 'articles' ? 'articles' : 'video-thumbnails';
        $imageColumn = $type === 'articles' ? 'cover_path' : 'thumbnail_path';
        $oldImagePath = $item ? $item->$imageColumn : null;
        $pathsToDelete = [];

        if ($request->hasFile('media_file')) {
            $data[$imageColumn] = PublicMedia::store($request->file('media_file'), $imageDirectory);
            if ($type === 'articles') {
                $data['cover_url'] = null;
            } else {
                $data['thumbnail_url'] = null;
            }
            $pathsToDelete[] = $oldImagePath;
        } elseif ($request->boolean('remove_media')) {
            $data[$imageColumn] = null;
            $data[$type === 'articles' ? 'cover_url' : 'thumbnail_url'] = null;
            $pathsToDelete[] = $oldImagePath;
        }

        if ($type === 'videos') {
            if ($request->hasFile('video_file')) {
                $oldVideoPath = $item ? $item->video_path : null;
                $data['video_path'] = PublicMedia::store($request->file('video_file'), 'videos');
                $data['video_url'] = '';
                $pathsToDelete[] = $oldVideoPath;
            } elseif (!empty($data['video_url']) || $request->boolean('remove_video')) {
                $oldVideoPath = $item ? $item->video_path : null;
                $data['video_path'] = null;
                $pathsToDelete[] = $oldVideoPath;
            }
        }

        return $pathsToDelete;
    }
}
