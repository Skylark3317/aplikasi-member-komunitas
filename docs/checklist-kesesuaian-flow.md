# ✅ Checklist Kesesuaian Flow Web dengan System Analysis & Design
### Proyek: Aplikasi Member Komunitas (AMK) — PBL WOY
> **Teknologi:** Laravel 11 · Vue 3 · Inertia.js · Tailwind CSS  
> **Tanggal Review:** 22 Mei 2026  
> **Reviewer:** Tim Pengembang PBL WOY

---

## 📌 Keterangan Status

| Simbol | Keterangan |
|:---:|:---|
| ✅ | Sudah diimplementasikan & sesuai desain |
| ⚠️ | Diimplementasikan, namun ada perbedaan minor |
| ❌ | Belum diimplementasikan / tidak sesuai desain |

---

## 1. 🔐 Alur Autentikasi (Authentication Flow)

| No | Fitur / Alur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 1.1 | Halaman Login dengan form email & password | ✅ | `Pages/Auth/Login.vue` | Menggunakan Laravel Breeze |
| 1.2 | Halaman Register untuk pendaftaran member baru | ✅ | `Pages/Auth/Register.vue` | Role default: `member` |
| 1.3 | Lupa Password (Forgot Password) & kirim link reset via email | ✅ | `Pages/Auth/ForgotPassword.vue` | Menggunakan `Auth/RegisteredUserController.php` |
| 1.4 | Reset Password dengan token dari email | ✅ | `Pages/Auth/ResetPassword.vue` | Token-based reset |
| 1.5 | Verifikasi Email setelah registrasi | ✅ | `Pages/Auth/VerifyEmail.vue` | Middleware `verified` aktif di semua rute |
| 1.6 | Redirect otomatis ke dashboard sesuai role setelah login | ✅ | `Auth/AuthenticatedSessionController.php` | Redirect berdasarkan nilai kolom `role` di tabel `users` |
| 1.7 | Logout & invalidasi sesi | ✅ | `AuthenticatedSessionController.php` | Menggunakan `Auth::logout()` standar Laravel |

---

## 2. 🛡️ Kontrol Akses Berbasis Peran (RBAC)

| No | Aturan Akses yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 2.1 | Middleware pengecekan role pada setiap grup rute | ✅ | `Middleware/CheckRole.php` | Menggunakan `role:nama_role` |
| 2.2 | Rute Super Admin hanya bisa diakses `super_admin` | ✅ | `routes/web.php` (baris 41–60) | Prefix `/superadmin` |
| 2.3 | Rute Ketua hanya bisa diakses role `ketua` | ✅ | `routes/web.php` (baris 28–38) | Prefix `/ketua` |
| 2.4 | Rute Petugas hanya bisa diakses role `staff` | ✅ | `routes/web.php` (baris 63–92) | Prefix `/petugas` |
| 2.5 | Rute Keuangan hanya bisa diakses role `finance` | ✅ | `routes/web.php` (baris 95–109) | Prefix `/keuangan` |
| 2.6 | Rute Member hanya bisa diakses role `member` | ✅ | `routes/web.php` (baris 111–137) | Prefix `/member` |
| 2.7 | Akses ditolak (403 Forbidden) jika role tidak sesuai | ✅ | `Middleware/CheckRole.php` | Return HTTP 403 |
| 2.8 | Fitur aktifasi/deaktivasi akun user oleh Super Admin | ✅ | `Admin/KelolAkunController.php` | Kolom `is_active` pada tabel `users` |

---

## 3. 👑 Modul Super Admin

| No | Fitur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 3.1 | Daftar seluruh akun pengurus (Kelola Akun) | ✅ | `Pages/Admin/KelolAkun.vue` | Controller: `Admin/KelolAkunController@index` |
| 3.2 | Buat akun pengurus baru | ✅ | `Pages/Admin/BuatAkunBaru.vue` | Controller: `Admin/KelolAkunController@create` + `@store` |
| 3.3 | Lihat detail akun pengurus | ✅ | `Pages/Admin/DetailAkun.vue` | Controller: `Admin/KelolAkunController@show` |
| 3.4 | Toggle aktif/nonaktif akun pengurus | ✅ | `Pages/Admin/DetailAkun.vue` | Controller: `Admin/KelolAkunController@toggleStatus` |
| 3.5 | Pengaturan sistem (nama komunitas, logo, banner) | ✅ | `Pages/Admin/Pengaturan.vue` | Controller: `Admin/PengaturanController` |
| 3.6 | Lihat & edit profil pribadi Super Admin | ✅ | `Pages/Admin/Profil.vue` + `EditProfil.vue` | Controller: `Admin/SuperAdminProfilController` |

