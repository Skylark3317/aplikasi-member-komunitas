# Project Documentation

## 1. Project Overview
- **Nama Project**: PBL WOY / Aplikasi Member Komunitas
- **Tujuan Project**: Sistem manajemen anggota komunitas terpadu yang memfasilitasi akses materi (video/ebook), forum tanya jawab (Q&A), blog komunitas, sistem keanggotaan berbayar (premium), serta dashboard untuk pengurus (Super Admin, Ketua, Petugas, Bendahara, Member).
- **Deskripsi Singkat**: Aplikasi ini dibangun menggunakan stack modern (Laravel 11 dan Vue 3 dengan Inertia.js). Bertujuan untuk memberikan pengalaman *Single Page Application* (SPA) yang cepat dengan sistem *role-based access control* (RBAC) yang lengkap untuk mengatur konten, pembayaran, dan interaksi antar anggota.

## 2. Tech Stack
- **Frontend**: Vue 3 (Composition API), Inertia.js `@inertiajs/vue3`
- **Backend**: Laravel 11.x (PHP ^8.3)
- **Database**: SQLite (Default `.env`, dapat dikonfigurasi ke MySQL/PostgreSQL)
- **Styling**: Tailwind CSS 3.x, `@tailwindcss/forms`
- **Build Tools**: Vite 8.x, `@vitejs/plugin-vue`, Laravel Vite Plugin
- **Deployment**: Dapat di-deploy di VPS standar atau shared hosting dengan akses SSH, menggunakan `php artisan serve` & `npm run build`.
- **Icon Pack**: Bootstrap Icons (`bootstrap-icons`)
- **State Management**: Dikelola secara server-driven melalui Inertia.js (Props & Shared Data)
- **Utilities**: 
  - Rich Text Editor: Vue Quill (`@vueup/vue-quill`)
  - Charts: Chart.js & Vue Chart.js (`chart.js`, `vue-chartjs`)

## 3. Project Structure
Struktur utama project mengikuti standar Laravel + Inertia.js:
- `app/`: Berisi logika backend (Controllers, Models, Middleware).
  - `Http/Controllers/`: Terbagi menjadi sub-folder berdasarkan Role (`Admin`, `Bendahara`, `Petugas`, `Auth`).
  - `Models/`: Mendefinisikan schema Eloquent (User, Content, Payment, Invoice, Post, dll).
- `bootstrap/app.php`: Titik masuk utama aplikasi (konfigurasi routing dan middleware untuk Laravel 11).
- `database/`: Berisi struktur database, migrations, factories, dan seeders.
- `public/`: Direktori yang dapat diakses publik (entry point `index.php`, asset statis).
- `resources/`: 
  - `js/Pages/`: Komponen Vue halaman per role (Admin, Bendahara, Petugas, Auth, Blog, Profile).
  - `js/Components/`: Komponen Vue reusable (Button, Modal, TextInput, RichTextEditor).
  - `js/Layouts/`: Layout utama (misal: AppLayout, MemberLayout).
- `routes/`: Menyimpan deklarasi route (`web.php` untuk Web dan Inertia routes, auth rute).

## 4. Important Files
- `composer.json` & `package.json`: Daftar dependencies backend (PHP) dan frontend (Node.js).
- `bootstrap/app.php`: File registrasi utama untuk routing, middleware (`HandleInertiaRequests`, `CheckRole`), dan exception handling di Laravel 11.
- `routes/web.php`: Berisi seluruh routing logika aplikasi, dikelompokkan dengan middleware `role`.
- `tailwind.config.js`: Konfigurasi Tailwind, men-scan folder `resources/js/**/*.vue` dan `resources/views`.
- `vite.config.js`: Konfigurasi build process asset frontend.

