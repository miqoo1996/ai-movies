<?php

use App\Http\Controllers\Admin\ShowController as AdminShowController;
use App\Http\Controllers\AdminController;
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
        Route::delete('shows/{show}/images/{image}', [AdminShowController::class, 'destroyImage'])->name('shows.images.destroy');
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
Route::get('/contact', [MainController::class, 'contact'])->name('contact');
