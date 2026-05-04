<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

// ─── Auth Routes (Breeze) ──────────────────────────────────
require __DIR__.'/auth.php';

// ─── Authenticated Routes ──────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin Routes (Ketua only) ─────────────────────────────
Route::middleware(['auth', 'verified', 'role:ketua'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    });
