<?php

use App\Http\Controllers\Admin\ContactSubmissionController as AdminContactController;
use App\Http\Controllers\Admin\EpisodeController as AdminEpisodeController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ShowController as AdminShowController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SocialLinkController as AdminSocialLinkController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ShowController;
use Illuminate\Support\Facades\Route;

// ── Admin ────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',  [AdminController::class, 'loginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware('admin.auth')->group(function () {
        Route::get('/',       [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');

        Route::resource('shows', AdminShowController::class)->names('shows');
        Route::post('faqs/reorder', [AdminFaqController::class, 'reorder'])->name('faqs.reorder');
        Route::resource('faqs', AdminFaqController::class)->names('faqs')->except(['show']);
        Route::get('settings',  [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');

        Route::post('social-links',                    [AdminSocialLinkController::class, 'store'])->name('social-links.store');
        Route::put('social-links/{socialLink}',        [AdminSocialLinkController::class, 'update'])->name('social-links.update');
        Route::patch('social-links/{socialLink}/toggle',[AdminSocialLinkController::class, 'toggle'])->name('social-links.toggle');
        Route::delete('social-links/{socialLink}',     [AdminSocialLinkController::class, 'destroy'])->name('social-links.destroy');

        Route::get('contact',                          [AdminContactController::class, 'index'])->name('contact.index');
        Route::get('contact/{submission}',             [AdminContactController::class, 'show'])->name('contact.show');
        Route::patch('contact/{submission}/mark-read', [AdminContactController::class, 'markRead'])->name('contact.mark-read');
        Route::delete('contact/{submission}',          [AdminContactController::class, 'destroy'])->name('contact.destroy');

        Route::get('admins',                        [AdminUserController::class, 'index'])->name('admins.index');
        Route::post('admins',                       [AdminUserController::class, 'store'])->name('admins.store');
        Route::delete('admins/{user}',              [AdminUserController::class, 'destroy'])->name('admins.destroy');
        Route::post('admins/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admins.reset-password');

        Route::get('pages',              [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('pages/{page}',       [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}',       [AdminPageController::class, 'update'])->name('pages.update');
        Route::post('pages/{page}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
        Route::delete('shows/{show}/images/{image}', [AdminShowController::class, 'destroyImage'])->name('shows.images.destroy');

        Route::prefix('shows/{show}/episodes')->name('shows.episodes.')->group(function () {
            Route::get('/',              [AdminEpisodeController::class, 'index'])->name('index');
            Route::get('/create',        [AdminEpisodeController::class, 'create'])->name('create');
            Route::post('/',             [AdminEpisodeController::class, 'store'])->name('store');
            Route::get('/{episode}/edit',[AdminEpisodeController::class, 'edit'])->name('edit');
            Route::put('/{episode}',     [AdminEpisodeController::class, 'update'])->name('update');
            Route::delete('/{episode}',  [AdminEpisodeController::class, 'destroy'])->name('destroy');
        });
    });
});
// ─────────────────────────────────────────────────────────────────

Route::get('/',           [ShowController::class, 'home']);
Route::get('/shows',      [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows/{slug}', [ShowController::class, 'show'])->name('shows.show');
Route::get('/calendar',   [ShowController::class, 'calendar'])->name('calendar');

Route::get('/faq',     [MainController::class, 'faq'])->name('faq');
Route::get('/terms',   [MainController::class, 'terms'])->name('terms');
Route::get('/privacy', [MainController::class, 'privacy'])->name('privacy');
Route::get('/contact',  [MainController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
