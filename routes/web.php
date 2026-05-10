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

// ─── Petugas Routes ────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:staff'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        // Konten
        Route::get('/konten', [\App\Http\Controllers\Petugas\KontenController::class, 'index'])->name('konten.index');
        Route::get('/konten/buat', [\App\Http\Controllers\Petugas\KontenController::class, 'create'])->name('konten.create');
        Route::post('/konten', [\App\Http\Controllers\Petugas\KontenController::class, 'store'])->name('konten.store');
        Route::get('/konten/{content}/edit', [\App\Http\Controllers\Petugas\KontenController::class, 'edit'])->name('konten.edit');
        Route::post('/konten/{content}', [\App\Http\Controllers\Petugas\KontenController::class, 'update'])->name('konten.update');
        Route::delete('/konten/{content}', [\App\Http\Controllers\Petugas\KontenController::class, 'destroy'])->name('konten.destroy');

        // Blog
        Route::get('/blog', [\App\Http\Controllers\Petugas\BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/buat', [\App\Http\Controllers\Petugas\BlogController::class, 'create'])->name('blog.create');
        Route::post('/blog', [\App\Http\Controllers\Petugas\BlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [\App\Http\Controllers\Petugas\BlogController::class, 'edit'])->name('blog.edit');
        Route::patch('/blog/{post}', [\App\Http\Controllers\Petugas\BlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [\App\Http\Controllers\Petugas\BlogController::class, 'destroy'])->name('blog.destroy');

        // Pertanyaan
        Route::get('/pertanyaan', [\App\Http\Controllers\Petugas\PertanyaanController::class, 'index'])->name('pertanyaan.index');
        Route::get('/pertanyaan/{conversation}', [\App\Http\Controllers\Petugas\PertanyaanController::class, 'show'])->name('pertanyaan.show');
        Route::post('/pertanyaan/{conversation}/balas', [\App\Http\Controllers\Petugas\PertanyaanController::class, 'reply'])->name('pertanyaan.reply');

        // Profil
        Route::get('/profil', [\App\Http\Controllers\Petugas\ProfilController::class, 'show'])->name('profil');
        Route::get('/profil/edit', [\App\Http\Controllers\Petugas\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [\App\Http\Controllers\Petugas\ProfilController::class, 'update'])->name('profil.update');
    });