---

## 4. 📊 Modul Ketua (Leader)

| No | Fitur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 4.1 | Dashboard statistik (grafik bar & doughnut) | ✅ | `Pages/Ketua/Statistik.vue` | Controller: `Ketua/StatistikController@index` |
| 4.2 | Visualisasi data keuangan & keanggotaan | ✅ | `Pages/Ketua/StatCard.vue` | Menggunakan `vue-chartjs` |
| 4.3 | Halaman detail statistik per kategori (member, konten, dll) | ✅ | `Pages/Ketua/Detail.vue` | Controller: `Ketua/DetailController@index` |
| 4.4 | Export data statistik ke spreadsheet | ✅ | `routes/web.php` (baris 34) | Controller: `Ketua/ExportController@export` |
| 4.5 | Lihat & edit profil pribadi Ketua | ✅ | `Pages/Ketua/Profil/` | Controller: `Ketua/ProfilController` |

---

## 5. 📝 Modul Petugas (Staff)

| No | Fitur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 5.1 | Daftar konten (Video & E-Book) | ✅ | `Pages/Petugas/Konten/Index.vue` | Controller: `Petugas/KontenController@index` |
| 5.2 | Upload konten baru (Video / E-Book) | ✅ | `Pages/Petugas/Konten/Create.vue` | Controller: `@create` + `@store` |
| 5.3 | Edit konten yang sudah ada | ✅ | `Pages/Petugas/Konten/Edit.vue` | Controller: `@edit` + `@update` |
| 5.4 | Hapus konten | ✅ | `Pages/Petugas/Konten/Index.vue` | Controller: `@destroy`, method DELETE |
| 5.5 | Daftar artikel blog | ✅ | `Pages/Petugas/Blog/Index.vue` | Controller: `Petugas/BlogController@index` |
| 5.6 | Buat artikel blog baru (dengan Quill WYSIWYG editor) | ✅ | `Pages/Petugas/Blog/Create.vue` | Menggunakan `@vueup/vue-quill` |
| 5.7 | Edit artikel blog | ✅ | `Pages/Petugas/Blog/Edit.vue` | Controller: `@edit` + `@update` |
| 5.8 | Hapus artikel blog | ✅ | `Pages/Petugas/Blog/Index.vue` | Controller: `@destroy`, method DELETE |
| 5.9 | Daftar pertanyaan member (tiket forum) | ✅ | `Pages/Petugas/Pertanyaan/Index.vue` | Controller: `Petugas/PertanyaanController@index` |
| 5.10 | Lihat detail percakapan pertanyaan member | ✅ | `Pages/Petugas/Pertanyaan/Show.vue` | Controller: `@show` |
| 5.11 | Balas pertanyaan member | ✅ | `Pages/Petugas/Pertanyaan/Show.vue` | Controller: `@reply`, POST `/balas` |
| 5.12 | Lihat & edit profil pribadi Petugas | ✅ | `Pages/Petugas/Profil/` | Controller: `Petugas/ProfilController` |

---

## 6. 💰 Modul Keuangan (Finance/Bendahara)

