<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\TicketController as AccountTicketController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\SlideController as AdminSlideController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// Public site
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator.index');
Route::post('/calculator/estimate', [CalculatorController::class, 'estimate'])->middleware('throttle:30,1')->name('calculator.estimate');
Route::post('/calculator/quote', [CalculatorController::class, 'requestQuote'])->middleware('throttle:5,1')->name('calculator.quote');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/blog', [ArticleController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [ArticleController::class, 'show'])->name('blog.show');
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{slug}', [VideoController::class, 'show'])->name('videos.show');
Route::get('/academy', [AcademyController::class, 'index'])->name('academy.index');
Route::get('/academy/{course}', [AcademyController::class, 'show'])->name('academy.show');
Route::get('/academy/{course}/lessons/{lesson}', [AcademyController::class, 'lesson'])->name('academy.lesson');
Route::get('/academy/lessons/{lesson}/stream', [AcademyController::class, 'stream'])->name('academy.stream');
Route::get('/contact', [LeadController::class, 'create'])->name('contact');
Route::post('/contact', [LeadController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::get('/lang/{locale}', [LocaleController::class, 'update'])->name('locale.update');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');

// Authentication (customers and staff share one login; the redirect depends on the role)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1')->name('register.store');
});
// Older builds posted the login form to /admin/login. Keep that URL working with the same login logic.
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Learner actions
Route::middleware(['auth'])->group(function () {
    Route::post('/academy/{course}/enroll', [AcademyController::class, 'enroll'])->name('academy.enroll');
    Route::post('/academy/lessons/{lesson}/complete', [AcademyController::class, 'complete'])->name('academy.complete');
});

// Customer panel
Route::prefix('account')->name('account.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
    Route::get('/invoices', [AccountController::class, 'invoices'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [AccountController::class, 'showInvoice'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [PaymentController::class, 'online'])->middleware('throttle:10,1')->name('invoices.pay');
    Route::post('/invoices/{invoice}/receipt', [PaymentController::class, 'receipt'])->middleware('throttle:10,1')->name('invoices.receipt');
    Route::get('/courses', [AccountController::class, 'courses'])->name('courses');
    Route::get('/tickets', [AccountTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [AccountTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [AccountTicketController::class, 'store'])->middleware('throttle:10,1')->name('tickets.store');
    Route::get('/tickets/{ticket}', [AccountTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AccountTicketController::class, 'reply'])->middleware('throttle:20,1')->name('tickets.reply');
    Route::get('/tickets/messages/{message}/attachment', [AccountTicketController::class, 'attachment'])->name('tickets.attachment');
});

// Admin panel (staff only; roles are checked per group)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,content,sales'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leads', [AdminLeadController::class, 'index'])->middleware('role:admin,sales')->name('leads.index');
    Route::patch('/leads/{lead}', [AdminLeadController::class, 'update'])->middleware('role:admin,sales')->name('leads.update');

    Route::middleware('role:admin,sales')->group(function () {
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::post('/orders/{order}/invoices', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
        Route::patch('/invoices/{invoice}', [AdminInvoiceController::class, 'update'])->name('invoices.update');
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
        Route::get('/payments/{payment}/receipt', [AdminPaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->middleware('throttle:30,1')->name('tickets.reply');
        Route::get('/tickets/messages/{message}/attachment', [AdminTicketController::class, 'attachment'])->name('tickets.attachment');
        Route::patch('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    });

    Route::middleware('role:admin,content')->group(function () {
        Route::get('/catalog/{type}', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{type}/create', [CatalogController::class, 'create'])->name('catalog.create');
        Route::post('/catalog/{type}', [CatalogController::class, 'store'])->name('catalog.store');
        Route::get('/catalog/{type}/{id}/edit', [CatalogController::class, 'edit'])->name('catalog.edit');
        Route::put('/catalog/{type}/{id}', [CatalogController::class, 'update'])->name('catalog.update');
        Route::delete('/catalog/{type}/{id}', [CatalogController::class, 'destroy'])->name('catalog.destroy');

        Route::get('/content/{type}', [ContentController::class, 'index'])->name('content.index');
        Route::get('/content/{type}/create', [ContentController::class, 'create'])->name('content.create');
        Route::post('/content/{type}', [ContentController::class, 'store'])->name('content.store');
        Route::get('/content/{type}/{id}/edit', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/content/{type}/{id}', [ContentController::class, 'update'])->name('content.update');
        Route::delete('/content/{type}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');

        Route::resource('/slides', AdminSlideController::class)->except(['show'])->names('slides');

        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/courses/{course}/lessons', [AdminCourseController::class, 'lessons'])->name('courses.lessons');
        Route::post('/courses/{course}/lessons', [AdminCourseController::class, 'storeLesson'])->name('courses.lessons.store');
        Route::get('/courses/{course}/lessons/{lesson}/edit', [AdminCourseController::class, 'editLesson'])->name('courses.lessons.edit');
        Route::put('/courses/{course}/lessons/{lesson}', [AdminCourseController::class, 'updateLesson'])->name('courses.lessons.update');
        Route::delete('/courses/{course}/lessons/{lesson}', [AdminCourseController::class, 'destroyLesson'])->name('courses.lessons.destroy');
        Route::get('/discounts', [AdminCourseController::class, 'discounts'])->name('discounts.index');
        Route::post('/discounts', [AdminCourseController::class, 'storeDiscount'])->name('discounts.store');
        Route::patch('/discounts/{discount}', [AdminCourseController::class, 'toggleDiscount'])->name('discounts.toggle');
        Route::delete('/discounts/{discount}', [AdminCourseController::class, 'destroyDiscount'])->name('discounts.destroy');
    });
});
