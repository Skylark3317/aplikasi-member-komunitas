<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\KelolAkunController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\RiwayatAktivitasController;
use App\Http\Controllers\Admin\SuperAdminProfilController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────
Route::get('/', HomeController::class)->name('home');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [PostController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');
Route::get('/syarat-ketentuan', function () {
    $setting = \App\Models\Setting::where('key', 'terms_and_conditions')->first();
    return \Inertia\Inertia::render('SyaratKetentuan', [
        'terms_and_conditions' => $setting ? $setting->value : null,
        'last_updated' => $setting ? $setting->updated_at->toISOString() : now()->toISOString(),
    ]);
})->name('syarat-ketentuan');

// ─── Auth Routes (Breeze) ──────────────────────────────────
require __DIR__.'/auth.php';

// ─── Authenticated Routes ──────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Ketua Routes ──────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:ketua'])
    ->prefix('ketua')
    ->name('ketua.')
    ->group(function () {
        Route::get('/statistik', [\App\Http\Controllers\Ketua\StatistikController::class, 'index'])->name('statistik');
        Route::get('/statistik/detail/{type}', [\App\Http\Controllers\Ketua\DetailController::class, 'index'])->name('statistik.detail');
        Route::get('/statistik/detail/{type}/export', [\App\Http\Controllers\Ketua\ExportController::class, 'export'])->name('statistik.detail.export');
        Route::get('/profil', [\App\Http\Controllers\Ketua\ProfilController::class, 'show'])->name('profil');
        Route::get('/profil/edit', [\App\Http\Controllers\Ketua\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [\App\Http\Controllers\Ketua\ProfilController::class, 'update'])->name('profil.update');
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
        Route::delete('/kelol-akun/{user}', [KelolAkunController::class, 'destroy'])->name('kelol-akun.destroy');

        // Pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

        // Profil
        Route::get('/profil', [SuperAdminProfilController::class, 'show'])->name('profil');
        Route::get('/profil/edit', [SuperAdminProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [SuperAdminProfilController::class, 'update'])->name('profil.update');

        // Riwayat Aktivitas
        Route::get('/riwayat-aktivitas', [RiwayatAktivitasController::class, 'index'])->name('riwayat-aktivitas');
        Route::post('/riwayat-aktivitas/{log}/revert', [RiwayatAktivitasController::class, 'revert'])->name('riwayat-aktivitas.revert');

        // Paket Premium
        Route::get('/paket-premium', [\App\Http\Controllers\Admin\PaketPremiumController::class, 'index'])->name('paket-premium.index');
        Route::post('/paket-premium', [\App\Http\Controllers\Admin\PaketPremiumController::class, 'store'])->name('paket-premium.store');
        Route::patch('/paket-premium/{plan}', [\App\Http\Controllers\Admin\PaketPremiumController::class, 'update'])->name('paket-premium.update');
        Route::delete('/paket-premium/{plan}', [\App\Http\Controllers\Admin\PaketPremiumController::class, 'destroy'])->name('paket-premium.destroy');
        Route::patch('/paket-premium/{plan}/toggle-status', [\App\Http\Controllers\Admin\PaketPremiumController::class, 'toggleStatus'])->name('paket-premium.toggle-status');
        Route::patch('/paket-premium/{plan}/toggle-recommended', [\App\Http\Controllers\Admin\PaketPremiumController::class, 'toggleRecommended'])->name('paket-premium.toggle-recommended');
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

        // Member
        Route::get('/member', [\App\Http\Controllers\Petugas\MemberController::class, 'index'])->name('member.index');

        // Profil
        Route::get('/profil', [\App\Http\Controllers\Petugas\ProfilController::class, 'show'])->name('profil');
        Route::get('/profil/edit', [\App\Http\Controllers\Petugas\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [\App\Http\Controllers\Petugas\ProfilController::class, 'update'])->name('profil.update');
    });

// ─── Keuangan Routes ──────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:finance'])
    ->prefix('keuangan')
    ->name('keuangan.')
    ->group(function () {
        // Pembayaran
        Route::get('/pembayaran', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/{payment}', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'show'])->name('pembayaran.show');
        Route::post('/pembayaran/{payment}/verify', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'verify'])->name('pembayaran.verify');
        Route::post('/pembayaran/{payment}/reject', [\App\Http\Controllers\Keuangan\PembayaranController::class, 'reject'])->name('pembayaran.reject');

        // Profil
        Route::get('/profil', [\App\Http\Controllers\Keuangan\ProfilController::class, 'show'])->name('profil');
        Route::get('/profil/edit', [\App\Http\Controllers\Keuangan\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [\App\Http\Controllers\Keuangan\ProfilController::class, 'update'])->name('profil.update');
    });

Route::middleware(['auth', 'verified', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        // Konten
        Route::get('/konten', [\App\Http\Controllers\Member\KontenController::class, 'index'])->name('konten.index');

        // Profil
        Route::get('/profil', [\App\Http\Controllers\Member\ProfilController::class, 'show'])->name('profil.show');
        Route::get('/profil/edit', [\App\Http\Controllers\Member\ProfilController::class, 'edit'])->name('profil.edit');
        Route::match(['patch', 'post'], '/profil', [\App\Http\Controllers\Member\ProfilController::class, 'update'])->name('profil.update');

        // Premium & Pembayaran
        Route::get('/premium', [\App\Http\Controllers\Member\PremiumController::class, 'index'])->name('premium.index');
        Route::post('/premium/gabung', [\App\Http\Controllers\Member\PremiumController::class, 'join'])->name('premium.join');
        Route::get('/premium/pembayaran', [\App\Http\Controllers\Member\PremiumController::class, 'paymentIndex'])->name('premium.payment');
        Route::get('/premium/pembayaran/{invoice}', [\App\Http\Controllers\Member\PremiumController::class, 'paymentDetail'])->name('premium.payment_detail');
        Route::delete('/premium/pembayaran/{invoice}', [\App\Http\Controllers\Member\PremiumController::class, 'cancelInvoice'])->name('premium.cancel_invoice');
        Route::post('/premium/bayar', [\App\Http\Controllers\Member\PremiumController::class, 'pay'])->name('premium.pay');

        // Pertanyaan (Q&A)
        Route::get('/pertanyaan', [\App\Http\Controllers\Member\PertanyaanController::class, 'index'])->name('pertanyaan.index');
        Route::get('/pertanyaan/{conversation}', [\App\Http\Controllers\Member\PertanyaanController::class, 'show'])->name('pertanyaan.show');
        Route::post('/pertanyaan/{conversation}/balas', [\App\Http\Controllers\Member\PertanyaanController::class, 'reply'])->name('pertanyaan.reply');

        // Hapus Akun
        Route::post('/hapus-akun', [\App\Http\Controllers\Member\HapusAkunController::class, 'request'])->name('hapus-akun.request');
        Route::delete('/hapus-akun', [\App\Http\Controllers\Member\HapusAkunController::class, 'cancel'])->name('hapus-akun.cancel');
    });
