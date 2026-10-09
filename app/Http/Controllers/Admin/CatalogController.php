<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\ServiceAddon;
use App\Models\PricingPlan;
use App\Models\ServicePrice;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\PublicMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    private $catalog = [
        'categories' => [ServiceCategory::class, 'category'],
        'services' => [Service::class, 'service'],
        'plans' => [PricingPlan::class, 'plan'],
        'portfolio' => [Portfolio::class, 'portfolio'],
        'prices' => [ServicePrice::class, 'price'],
        'addons' => [ServiceAddon::class, 'addon'],
    ];

    private function modelClass($type)
    {
        abort_unless(isset($this->catalog[$type]), 404);

        return $this->catalog[$type][0];
    }

    public function index($type)
    {
        $class = $this->modelClass($type);
        $items = $class::latest()->paginate(20);

        return view('admin.catalog.index', [
            'type' => $type,
            'items' => $items,
            'labels' => $this->labels($type),
        ]);
    }

    public function create($type)
    {
        $this->modelClass($type);

        return $this->form($type, null);
    }

    public function store(Request $request, $type)
    {
        $class = $this->modelClass($type);
        $data = $this->validatedData($request, $type);
        $this->storeMedia($request, $type, $data);
        $class::create($data);

        return redirect()->route('admin.catalog.index', $type)->with('success', __('site.saved'));
    }

    public function edit($type, $id)
    {
        $class = $this->modelClass($type);

        return $this->form($type, $class::findOrFail($id));
    }

    public function update(Request $request, $type, $id)
    {
        $class = $this->modelClass($type);
        $item = $class::findOrFail($id);
        $data = $this->validatedData($request, $type, $item->id);
        $oldMedia = $item->media_path;
        $this->storeMedia($request, $type, $data);
        $item->update($data);
        if ($oldMedia && $oldMedia !== $item->media_path) {
            PublicMedia::delete($oldMedia);
        }

        return redirect()->route('admin.catalog.index', $type)->with('success', __('site.saved'));
    }

    public function destroy($type, $id)
    {
        $class = $this->modelClass($type);
        $item = $class::findOrFail($id);
        $mediaPath = $item->media_path;
        $item->delete();
        PublicMedia::delete($mediaPath);

        return redirect()->route('admin.catalog.index', $type)->with('success', __('site.deleted'));
    }

    private function form($type, $item)
    {
        return view('admin.catalog.form', [
            'type' => $type,
            'item' => $item,
            'labels' => $this->labels($type),
            'categories' => ServiceCategory::orderBy('sort_order')->get(),
            'services' => Service::orderBy('title_fa')->get(),
        ]);
    }

    private function validatedData(Request $request, $type, $ignoreId = null)
    {
        $slugRule = Rule::unique($this->tableFor($type), 'slug')->ignore($ignoreId);
        $common = [
            'slug' => ['required', 'string', 'max:190', $slugRule],
        ];
        $rules = [
            'categories' => $common + [
                'name_fa' => ['required', 'string', 'max:190'],
                'name_en' => ['required', 'string', 'max:190'],
                'description_fa' => ['nullable', 'string'],
                'description_en' => ['nullable', 'string'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['nullable', 'boolean'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
            ],
            'services' => $common + [
                'category_id' => ['nullable', 'exists:service_categories,id'],
                'title_fa' => ['required', 'string', 'max:190'],
                'title_en' => ['required', 'string', 'max:190'],
                'summary_fa' => ['nullable', 'string', 'max:500'],
                'summary_en' => ['nullable', 'string', 'max:500'],
                'description_fa' => ['nullable', 'string'],
                'description_en' => ['nullable', 'string'],
                'included_fa' => ['nullable', 'string'],
                'included_en' => ['nullable', 'string'],
                'excluded_fa' => ['nullable', 'string'],
                'excluded_en' => ['nullable', 'string'],
                'delivery_days' => ['nullable', 'integer', 'min:1'],
                'is_featured' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
            ],
            'plans' => $common + [
                'service_id' => ['nullable', 'exists:services,id'],
                'name_fa' => ['required', 'string', 'max:190'],
                'name_en' => ['required', 'string', 'max:190'],
                'description_fa' => ['nullable', 'string'],
                'description_en' => ['nullable', 'string'],
                'features_fa' => ['nullable', 'string'],
                'features_en' => ['nullable', 'string'],
                'setup_fee' => ['nullable', 'integer', 'min:0'],
                'recurring_fee' => ['nullable', 'integer', 'min:0'],
                'recurrence_fa' => ['nullable', 'string', 'max:80'],
                'recurrence_en' => ['nullable', 'string', 'max:80'],
                'price_type' => ['required', 'in:company,negotiated,quote'],
                'valid_until' => ['nullable', 'date'],
                'is_featured' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
            ],
            'portfolio' => $common + [
                'title_fa' => ['required', 'string', 'max:190'],
                'title_en' => ['required', 'string', 'max:190'],
                'client_name' => ['nullable', 'string', 'max:190'],
                'challenge_fa' => ['nullable', 'string'],
                'challenge_en' => ['nullable', 'string'],
                'solution_fa' => ['nullable', 'string'],
                'solution_en' => ['nullable', 'string'],
                'result_fa' => ['nullable', 'string'],
                'result_en' => ['nullable', 'string'],
                'technologies' => ['nullable', 'string'],
                'image_url' => ['nullable', 'url', 'max:2048'],
                'project_url' => ['nullable', 'url', 'max:2048'],
                'completed_at' => ['nullable', 'date'],
                'is_published' => ['nullable', 'boolean'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
            ],
            'addons' => [
                'service_id' => ['nullable', 'exists:services,id'],
                'name_fa' => ['required', 'string', 'max:190'],
                'name_en' => ['required', 'string', 'max:190'],
                'description_fa' => ['nullable', 'string'],
                'description_en' => ['nullable', 'string'],
                'amount' => ['nullable', 'integer', 'min:0'],
                'price_type' => ['required', 'in:company,negotiated,quote'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['nullable', 'boolean'],
                'media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_media' => ['nullable', 'boolean'],
            ],
            'prices' => [
                'service_id' => ['nullable', 'exists:services,id'],
                'title_fa' => ['required', 'string', 'max:190'],
                'title_en' => ['required', 'string', 'max:190'],
                'unit_fa' => ['nullable', 'string', 'max:100'],
                'unit_en' => ['nullable', 'string', 'max:100'],
                'amount' => ['nullable', 'integer', 'min:0'],
                'minimum_amount' => ['nullable', 'integer', 'min:0'],
                'maximum_amount' => ['nullable', 'integer', 'min:0'],
                'price_type' => ['required', 'in:official,company,negotiated,quote'],
                'tariff_year' => ['nullable', 'integer', 'min:1300', 'max:1500'],
                'source_name' => ['required_if:price_type,official', 'nullable', 'string', 'max:190'],
                'source_url' => ['required_if:price_type,official', 'nullable', 'url', 'max:2048'],
                'is_verified' => ['nullable', 'boolean'],
                'show_amount' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'valid_from' => ['nullable', 'date'],
                'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
                'notes' => ['nullable', 'string', 'max:5000'],
            ],
        ];

        if ($type === 'prices' && $request->filled('minimum_amount')) {
            $rules[$type]['maximum_amount'][] = 'gte:minimum_amount';
        }
        $data = $request->validate($rules[$type]);
        unset($data['media_file'], $data['remove_media']);

        foreach (['is_active', 'is_featured', 'is_published', 'is_verified', 'show_amount'] as $checkbox) {
            if (array_key_exists($checkbox, $rules[$type])) {
                $data[$checkbox] = $request->boolean($checkbox);
            }
        }
        foreach (['included_fa', 'included_en', 'excluded_fa', 'excluded_en', 'features_fa', 'features_en', 'technologies'] as $lines) {
            if (array_key_exists($lines, $data)) {
                $data[$lines] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $data[$lines]))));
            }
        }

        return $data;
    }

    private function storeMedia(Request $request, string $type, array &$data): void
    {
        if (!$request->hasFile('media_file')) {
            if ($request->boolean('remove_media')) {
                $data['media_path'] = null;
                if ($type === 'portfolio') {
                    $data['image_url'] = null;
                }
            }

            unset($data['remove_media']);

            return;
        }

        $directory = [
            'categories' => 'categories',
            'services' => 'services',
            'plans' => 'plans',
            'addons' => 'addons',
            'portfolio' => 'portfolio',
        ][$type] ?? null;

        if ($directory === null) {
            unset($data['remove_media']);

            return;
        }

        $data['media_path'] = PublicMedia::store($request->file('media_file'), $directory);
        if ($type === 'portfolio') {
            $data['image_url'] = null;
        }
        unset($data['remove_media']);
    }

    private function tableFor($type)
    {
        return [
            'categories' => 'service_categories',
            'services' => 'services',
            'plans' => 'pricing_plans',
            'portfolio' => 'portfolios',
            'prices' => 'service_prices',
            'addons' => 'service_addons',
        ][$type];
    }

    private function labels($type)
    {
        return [
            'categories' => [
                'name_fa' => 'نام فارسی', 'name_en' => 'نام انگلیسی', 'slug' => 'شناسه URL',
                'description_fa' => 'توضیحات فارسی', 'description_en' => 'توضیحات انگلیسی',
                'sort_order' => 'ترتیب نمایش', 'is_active' => 'فعال',
            ],
            'services' => [
                'category_id' => 'دسته‌بندی', 'title_fa' => 'عنوان فارسی', 'title_en' => 'عنوان انگلیسی',
                'slug' => 'شناسه URL', 'summary_fa' => 'خلاصه فارسی', 'summary_en' => 'خلاصه انگلیسی',
                'description_fa' => 'شرح فارسی', 'description_en' => 'شرح انگلیسی',
                'included_fa' => 'موارد شامل (هر خط یک مورد)', 'included_en' => 'Included (one per line)',
                'excluded_fa' => 'موارد خارج از تعهد (هر خط یک مورد)', 'excluded_en' => 'Excluded (one per line)',
                'delivery_days' => 'زمان تقریبی (روز)', 'is_featured' => 'ویژه صفحه اصلی', 'is_active' => 'فعال',
            ],
            'plans' => [
                'service_id' => 'خدمت مرتبط', 'name_fa' => 'نام فارسی', 'name_en' => 'نام انگلیسی',
                'slug' => 'شناسه URL', 'description_fa' => 'توضیحات فارسی', 'description_en' => 'توضیحات انگلیسی',
                'features_fa' => 'امکانات فارسی (هر خط یک مورد)', 'features_en' => 'Features (one per line)',
                'setup_fee' => 'هزینه راه‌اندازی (تومان)', 'recurring_fee' => 'هزینه دوره‌ای (تومان)',
                'recurrence_fa' => 'دوره پرداخت فارسی', 'recurrence_en' => 'Billing period in English',
                'price_type' => 'نوع قیمت', 'valid_until' => 'اعتبار تا', 'sort_order' => 'ترتیب نمایش',
                'is_featured' => 'ویژه صفحه اصلی', 'is_active' => 'فعال',
            ],
            'portfolio' => [
                'title_fa' => 'عنوان فارسی', 'title_en' => 'عنوان انگلیسی', 'slug' => 'شناسه URL',
                'client_name' => 'نام مشتری (در صورت اجازه انتشار)', 'challenge_fa' => 'مسئله فارسی',
                'challenge_en' => 'Challenge in English', 'solution_fa' => 'راهکار فارسی',
                'solution_en' => 'Solution in English', 'result_fa' => 'نتیجه فارسی',
                'result_en' => 'Result in English', 'technologies' => 'فناوری‌ها (هر خط یک مورد)',
                'image_url' => 'آدرس تصویر', 'project_url' => 'آدرس پروژه', 'completed_at' => 'تاریخ اجرا',
                'is_published' => 'منتشر شود',
            ],
            'addons' => [
                'service_id' => 'خدمت مرتبط', 'name_fa' => 'نام فارسی', 'name_en' => 'نام انگلیسی',
                'description_fa' => 'توضیحات فارسی', 'description_en' => 'توضیحات انگلیسی',
                'amount' => 'مبلغ (تومان)', 'price_type' => 'نوع قیمت', 'sort_order' => 'ترتیب نمایش', 'is_active' => 'فعال',
            ],
            'prices' => [
                'service_id' => 'خدمت مرتبط', 'title_fa' => 'عنوان فارسی', 'title_en' => 'عنوان انگلیسی',
                'unit_fa' => 'واحد فارسی', 'unit_en' => 'واحد انگلیسی', 'amount' => 'مبلغ (تومان)',
                'minimum_amount' => 'حداقل مبلغ', 'maximum_amount' => 'حداکثر مبلغ',
                'price_type' => 'نوع قیمت', 'tariff_year' => 'سال تعرفه', 'source_name' => 'نام منبع',
                'source_url' => 'آدرس منبع', 'is_verified' => 'منبع تأیید شده', 'show_amount' => 'نمایش مبلغ', 'is_active' => 'فعال',
                'valid_from' => 'شروع اعتبار', 'valid_until' => 'پایان اعتبار', 'notes' => 'توضیحات و استثناها',
            ],
        ][$type];
    }
}
