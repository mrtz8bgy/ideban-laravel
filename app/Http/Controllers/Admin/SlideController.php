<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SlideController extends Controller
{
    public function index()
    {
        return view('admin.slides.index', ['slides' => Slide::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create()
    {
        return view('admin.slides.form', ['slide' => new Slide(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        Slide::create($data);

        return redirect()->route('admin.slides.index')->with('success', tr('اسلاید ذخیره شد.', 'Slide saved.'));
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.form', ['slide' => $slide]);
    }

    public function update(Request $request, Slide $slide)
    {
        $slide->update($this->validated($request, false, $slide));

        return redirect()->route('admin.slides.index')->with('success', tr('اسلاید به‌روزرسانی شد.', 'Slide updated.'));
    }

    public function destroy(Slide $slide)
    {
        if ($slide->is_sample === false && strpos((string) $slide->image_path, 'uploads/slides/') === 0) {
            @unlink(public_path($slide->image_path));
        }
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('success', tr('اسلاید حذف شد.', 'Slide deleted.'));
    }

    private function validated(Request $request, bool $creating, ?Slide $slide = null): array
    {
        $data = $request->validate([
            'title_fa' => ['required', 'string', 'max:190'],
            'title_en' => ['required', 'string', 'max:190'],
            'subtitle_fa' => ['nullable', 'string', 'max:500'],
            'subtitle_en' => ['nullable', 'string', 'max:500'],
            'button_text_fa' => ['nullable', 'string', 'max:80'],
            'button_text_en' => ['nullable', 'string', 'max:80'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'image' => [$creating ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $url = $data['button_url'] ?? null;
        if ($url !== null && $url !== '' && ! preg_match('#^(/[^/\\\\]|https://)#', $url)) {
            throw ValidationException::withMessages(['button_url' => [tr('آدرس دکمه باید با / یا https:// شروع شود.', 'Button link must start with / or https://.')]]);
        }

        if ($request->hasFile('image')) {
            $name = 'slide-'.bin2hex(random_bytes(8)).'.'.$request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/slides'), $name);
            $data['image_path'] = 'uploads/slides/'.$name;
            $data['is_sample'] = false;
        }
        unset($data['image']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