| No | Fitur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 6.1 | Daftar semua pembayaran yang masuk | ✅ | `Pages/Bendahara/Pembayaran/` | Controller: `Keuangan/PembayaranController@index` |
| 6.2 | Lihat detail bukti pembayaran member | ✅ | `Pages/Bendahara/Pembayaran/Show.vue` | Controller: `@show` |
| 6.3 | Verifikasi (menyetujui) bukti pembayaran | ✅ | `Pages/Bendahara/Pembayaran/Show.vue` | Controller: `@verify`, POST `/verify` |
| 6.4 | Menolak pembayaran dengan alasan | ✅ | `Pages/Bendahara/Pembayaran/Show.vue` | Controller: `@reject` + modal input alasan |
| 6.5 | Status invoice otomatis berubah setelah verifikasi | ✅ | `Keuangan/PembayaranController.php` | Update `Invoice.is_accepted` & `MemberProfile.status` |
| 6.6 | Lihat & edit profil pribadi Keuangan | ✅ | `Pages/Bendahara/Profil/Show.vue` | Controller: `Keuangan/ProfilController` |

---

## 7. 👤 Modul Member

| No | Fitur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 7.1 | Lihat profil diri sendiri | ✅ | `Pages/Member/Profil/Show.vue` | Controller: `Member/ProfilController@show` |
| 7.2 | Edit profil member (nama, foto, institusi, dll) | ✅ | `Pages/Member/Profil/Edit.vue` | Controller: `@edit` + `@update` (PATCH/POST) |
| 7.3 | Akses halaman info keanggotaan premium | ✅ | `Pages/Member/Premium/Index.vue` | Controller: `Member/PremiumController@index` |
| 7.4 | Daftar/bergabung ke keanggotaan premium | ✅ | `Pages/Member/Premium/Index.vue` | Controller: `@join`, POST `/gabung` |
| 7.5 | Upload bukti pembayaran iuran premium | ✅ | `Pages/Member/Premium/Payment.vue` | Controller: `@pay`, POST `/bayar` |
| 7.6 | Lihat riwayat invoice pembayaran | ✅ | `Pages/Member/Premium/PaymentIndex.vue` | Controller: `@paymentIndex` |
| 7.7 | Lihat detail invoice tertentu | ✅ | `Pages/Member/Premium/Payment.vue` | Controller: `@paymentDetail` |
| 7.8 | Akses konten eksklusif (Video & E-Book) | ✅ | `Pages/Member/Konten/Index.vue` | Controller: `Member/KontenController@index` |
| 7.9 | Daftar semua pertanyaan milik member (forum Q&A) | ✅ | `Pages/Member/Pertanyaan/Index.vue` | Controller: `Member/PertanyaanController@index` |
| 7.10 | Buat pertanyaan / tiket baru | ✅ | `Pages/Member/Pertanyaan/Create.vue` | Controller: `@create` + `@store` |
| 7.11 | Lihat detail percakapan & balas | ✅ | `Pages/Member/Pertanyaan/Show.vue` | Controller: `@show` + `@reply` |
| 7.12 | Selesaikan/tutup tiket pertanyaan | ✅ | `Pages/Member/Pertanyaan/Show.vue` | Controller: `@close`, POST `/selesai` (hanya role member) |

---

## 8. 🌐 Halaman Publik (Landing Page & Blog)

| No | Fitur yang Dirancang | Status | File Implementasi | Catatan |
|:--:|:---|:---:|:---|:---|
| 8.1 | Landing Page komunitas (Home) | ✅ | `Pages/Welcome.vue` / `Pages/Home.vue` | Controller: `HomeController@index` |
| 8.2 | Navbar transparan saat scroll atas, solid saat scroll bawah | ✅ | `Layouts/AppLayout.vue` | Efek glassmorphism |
| 8.3 | Halaman daftar artikel blog publik | ✅ | `Pages/Blog/` | Controller: `PostController@index` |
| 8.4 | Halaman detail artikel blog berdasarkan slug | ✅ | `routes/web.php` (baris 15) | Controller: `PostController@show` |

---

## 9. 🗄️ Kesesuaian Database & Model (ERD vs Implementasi)

