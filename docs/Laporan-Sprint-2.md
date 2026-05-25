# Laporan Sprint 2 (Mei) - Implementasi Front-End dan Back-End

Berikut adalah laporan progress implementasi aplikasi member komunitas berdasarkan Sprint 2 bulan Mei:

## a. Frontend

Berdasarkan struktur proyek (`resources/js/Pages`), terdapat beberapa modul halaman yang telah diimplementasikan dari wireframe menggunakan Vue.js dan Inertia.js. Beberapa kelompok halaman tersebut meliputi:

1. **Halaman Publik / Landing Page:**
   - Home Page / Welcome Page
   - Blog (Daftar Artikel & Detail Artikel)
2. **Autentikasi (Auth):**
   - Login, Register, Forgot Password, Reset Password
3. **Modul Member:**
   - Dashboard Member
   - Profil Member (Edit & Tampil Profil)
   - Pembayaran / Langganan Premium
   - Forum / Q&A (Tanya Jawab)
   - Akses Konten Pembelajaran
4. **Modul Admin (Petugas / Ketua / Keuangan):**
   - Dashboard Statistik
   - Manajemen Konten / Blog
   - Manajemen User & Role
   - Verifikasi Pembayaran (Modul Keuangan)
   - Manajemen Pertanyaan / Tiket Dukungan

*(Catatan: Total halaman `.vue` yang telah dibuat kurang lebih mencapai 20+ komponen utama, menyesuaikan dengan wireframe yang telah disepakati).*

## b. Backend

Berdasarkan ERD dan migrasi database (`database/migrations`), **seluruh tabel entitas utama** dari desain ERD telah berhasil diimplementasikan ke dalam database. Terdapat **9 entitas domain utama** yang dibuat, antara lain:

1. `users` (Manajemen Pengguna & Autentikasi)
2. `member_profiles` (Data detail profil anggota)
3. `conversations` (Tiket percakapan/pertanyaan dari member)
4. `messages` (Isi pesan dalam tiket percakapan)
5. `contents` (Materi / Konten eksklusif untuk member)
6. `posts` (Artikel blog / berita umum)
7. `categories` (Kategori artikel/postingan)
8. `invoices` (Tagihan langganan/pembayaran member)
9. `payments` (Bukti dan status pembayaran member)
10. `settings` (Pengaturan aplikasi)

Selain tabel entitas di atas, terdapat juga tabel sistem bawaan framework (seperti `cache` dan `jobs`).

## c. Integrasi Frontend dan Backend

Integrasi antara antarmuka Vue.js (Frontend) dan Laravel (Backend) telah berjalan secara _end-to-end_ dengan menggunakan Inertia.js. Beberapa flow yang sudah terintegrasi meliputi:

- **Autentikasi:** Flow login, register, dan pengelolaan session berjalan lancar, termasuk akses role (_middleware_ untuk Petugas, Member, Keuangan).
- **CRUD Operasi:** 
  - Manajemen konten dan artikel dari Admin langsung ter-render secara dinamis di halaman Member dan Publik.
  - Member dapat melengkapi profil, membuat tiket pertanyaan, dan mengunggah bukti pembayaran.
  - Bendahara/Keuangan dapat melihat dan memverifikasi bukti pembayaran, yang statusnya akan secara _real-time_ mengupdate akses premium member terkait.
- **File Upload:** Upload gambar (thumbnail konten, foto profil, dan bukti pembayaran) sudah terhubung dengan sistem storage/bucket dengan integrasi yang tepat.

---

**Bukti Pendukung:**
_Note: Lampirkan link (Google Drive / GitHub / Video Demo) di sini_

- **Link Repository/Commit Terbaru:** [Masukkan Link Repositori]
- **Link Video Demo (Jika ada):** [Masukkan Link Video]
- **Tangkapan Layar (PDF Terpisah):** [Lampirkan file PDF / Link Folder Bukti]