## 5. Routing System
Sistem routing didefinisikan secara tersentralisasi di `routes/web.php` dan dibagi menjadi beberapa block middleware:
- **Public Routes**: Homepage (`/`) dan Blog (`/blog`).
- **Auth Routes**: Sistem login, register, reset password bawaan Laravel Breeze (`auth.php`).
- **Super Admin Routes** (`/superadmin`): Mengelola akun dan pengaturan web.
- **Admin/Ketua Routes** (`/admin`): Mengakses halaman statistik/dashboard.
- **Petugas/Staff Routes** (`/petugas`): Manajemen konten, blog, dan membalas pertanyaan member.
- **Bendahara Routes** (`/bendahara`): Verifikasi pembayaran dan invoice.
- Setiap role dilindungi oleh custom middleware `role:nama_role`.

## 6. Authentication & Authorization
- **Authentication**: Menggunakan **Laravel Breeze** (Inertia + Vue stack). Menangani Login, Registration, Password Reset, dan Email Verification.
- **Authorization**: Menggunakan custom Middleware `CheckRole` yang didaftarkan sebagai alias `role` di `bootstrap/app.php`. Role yang terdeteksi antara lain: `super_admin`, `ketua`, `staff` (petugas), `bendahara`, dan `member` biasa.

## 7. Database Structure
Aplikasi ini memiliki beberapa model/tabel utama:
- `users`: Data pengguna dan role mereka (`is_active` flag).
- `member_profiles`: Data profil khusus untuk para member.
- `categories`: Kategori untuk konten/blog.
- `contents`: Menyimpan informasi video atau ebook.
- `posts` / `blogs`: Untuk publikasi blog.
- `payments` & `invoices`: Mengelola transaksi keanggotaan/premium.
- `conversations` & `messages`: Sistem Q&A atau forum komunikasi antara member dan petugas.
- `settings`: Konfigurasi aplikasi yang bisa diubah Super Admin.

## 8. API Structure
Saat ini aplikasi tidak difokuskan sebagai REST API murni melainkan sebagai **SPA dengan Inertia.js**. Response dari server langsung di-render menjadi komponen Vue beserta data JSON prop-nya. Tidak ada file `routes/api.php` yang aktif dalam arsitektur default saat ini.

## 9. Components Architecture
Aplikasi mengadopsi struktur *Atomic Design/Reusable Components*:
- Komponen dasar (UI Toolkit) diletakkan di `resources/js/Components/` (ex: `PrimaryButton.vue`, `Modal.vue`, `RichTextEditor.vue`, `TextInput.vue`).
- Komponen layout diletakkan di `resources/js/Layouts/`.
- Halaman diletakkan di `resources/js/Pages/` dikelompokkan per Role agar rapi.
Semua komponen ditulis dengan `<script setup>` (Vue 3 Composition API).

## 10. State Management
Tidak menggunakan library external seperti Vuex atau Pinia. State management dikelola oleh **Inertia.js**.
- Data Global (seperti Info User yang login, flash message, pengaturan) di-share via `HandleInertiaRequests.php` middleware.
- Data Page-specific dikirim langsung dari Controller sebagai props ke page komponen Vue.

## 11. UI/Design System
- **UI Framework**: Tailwind CSS
- **Icon Pack**: Bootstrap Icons
- **Theme System**: Bergantung pada utility classes Tailwind CSS (terlihat dari konfigurasi form di `tailwind.config.js`).
- **Styling Approach**: Utility-first CSS dengan beberapa custom CSS di `resources/css/app.css` jika diperlukan. Design sangat menekankan pada clean UI yang modern.

## 12. Environment Configuration
Konfigurasi `ENV` memuat variabel esensial Laravel tanpa membocorkan secret:
- `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`
- `DB_CONNECTION=sqlite` (Secara default menggunakan SQLite untuk development yang praktis).
- `VITE_APP_NAME`: Untuk injeksi nama aplikasi ke Vue.
- Tidak ada credentials sensitive (seperti password DB atau payment gateway secret) yang tersimpan di `.env.example`, yang mana ini sudah sesuai best-practice.

## 13. Build & Run Instructions
**Development Setup:**
1. `composer install`
2. `npm install`
3. Copy `.env.example` ke `.env` dan jalankan `php artisan key:generate`
4. Sesuaikan konfigurasi database, lalu jalankan `php artisan migrate --seed`
5. Buka dua terminal, jalankan:
   - `php artisan serve`
   - `npm run dev`

