<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\ResumeItem;
use App\Support\PublicMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    private function rules(): array
    {
        return [
            'name_fa' => ['required', 'string', 'max:120'],
            'name_en' => ['required', 'string', 'max:120'],
            'job_title_fa' => ['nullable', 'string', 'max:160'],
            'job_title_en' => ['nullable', 'string', 'max:160'],
            'bio_fa' => ['nullable', 'string', 'max:3000'],
            'bio_en' => ['nullable', 'string', 'max:3000'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location_fa' => ['nullable', 'string', 'max:160'],
            'location_en' => ['nullable', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160', 'regex:/^[a-z0-9-]+$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_photo' => ['nullable', 'boolean'],
        ];
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'resume';
        $candidate = $slug;
        $i = 2;
        while (Resume::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }

    public function index()
    {
        return view('admin.resumes.index', ['resumes' => Resume::withCount('items')->orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create()
    {
        return view('admin.resumes.form', ['resume' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name_en']);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_sample'] = false;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = PublicMedia::store($request->file('photo'), 'resumes');
        }
        unset($data['photo'], $data['remove_photo']);

        $resume = Resume::create($data);

        return redirect()->route('admin.resumes.edit', $resume)->with('success', tr('رزومه ذخیره شد. حالا بخش‌های رزومه را اضافه کنید.', 'Resume saved. Now add its sections.'));
    }

    public function edit(Resume $resume)
    {
        $resume->load('items');

        return view('admin.resumes.form', ['resume' => $resume]);
    }

    public function update(Request $request, Resume $resume)
    {
        $data = $request->validate($this->rules());
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name_en'], $resume->id);
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $oldPhoto = $resume->photo_path;
        if ($request->hasFile('photo')) {
            $data['photo_path'] = PublicMedia::store($request->file('photo'), 'resumes');
        } elseif ($request->boolean('remove_photo')) {
            $data['photo_path'] = null;
        }
        if (array_key_exists('photo_path', $data) && $oldPhoto && $oldPhoto !== $data['photo_path']) {
            PublicMedia::delete($oldPhoto);
        }
        unset($data['photo'], $data['remove_photo']);

        $resume->update($data);

        return redirect()->route('admin.resumes.edit', $resume)->with('success', tr('رزومه به‌روزرسانی شد.', 'Resume updated.'));
    }

    public function destroy(Resume $resume)
    {
        PublicMedia::delete($resume->photo_path);
        $resume->delete();

        return redirect()->route('admin.resumes.index')->with('success', tr('رزومه حذف شد.', 'Resume deleted.'));
    }

    public function storeItem(Request $request, Resume $resume)
    {
        $data = $request->validate([
            'type' => ['required', 'in:'.implode(',', array_keys(Resume::ITEM_TYPES))],
            'title_fa' => ['required', 'string', 'max:190'],
            'title_en' => ['required', 'string', 'max:190'],
            'organization_fa' => ['nullable', 'string', 'max:190'],
            'organization_en' => ['nullable', 'string', 'max:190'],
            'period' => ['nullable', 'string', 'max:60'],
            'description_fa' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'url' => ['nullable', 'url', 'max:255', 'regex:/^https?:\/\//'],
            'level' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);
        $data['sort_order'] = (int) $resume->items()->max('sort_order') + 10;
        $resume->items()->create($data);

        return redirect()->route('admin.resumes.edit', $resume)->with('success', tr('مورد اضافه شد.', 'Entry added.'));
    }

    public function destroyItem(Resume $resume, ResumeItem $item)
    {
        abort_unless($item->resume_id === $resume->id, 404);
        $item->delete();

        return redirect()->route('admin.resumes.edit', $resume)->with('success', tr('مورد حذف شد.', 'Entry deleted.'));
    }
}
