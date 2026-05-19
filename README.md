# 👥 Aplikasi Member Komunitas (AMK) — "PBL WOY"

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue Version](https://img.shields.io/badge/Vue.js-3.x-green.svg?style=flat-square&logo=vue.js)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-SPA-purple.svg?style=flat-square)](https://inertiajs.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC.svg?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](https://opensource.org/licenses/MIT)

**Aplikasi Member Komunitas (AMK)** adalah platform manajemen terpadu untuk keanggotaan komunitas digital. Dibangun menggunakan arsitektur **Laravel 11**, **Vue 3 (Composition API)**, dan **Inertia.js**, sistem ini menghadirkan pengalaman navigasi halaman tunggal (*Single Page Application*) yang super responsif, mulus, dan terproteksi oleh otorisasi berbasis peran (RBAC).

---

## 🌟 Fitur Utama & Keunggulan

*   **Pemisahan Peran yang Tegas (RBAC):** Memiliki 5 dashboard khusus yang dipisahkan di tingkat server maupun klien: *Super Admin*, *Ketua (Leader)*, *Petugas (Staff)*, *Bendahara*, dan *Member*.
*   **Akses Materi Pembelajaran Eksklusif:** Member premium dapat mengakses koleksi materi berkualitas berupa **Video Pembelajaran** dan **E-Book** secara terstruktur.
*   **Forum Diskusi & Tiket Q&A:** Hubungan interaktif secara privat/umum antara member dan petugas menggunakan sistem nomor tiket percakapan.
*   **Portal Berita & Kegiatan (Community Blog):** Publikasi konten artikel dinamis yang ditulis oleh petugas menggunakan rich-text WYSIWYG editor.
*   **Laporan Statistik Komprehensif:** Ketua dapat melihat tren pendaftaran anggota dan performa konten lewat visualisasi grafik yang interaktif.
*   **Proses Billing & Invoice Otomatis:** Sistem pembuatan invoice untuk status keanggotaan premium yang dipasangkan dengan alur verifikasi bukti transfer manual oleh Bendahara.

---

## 📸 Panduan Antarmuka & Mockup Desain
Aset screenshot visual orisinal hasil rancangan antarmuka aplikasi ini telah disertakan dalam repositori untuk mempermudah pengerjaan frontend agar 100% presisi:
- 📂 **[PBL WOY](file:///c:/Users/VICTUS/aplikasi-member-komunitas/PBL%20WOY)**: Koleksi desain antarmuka bagi **Super Admin** (Kelola Akun, Detail Akun, Edit Profil, Pengaturan).
- 📂 **[PBL WOY PETUGAS](file:///c:/Users/VICTUS/aplikasi-member-komunitas/PBL%20WOY%20PETUGAS)**: Koleksi desain antarmuka bagi **Petugas / Staff** (Manajemen Blog, Detail Pertanyaan, Grid Konten Ebook/Video, Tulis Blog Baru).

---

## ⚡ Quick Start (Jalankan Cepat)

Untuk memulai pengembangan di komputer lokal Anda, cukup ikuti langkah ringkas di bawah ini:

### 1. Instalasi Dependensi
```bash
# Backend dependencies
composer install

# Frontend dependencies
npm install
```

### 2. Setup File Environment & Key
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database (SQLite)
Pastikan konfigurasi database di `.env` disetel ke SQLite (`DB_CONNECTION=sqlite`), buat berkas kosong database, lalu jalankan migrasi & data seeder:
```bash
# Buat database kosong
touch database/database.sqlite

# Migrasi tabel dan isi data awal
php artisan migrate --seed
```

### 4. Jalankan Aplikasi
Buka dua terminal dan jalankan perintah:
```bash
# Terminal 1 (Backend)
php artisan serve

# Terminal 2 (Frontend Hot Reload)
npm run dev
```
Akses aplikasi melalui browser di **`http://127.0.0.1:8000`**.

---

## 🔑 Kredensial Akun Percobaan (Sandi: `password`)

| Peran (Role) | 📧 Alamat Email | 👤 Nama Default |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@amk.com` | Met Slamet |
| **Ketua (Leader)** | `ketua@amk.com` | Jo Bejo |
| **Petugas (Staff)** | `staff@amk.com` | Agus Haryanto |
| **Bendahara** | `bendahara@amk.com` | Jo Paijo |
| **Anggota (Member)** | `nem@amk.com` | Nem Painem |

---

## 📖 Dokumentasi Lengkap Developer
Untuk dokumentasi yang lebih mendalam mengenai arsitektur sistem, skema database, detail routing, model relasional (ERD), dan tata cara kompilasi ke tahap produksi (*production*), silakan merujuk pada berkas:
👉 **[PROJECT_DOCUMENTATION.md](file:///c:/Users/VICTUS/aplikasi-member-komunitas/PROJECT_DOCUMENTATION.md)**

---

*Hak Cipta © 2026. Proyek Kolaboratif PBL WOY - Aplikasi Member Komunitas.*
