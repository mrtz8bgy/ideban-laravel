<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    private function rules(?MenuItem $item): array
    {
        return [
            'location' => ['required', 'in:header'],
            'parent_id' => ['nullable', Rule::exists('menu_items', 'id')->where('location', 'header')],
            'label_fa' => ['required', 'string', 'max:120'],
            'label_en' => ['required', 'string', 'max:120'],
            'url' => ['required', 'string', 'max:255', 'regex:#^(/[^\s]*|https?://[^\s]+|\#[^\s]*)$#'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function checkDepth(array $data, ?MenuItem $item): void
    {
        if (empty($data['parent_id'])) {
            return;
        }
        $parent = MenuItem::find($data['parent_id']);
        if ($item && (int) $parent->id === (int) $item->id) {
            throw ValidationException::withMessages(['parent_id' => tr('یک منو نمی‌تواند زیر خودش باشد.', 'An item cannot be its own parent.')]);
        }
        // The new depth is parent depth + 1, which must not exceed MAX_DEPTH.
        $newDepth = $parent->depth() + 1;
        $subtreeDepth = $item ? $this->subtreeHeight($item) : 0;
        if ($newDepth + $subtreeDepth > MenuItem::MAX_DEPTH) {
            throw ValidationException::withMessages(['parent_id' => tr('حداکثر سه سطح منو مجاز است.', 'The menu supports at most three levels.')]);
        }
        if ($item && $this->isDescendant($parent, $item)) {
            throw ValidationException::withMessages(['parent_id' => tr('این والد زیرمجموعهٔ همین منو است.', 'That parent is a child of this item.')]);
        }
    }

    private function subtreeHeight(MenuItem $item): int
    {
        $children = MenuItem::where('parent_id', $item->id)->get();
        if ($children->isEmpty()) {
            return 0;
        }

        return 1 + $children->map(fn ($c) => $this->subtreeHeight($c))->max();
    }

    private function isDescendant(MenuItem $candidate, MenuItem $item): bool
    {
        $current = $candidate;
        while ($current && $current->parent_id) {
            if ((int) $current->parent_id === (int) $item->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }

    public function index()
    {
        $items = MenuItem::with('parent')->orderBy('sort_order')->orderBy('id')->get();
        $ordered = collect();
        $add = function ($parentId, $level) use (&$add, $items, $ordered) {
            foreach ($items->where('parent_id', $parentId) as $item) {
                $item->level = $level;
                $ordered->push($item);
                $add($item->id, $level + 1);
            }
        };
        $add(null, 0);

        return view('admin.menu.index', ['items' => $ordered]);
    }

    public function create()
    {
        return view('admin.menu.form', ['item' => null, 'parents' => $this->parentOptions(null)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(null));
        $this->checkDepth($data, null);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('success', tr('منو ذخیره شد.', 'Menu item saved.'));
    }

    public function edit(MenuItem $menu)
    {
        return view('admin.menu.form', ['item' => $menu, 'parents' => $this->parentOptions($menu)]);
    }

    public function update(Request $request, MenuItem $menu)
    {
        $data = $request->validate($this->rules($menu));
        $this->checkDepth($data, $menu);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', tr('منو به‌روزرسانی شد.', 'Menu item updated.'));
    }

    public function destroy(MenuItem $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', tr('منو و زیرمجموعه‌های آن حذف شدند.', 'Menu item and its children were deleted.'));
    }

    /** Only items that can still take a child (depth below MAX_DEPTH) and are not the item itself. */
    private function parentOptions(?MenuItem $item)
    {
        return MenuItem::orderBy('sort_order')->orderBy('id')->get()
            ->filter(fn ($p) => (!$item || $p->id !== $item->id) && $p->depth() < MenuItem::MAX_DEPTH)
            ->values();
    }
}