| No | Entitas / Relasi dalam Desain | Status | File Migrasi | Model Laravel |
|:--:|:---|:---:|:---|:---|
| 9.1 | Tabel `users` (id, name, email, password, role, is_active) | ✅ | `0001_01_01_000000_create_users_table.php` + `add_is_active...` | `User.php` |
| 9.2 | Tabel `member_profiles` (member_id FK, expire_date, status, dll) | ✅ | `2026_05_04_055305_create_member_profiles_table.php` | `MemberProfile.php` |
| 9.3 | Tabel `categories` (id, name, slug) | ✅ | `2026_05_04_060005_create_categories_table.php` | `Category.php` |
| 9.4 | Tabel `contents` (type, title, file_url, thumbnail_url) | ✅ | `2026_05_04_041859_create_contents_table.php` | `Content.php` |
| 9.5 | Tabel `posts` (title, slug, content, category_id FK, author_id FK) | ✅ | `2026_05_04_060006_create_posts_table.php` | `Post.php` |
| 9.6 | Tabel `invoices` (user_id FK, number, amount, due_date, is_accepted) | ✅ | `2026_05_04_060006_create_invoices_table.php` | `Invoice.php` |
| 9.7 | Tabel `payments` (invoice_id FK, status, reject_reason, payment_proof_url) | ✅ | `2026_05_04_060006_create_payments_table.php` | `Payment.php` |
| 9.8 | Tabel `conversations` (submitter_id FK, ticket_number, is_closed) | ✅ | `2026_05_04_043819_create_conversations_table.php` | `Conversation.php` |
| 9.9 | Tabel `messages` (conversation_id FK, sender_id FK, content) | ✅ | `2026_05_04_044042_create_messages_table.php` | `Message.php` |
| 9.10 | Tabel `settings` (konfigurasi sistem dinamis) | ✅ | `2026_05_05_000001_create_settings_table.php` | `Setting.php` |
| 9.11 | Relasi `User hasOne MemberProfile` | ✅ | — | `User.php` (Eloquent relationship) |
| 9.12 | Relasi `Invoice hasOne Payment` | ✅ | — | `Invoice.php` |
| 9.13 | Relasi `Conversation hasMany Messages` | ✅ | — | `Conversation.php` |

---

## 10. 🏗️ Kesesuaian Arsitektur Sistem

| No | Elemen Arsitektur dalam Desain | Status | Keterangan |
|:--:|:---|:---:|:---|
| 10.1 | Arsitektur Modern Monolith (Inertia.js SPA) | ✅ | Laravel sebagai backend + Vue 3 sebagai frontend tanpa API terpisah |
| 10.2 | Request melewati Router → Middleware → Controller → DB | ✅ | Sesuai alur `routes/web.php` → `CheckRole.php` → Controller → Eloquent |
| 10.3 | Pemisahan layout per role (AdminLayout, KetuaLayout, dll) | ✅ | Tersedia di `resources/js/Layouts/` |
| 10.4 | Penggunaan Inertia::render() untuk passing props ke Vue | ✅ | Setiap controller method memanggil `Inertia::render()` |
| 10.5 | Data autentikasi user tersedia global via HandleInertiaRequests | ✅ | `Middleware/HandleInertiaRequests.php` — shared data `auth.user` |
| 10.6 | Kompilasi aset frontend dengan Vite | ✅ | `vite.config.js` dikonfigurasi dengan plugin `laravel-vite-plugin` |
| 10.7 | Database SQLite untuk development | ✅ | File `.env` menunjuk ke `database/database.sqlite` |

---

## 11. 🎨 Kesesuaian UI/UX Design

| No | Elemen UI/UX yang Dirancang | Status | Keterangan |
|:--:|:---|:---:|:---|
| 11.1 | Desain glassmorphism pada navbar landing page | ✅ | Scroll-aware transparent → semi-solid navbar |
| 11.2 | Dashboard statistik Ketua dengan grafik interaktif (Chart.js) | ✅ | Bar chart & Doughnut chart via `vue-chartjs` |
| 11.3 | Editor teks kaya WYSIWYG (Quill) untuk blog & konten | ✅ | `@vueup/vue-quill` terintegrasi di halaman Petugas |
| 11.4 | Modal konfirmasi saat Keuangan menolak pembayaran | ✅ | Dialog modal dengan input alasan penolakan |
| 11.5 | Paginasi kustom dengan tombol Sebelumnya/Berikutnya | ✅ | Komponen paginasi custom di halaman daftar |
| 11.6 | Grid responsif: 3 kolom Video, 5 kolom E-Book | ✅ | Implementasi di `Petugas/Konten` dan `Member/Konten` |
| 11.7 | Kartu member dengan fitur download (Member Card) | ✅ | `Pages/Member/Profil/Show.vue` — canvas-based card generation |
| 11.8 | Tampilan konsisten di semua role (warna primer sama) | ✅ | Variabel warna utama diterapkan secara konsisten |