**Production Setup:**
1. Jalankan `npm run build` untuk meng-compile asset Vue dan Tailwind.
2. Pastikan `APP_ENV=production` dan `APP_DEBUG=false`.
3. Konfigurasi web server (Nginx/Apache) untuk mengarah ke folder `public/`.

## 14. Deployment
Aplikasi siap untuk dideploy pada target Hosting konvensional (Shared Hosting dengan SSH) atau VPS (DigitalOcean, AWS, dll). Diperlukan Node.js di server hanya untuk membuild asset (`npm run build`), dan PHP ^8.3 untuk menjalankan logic backend.

## 15. Third Party Services
- **Chart.js**: Untuk rendering statistik dan chart di halaman dashboard Ketua/Admin.
- **Vue Quill**: Untuk Rich Text Editor di halaman pembuatan konten/blog.

## 16. Security Notes
- Proteksi CSRF otomatis dilindungi oleh Laravel + Inertia Axios middleware.
- Autentikasi aman via session Laravel dan hashing Bcrypt (Breeze default).
- Route Role terlindungi dengan baik menggunakan Custom Middleware.
- Terdapat flag `is_active` pada User untuk me-nonaktifkan akun jika ada pelanggaran.

## 17. Performance Notes
- Menggunakan Vite memangkas waktu load aset saat development dan mengoptimalkan bundle di production.
- Menggunakan pendekatan SPA dengan Inertia.js sehingga tidak ada page reload (full refresh) ketika bernavigasi, membuat UI terasa sangat instan.
- *Rekomendasi:* Gunakan `php artisan optimize` di production dan pastikan database di-index dengan benar pada tabel dengan trafik tinggi (`messages`, `payments`).

## 18. Issues & Recommendations
- **Sistem Database**: Saat ini `.env.example` menggunakan SQLite, yang ideal untuk development. Untuk production multi-user, direkomendasikan migrasi ke MySQL atau PostgreSQL untuk menangani concurrent writes yang lebih baik (terutama untuk sistem chat/Q&A).
- **Penggunaan Storage**: Jika banyak member upload bukti pembayaran/avatar, pastikan mengubah `FILESYSTEM_DISK=public` dan jalankan `php artisan storage:link`.
- **Sistem Notifikasi**: Belum terlihat implementasi real-time WebSockets (seperti Laravel Reverb atau Pusher). Untuk Q&A/Forum dan status Pembayaran, WebSockets akan sangat menambah kesan *premium* dan *live*.
- **Code Refactor**: Periksa file controllers di folder `Petugas` dan `Bendahara` untuk melihat apakah ada logic yang bisa dipindah ke *Service Layer* agar controller tetap tipis.

## 19. Dependency Analysis
- **`laravel/framework` (^11.0 / 13.0 dev)**: Core backend.
- **`inertiajs/inertia-laravel` & `@inertiajs/vue3`**: Jembatan Vue dan Laravel.
- **`vue` (^3.4.0)**: Core framework frontend.
- **`tailwindcss` & `@tailwindcss/vite`**: Styling CSS framework yang mudah dan modern.
- **`@vueup/vue-quill`**: Komponen krusial untuk mengisi deskripsi ebook, video, dan blog post secara profesional.
- **`vue-chartjs`**: Dibutuhkan untuk halaman `/admin/statistik`.

## 20. Conclusion
Aplikasi **PBL WOY / Aplikasi Member Komunitas** adalah platform komprehensif yang telah menggunakan arsitektur modern (Laravel 11 + Vue 3 SPA). Struktur foldernya rapi, pemisahan *Role* dilakukan secara tegas di level Controller, Route, dan Vue Pages, serta penggunakan komponen Vue (*atomic*) sudah mengikuti standar *best practice*. Dengan penyesuaian minor untuk production (seperti database engine dan caching statis), sistem ini sangat solid, secure, dan scalable.
