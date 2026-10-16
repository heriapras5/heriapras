<?php

use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TimelineItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/projects/{slug}', [FrontendController::class, 'portfolioDetail'])->name('projects.detail');
Route::get('/portfolio/{slug}', function ($slug) {
    return redirect(route('projects.detail', $slug), 301);
});
Route::redirect('/projects', '/#portfolio')->name('projects');
Route::redirect('/portfolio', '/#portfolio')->name('portfolio');
Route::redirect('/skills', '/#skills')->name('skills');
Route::redirect('/layanan', '/#portfolio');
Route::redirect('/paket', '/#portfolio');
Route::redirect('/tentang', '/#about')->name('about');
Route::redirect('/tentang-kami', '/#about');
Route::redirect('/faq', '/#contact');
Route::redirect('/kontak', '/#contact')->name('contact');
Route::get('/favicon.png', [FrontendController::class, 'favicon'])->name('favicon');
Route::get('/og-image.png', [FrontendController::class, 'ogImage'])->name('og-image');

// CV publik (ID + EN): unduhan PDF dinamis + pratinjau HTML siap cetak
Route::get('/cv', [CvController::class, 'downloadId'])->name('cv');
Route::get('/cv/en', [CvController::class, 'downloadEn'])->name('cv.en');
Route::get('/cv/preview', [CvController::class, 'previewId'])->name('cv.preview');
Route::get('/cv/en/preview', [CvController::class, 'previewEn'])->name('cv.en.preview');

Route::post('/kontak/kirim', [FrontendController::class, 'sendMessage'])->middleware('throttle:10,1')->name('contact.send');

// Auth
Route::get('/admin', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }

    return view('admin.login');
})->name('login');

Route::post('/admin', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/status/toggle', [StatusController::class, 'toggle'])->name('status.toggle');
    Route::resource('hero-sections', HeroSectionController::class);
    Route::resource('about-sections', AboutSectionController::class);
    Route::resource('timeline', TimelineItemController::class)->except(['show']);
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('project-categories', ProjectCategoryController::class);
    Route::delete('projects/{project}/images/{image}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
    Route::resource('projects', ProjectController::class);
    Route::resource('messages', MessageController::class)->except(['create', 'store', 'edit', 'update']);
    Route::patch('messages/{message}/toggle-read', [MessageController::class, 'toggleRead'])->name('messages.toggle-read');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/seo', [SeoController::class, 'index'])->name('seo.index');
    Route::put('/seo', [SeoController::class, 'update'])->name('seo.update');
    Route::resource('users', UserController::class)->middleware('super_admin');
});
