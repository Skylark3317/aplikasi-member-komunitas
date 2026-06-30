# Changelog - Template Surat Keanggotaan

## 30 Juni 2026

### Fitur Baru: Kustomisasi Template Surat Keterangan Keanggotaan

Menambahkan 3 field baru di halaman Pengaturan (Tab Template Surat) untuk kustomisasi yang lebih fleksibel:

#### Field yang Ditambahkan:

1. **Nama Komunitas (Kop Surat)** - `cv_community_name`
   - Field untuk override nama komunitas khusus di kop surat
   - Jika kosong, akan fallback ke field "Nama Komunitas" dari tab Identitas
   - Default: "Aplikasi Member Komunitas"

2. **Website Komunitas** - `cv_website`
   - Field untuk menampilkan URL website di kop surat
   - Sebelumnya di-generate otomatis, sekarang bisa diisi manual
   - Default: "www.komunitasamk.com"

3. **Judul Surat** - `cv_letter_title`
   - Field untuk mengatur judul surat yang ditampilkan
   - Default: "Surat Keterangan Keanggotaan Premium"

#### File yang Diubah:

**Frontend:**
- `resources/js/Pages/Admin/Pengaturan.vue`
  - Menambahkan 3 input field baru di form tab "Template Surat"
  - Menambahkan property di form object dengan fallback dari settings
  - Live preview otomatis menyesuaikan saat user mengetik

- `resources/js/Pages/Member/Profil/Show.vue`
  - Mengupdate function `printLetter()` untuk menggunakan field baru:
    - `cv_community_name` untuk nama komunitas di kop surat
    - `cv_website` untuk website di kop surat
    - `cv_letter_title` untuk judul surat

**Backend:**
- `app/Http/Controllers/Admin/PengaturanController.php`
  - Menambahkan validation rules untuk 3 field baru
  - Menambahkan field ke array `$fields` untuk diproses saat update

- `database/seeders/SettingsSeeder.php`
  - Menambahkan default value untuk 3 field baru

- `database/migrations/2026_06_30_160246_add_cv_template_fields_to_settings.php`
  - Migration untuk menambahkan field baru ke database existing
  - Data migration untuk insert default values

#### Cara Menggunakan:

1. Login sebagai Super Admin
2. Buka menu Pengaturan
3. Pilih tab "Template Surat"
4. Edit field "Nama Komunitas (Kop Surat)", "Website Komunitas", atau "Judul Surat"
5. Klik "Simpan perubahan"
6. Member yang download surat keterangan akan melihat perubahan tersebut

#### Testing:

- Test `test_ADM24_superadmin_dapat_mengubah_template_surat_keanggotaan` masih passing
- Migration berhasil menambahkan 3 record baru ke tabel settings
- Live preview di halaman Pengaturan sudah menampilkan perubahan real-time