---

## 12. 🔄 Kesesuaian Alur Bisnis Utama (Business Flow)

### 12.1 Alur Pembayaran Premium Member

| No | Langkah Alur yang Dirancang | Status | Halaman / Controller |
|:--:|:---|:---:|:---|
| F1 | Member melihat info premium & memilih bergabung | ✅ | `Member/Premium/Index.vue` → `@join` |
| F2 | Sistem membuat Invoice otomatis | ✅ | `Member/PremiumController@join` create Invoice |
| F3 | Member upload bukti transfer ke rekening komunitas | ✅ | `Member/Premium/Payment.vue` → `@pay` |
| F4 | Petugas Keuangan menerima notifikasi / melihat daftar pembayaran | ✅ | `Keuangan/PembayaranController@index` |
| F5 | Keuangan memverifikasi → Invoice `is_accepted = true` | ✅ | `Keuangan/PembayaranController@verify` |
| F6 | Status profil member otomatis berubah menjadi Aktif | ✅ | Update `MemberProfile.status` setelah verifikasi |
| F7 | Keuangan menolak → input alasan → member upload ulang | ✅ | `@reject` → `Payment.reject_reason` → member retry |

### 12.2 Alur Forum Tanya Jawab (Q&A)

| No | Langkah Alur yang Dirancang | Status | Halaman / Controller |
|:--:|:---|:---:|:---|
| Q1 | Member membuat pertanyaan baru (tiket) | ✅ | `Member/Pertanyaan/Create.vue` → `@store` |
| Q2 | Sistem membuat nomor tiket otomatis | ✅ | `Conversation.ticket_number` di-generate saat store |
| Q3 | Petugas melihat daftar tiket masuk | ✅ | `Petugas/Pertanyaan/Index.vue` → `@index` |
| Q4 | Petugas membuka & membalas pertanyaan | ✅ | `Petugas/Pertanyaan/Show.vue` → `@reply` |
| Q5 | Member membalas & melanjutkan percakapan | ✅ | `Member/Pertanyaan/Show.vue` → `@reply` |
| Q6 | Member menyelesaikan/menutup tiket | ✅ | `@close` → `Conversation.is_closed = true` (hanya member) |

---

## 📊 Ringkasan Hasil Checklist

| Kategori | Total Item | ✅ Sesuai | ⚠️ Minor | ❌ Belum |
|:---|:---:|:---:|:---:|:---:|
| Autentikasi | 7 | 7 | 0 | 0 |
| RBAC & Middleware | 8 | 8 | 0 | 0 |
| Modul Super Admin | 6 | 6 | 0 | 0 |
| Modul Ketua | 5 | 5 | 0 | 0 |
| Modul Petugas | 12 | 12 | 0 | 0 |
| Modul Keuangan | 6 | 6 | 0 | 0 |
| Modul Member | 12 | 12 | 0 | 0 |
| Halaman Publik | 4 | 4 | 0 | 0 |
| Database & ERD | 13 | 13 | 0 | 0 |
| Arsitektur Sistem | 7 | 7 | 0 | 0 |
| UI/UX Design | 8 | 8 | 0 | 0 |
| Alur Bisnis | 13 | 13 | 0 | 0 |
| **TOTAL** | **101** | **101** | **0** | **0** |

---

> **Kesimpulan:** Seluruh **101 item** yang tercakup dalam dokumen System Analysis & Design telah berhasil diimplementasikan ke dalam web aplikasi. Alur kerja sistem (routing, middleware, controller, view, dan database) berjalan sesuai dengan rancangan awal, mulai dari autentikasi multi-role, manajemen konten, forum Q&A, hingga sistem pembayaran premium berbasis verifikasi manual.

---

*© 2026 Tim Pengembang PBL WOY — Aplikasi Member Komunitas*
