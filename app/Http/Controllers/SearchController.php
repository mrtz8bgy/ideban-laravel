<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Course;
use App\Models\MenuItem;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Resume;
use App\Models\ResumeItem;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServicePrice;
use App\Models\Slide;
use App\Models\Video;
use App\Support\AppLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    private const RESULT_LIMIT = 10;

    public function index(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:100']);
        $q = trim((string) $request->query('q', ''));
        $groups = mb_strlen($q) >= 2 ? $this->search($q) : collect();
        $total = $groups->sum(function ($group) {
            return $group['items']->count();
        });

        return view('search.index', compact('q', 'groups', 'total'));
    }

    public function results(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:100']);
        $q = trim((string) $request->query('q', ''));
        $groups = mb_strlen($q) >= 2 ? $this->search($q) : collect();
        $total = $groups->sum(function ($group) {
            return $group['items']->count();
        });

        return response()->json([
            'html' => view('search.results', compact('q', 'groups', 'total'))->render(),
            'total' => $total,
        ]);
    }

    private function search(string $term): Collection
    {
        $patterns = $this->patterns($term);
        $locale = app()->getLocale();
        $groups = collect();

        $services = Service::where('is_active', true)
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['title_fa', 'title_en', 'summary_fa', 'summary_en', 'description_fa', 'description_en', 'included_fa', 'included_en', 'excluded_fa', 'excluded_en'], $patterns);
            })->orderByDesc('is_featured')->orderBy('id')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('خدمات', 'Services'), $services, function ($item) use ($locale) {
            return [$item->{'title_'.$locale}, $item->{'summary_'.$locale} ?: $item->{'description_'.$locale}, route('services.show', $item->slug)];
        });

        $categories = ServiceCategory::where('is_active', true)
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['name_fa', 'name_en', 'description_fa', 'description_en'], $patterns);
            })->orderBy('sort_order')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('دسته‌بندی خدمات', 'Service categories'), $categories, function ($item) use ($locale) {
            return [$item->{'name_'.$locale}, $item->{'description_'.$locale}, route('services.index').'#'.$item->slug];
        });

        $plans = PricingPlan::where('is_active', true)
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['name_fa', 'name_en', 'description_fa', 'description_en', 'features_fa', 'features_en'], $patterns);
            })->orderBy('sort_order')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('بسته‌های قیمتی', 'Pricing plans'), $plans, function ($item) use ($locale) {
            return [$item->{'name_'.$locale}, $item->{'description_'.$locale}, route('pricing.index')];
        });

        $prices = ServicePrice::where('is_active', true)
            ->whereHas('service', function (Builder $query) {
                $query->where('is_active', true);
            })->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['title_fa', 'title_en', 'unit_fa', 'unit_en', 'source_name', 'notes'], $patterns);
            })->with('service')->orderByDesc('tariff_year')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('تعرفه خدمات', 'Service rates'), $prices, function ($item) use ($locale) {
            $serviceTitle = $item->service ? $item->service->{'title_'.$locale} : '';

            return [$item->{'title_'.$locale}, trim($serviceTitle.' · '.$item->{'unit_'.$locale}, ' ·'), route('pricing.index')];
        });

        $portfolio = Portfolio::where('is_published', true)
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['title_fa', 'title_en', 'client_name', 'challenge_fa', 'challenge_en', 'solution_fa', 'solution_en', 'result_fa', 'result_en', 'technologies'], $patterns);
            })->latest('completed_at')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('نمونه‌کارها', 'Portfolio'), $portfolio, function ($item) use ($locale) {
            return [$item->{'title_'.$locale}, $item->{'challenge_'.$locale} ?: $item->{'solution_'.$locale}, route('portfolio.show', $item->slug)];
        });

        $articles = Article::public()
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['title_fa', 'title_en', 'excerpt_fa', 'excerpt_en', 'body_fa', 'body_en', 'category', 'tags', 'author_name', 'meta_title', 'meta_description'], $patterns);
            })->latest('published_at')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('مقالات', 'Articles'), $articles, function ($item) use ($locale) {
            return [$item->{'title_'.$locale}, $item->{'excerpt_'.$locale} ?: $item->{'category'}, route('blog.show', $item->slug)];
        });

        $videos = Video::public()
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['title_fa', 'title_en', 'description_fa', 'description_en', 'category', 'tags'], $patterns);
            })->latest('published_at')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('ویدئوها', 'Videos'), $videos, function ($item) use ($locale) {
            return [$item->{'title_'.$locale}, $item->{'description_'.$locale}, route('videos.show', $item->slug)];
        });

        $courses = Course::published()
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['title_fa', 'title_en', 'summary_fa', 'summary_en', 'description_fa', 'description_en', 'category', 'instructor_fa', 'instructor_en', 'prerequisite_fa', 'prerequisite_en'], $patterns);
            })->orderBy('sort_order')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('دوره‌های آموزشی', 'Courses'), $courses, function ($item) use ($locale) {
            return [$item->{'title_'.$locale}, $item->{'summary_'.$locale} ?: $item->{'category'}, route('academy.show', $item)];
        });

        $team = Resume::published()
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['name_fa', 'name_en', 'job_title_fa', 'job_title_en', 'bio_fa', 'bio_en', 'location_fa', 'location_en'], $patterns);
            })->orderBy('sort_order')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('رزومه‌ها', 'Resumes'), $team, function ($item) use ($locale) {
            return [$item->{'name_'.$locale}, $item->{'job_title_'.$locale}, route('team.show', $item)];
        });

        $resumeItems = ResumeItem::whereHas('resume', function (Builder $query) {
            $query->published();
        })->where(function (Builder $query) use ($patterns) {
            $this->matchFields($query, ['title_fa', 'title_en', 'organization_fa', 'organization_en', 'description_fa', 'description_en', 'period'], $patterns);
        })->with('resume')->orderBy('sort_order')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('سوابق و مهارت‌ها', 'Experience and skills'), $resumeItems, function ($item) use ($locale) {
            $resumeName = $item->resume->{'name_'.$locale};

            return [$item->{'title_'.$locale}, trim($resumeName.' · '.$item->{'organization_'.$locale}, ' ·'), route('team.show', $item->resume)];
        });

        $menuItems = MenuItem::whereIn('location', ['header', 'footer'])->where('is_active', true)
            ->where(function (Builder $query) use ($patterns) {
                $this->matchFields($query, ['label_fa', 'label_en', 'url'], $patterns);
            })->orderBy('sort_order')->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('پیوندهای منو', 'Menu links'), $menuItems, function ($item) use ($locale) {
            return [$item->labelFor($locale), $item->url, AppLink::resolve($item->url)];
        });

        $slides = Slide::active()->where(function (Builder $query) use ($patterns) {
            $this->matchFields($query, ['title_fa', 'title_en', 'subtitle_fa', 'subtitle_en', 'button_text_fa', 'button_text_en'], $patterns);
        })->take(self::RESULT_LIMIT)->get();
        $this->addGroup($groups, tr('صفحه اصلی', 'Homepage'), $slides, function ($item) use ($locale) {
            return [$item->{'title_'.$locale}, $item->{'subtitle_'.$locale}, AppLink::resolve($item->button_url ?: route('home'))];
        });

        return $groups->filter(function ($group) {
            return $group['items']->isNotEmpty();
        })->values();
    }

    private function patterns(string $term): array
    {
        $term = preg_replace('/\s+/u', ' ', trim(strtr($term, [
            'ي' => 'ی',
            'ك' => 'ک',
            "\u{200c}" => ' ',
        ])));
        $variants = [$term, strtr($term, ['ی' => 'ي', 'ک' => 'ك'])];

        return array_values(array_unique(array_map(function ($variant) {
            return '%'.addcslashes($variant, '%_\\').'%';
        }, $variants)));
    }

    private function matchFields(Builder $query, array $fields, array $patterns): void
    {
        $query->where(function (Builder $nested) use ($fields, $patterns) {
            foreach ($fields as $field) {
                foreach ($patterns as $pattern) {
                    $nested->orWhere($field, 'like', $pattern);
                }
            }
        });
    }

    private function addGroup(Collection $groups, string $label, Collection $records, callable $format): void
    {
        if ($records->isEmpty()) {
            return;
        }

        $groups->push([
            'label' => $label,
            'items' => $records->map(function ($record) use ($format) {
                [$title, $description, $url] = $format($record);

                return [
                    'title' => $title,
                    'description' => Str::limit(trim(strip_tags((string) $description)), 220),
                    'url' => $url,
                ];
            }),
        ]);
    }
}
