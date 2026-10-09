<?php

use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/contact', [LeadController::class, 'create'])->name('contact');
Route::post('/contact', [LeadController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::get('/lang/{locale}', [LocaleController::class, 'update'])->name('locale.update');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,content,sales'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leads', [AdminLeadController::class, 'index'])->middleware('role:admin,sales')->name('leads.index');
    Route::patch('/leads/{lead}', [AdminLeadController::class, 'update'])->middleware('role:admin,sales')->name('leads.update');
    Route::get('/catalog/{type}', [CatalogController::class, 'index'])->middleware('role:admin,content')->name('catalog.index');
    Route::get('/catalog/{type}/create', [CatalogController::class, 'create'])->middleware('role:admin,content')->name('catalog.create');
    Route::post('/catalog/{type}', [CatalogController::class, 'store'])->middleware('role:admin,content')->name('catalog.store');
    Route::get('/catalog/{type}/{id}/edit', [CatalogController::class, 'edit'])->middleware('role:admin,content')->name('catalog.edit');
    Route::put('/catalog/{type}/{id}', [CatalogController::class, 'update'])->middleware('role:admin,content')->name('catalog.update');
    Route::delete('/catalog/{type}/{id}', [CatalogController::class, 'destroy'])->middleware('role:admin,content')->name('catalog.destroy');
});
