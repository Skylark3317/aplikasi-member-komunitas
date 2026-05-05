<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\KelolAkunController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\SuperAdminProfilController;
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

// ─── Super Admin Routes ────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        // Kelola Akun
        Route::get('/kelol-akun', [KelolAkunController::class, 'index'])->name('kelol-akun.index');
        Route::get('/kelol-akun/buat', [KelolAkunController::class, 'create'])->name('kelol-akun.create');
        Route::post('/kelol-akun', [KelolAkunController::class, 'store'])->name('kelol-akun.store');
        Route::get('/kelol-akun/{user}', [KelolAkunController::class, 'show'])->name('kelol-akun.show');
        Route::patch('/kelol-akun/{user}/toggle-status', [KelolAkunController::class, 'toggleStatus'])->name('kelol-akun.toggle-status');

        // Pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

        // Profil
        Route::get('/profil', [SuperAdminProfilController::class, 'show'])->name('profil');
        Route::get('/profil/edit', [SuperAdminProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [SuperAdminProfilController::class, 'update'])->name('profil.update');
    });
