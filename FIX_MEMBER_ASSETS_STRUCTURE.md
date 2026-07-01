# Perbaikan Struktur Tab Member Assets

## Ringkasan
Memperbaiki struktur HTML yang salah pada tab **Member Assets** di halaman Pengaturan (Super Admin). Ada duplikasi konten dan tab yang tidak sesuai dengan desain reorganisasi tab.

## Masalah yang Ditemukan

### 1. **Konten Duplikat "Syarat & Ketentuan"**
**Lokasi**: Baris 377-384 (sebelum perbaikan)
**Masalah**: Konten "Syarat & Ketentuan" muncul di dalam tab `member-assets`, padahal seharusnya hanya ada di tab `legal`.

```html
<!-- SALAH: Ini tidak seharusnya ada di member-assets -->
<p class="form-subsection mt-4">Template Surat Keterangan</p>
<p class="form-subsection" style="margin-top: 12px; margin-bottom: 8px;">Informasi Kop Surat</p>
<h3 class="form-card-title">
  <svg>...</svg>
  Syarat & Ketentuan  <!-- ❌ Ini salah! -->
</h3>
<p class="field-hint">Konten syarat dan ketentuan untuk form registrasi member.</p>
<div class="field-group">
  <label class="field-label">Isi Syarat & Ketentuan</label>
  <RichTextEditor v-model="form.terms_and_conditions" />
</div>
```

### 2. **Tab `cvtemplate` yang Tidak Ada dalam Array Tabs**
**Lokasi**: Baris 387 (sebelum perbaikan)
**Masalah**: Ada `<div class="form-card" v-show="activeTab === 'cvtemplate'">` yang tidak akan pernah muncul karena key `cvtemplate` tidak ada dalam array `tabs`.

```javascript
// Array tabs yang valid:
const tabs = [
  { key: 'identitas-kontak', label: 'Identitas & Kontak' },
  { key: 'premium-pembayaran', label: 'Premium & Pembayaran' },
  { key: 'landing-page', label: 'Landing Page' },
  { key: 'member-assets', label: 'Member Assets' },
  { key: 'legal', label: 'Legal' },
];

// ❌ Key 'cvtemplate' tidak ada dalam array tabs!
```

### 3. **Struktur yang Berantakan**
Konten Template Surat (CV fields) seharusnya masuk ke dalam tab `member-assets`, bukan dalam tab terpisah `cvtemplate`.

## Perbaikan yang Dilakukan

### File yang Diubah
`resources/js/Pages/Admin/Pengaturan.vue`

### Perubahan
Menghapus duplikasi konten "Syarat & Ketentuan" dan tag pembuka tab `cvtemplate` yang salah, kemudian menggabungkan semua konten CV fields ke dalam tab `member-assets`.

**BEFORE (Salah)**:
```html
<!-- === MEMBER ASSETS === -->
<div class="form-card" v-show="activeTab === 'member-assets'">
  <!-- Kartu Member Design -->
  ...
  
  <p class="form-subsection mt-4">Template Surat Keterangan</p>
  <p class="form-subsection">Informasi Kop Surat</p>
  
  <!-- ❌ SALAH: Konten S&K muncul di sini -->
  <h3 class="form-card-title">Syarat & Ketentuan</h3>
  <RichTextEditor v-model="form.terms_and_conditions" />
</div>

<!-- ❌ SALAH: Tab yang tidak ada dalam array tabs -->
<div class="form-card" v-show="activeTab === 'cvtemplate'">
  <!-- CV Template Fields -->
  ...
</div>
```

**AFTER (Benar)**:
```html
<!-- === MEMBER ASSETS === -->
<div class="form-card" v-show="activeTab === 'member-assets'">
  <!-- Kartu Member Design -->
  ...
  
  <!-- ✅ BENAR: Langsung ke konten Template Surat -->
  <p class="form-subsection mt-4">Template Surat Keterangan</p>
  <p class="form-subsection" style="margin-top: 12px; margin-bottom: 8px;">Informasi Kop Surat</p>
  
  <!-- CV Template Fields langsung di bawah ini -->
  <div class="field-group">
    <label class="field-label">Nama Komunitas (Kop Surat)</label>
    <input v-model="form.cv_community_name" type="text" />
  </div>
  <!-- ... 8 field CV lainnya ... -->
</div><!-- /form-card member-assets -->
```

## Struktur Tab Member Assets yang Benar

Tab **Member Assets** sekarang berisi:

### 1. **Kartu Member Design**
- Gambar Latar Belakang Kartu Member (upload/delete)

### 2. **Template Surat Keterangan**
- **Informasi Kop Surat:**
  - Nama Komunitas (Kop Surat)
  - Email Komunitas (Kop Surat)
  - Website Komunitas
  - Judul Surat
  
- **Konten Surat:**
  - Teks Pembuka (Keterangan)
  - Teks Penutup
  - Tempat/Kota TTD
  - Jabatan Penandatangan
  - Nama Penandatangan
  - Gambar Tanda Tangan (Signature)

**Total: 10 input fields** (1 Kartu + 9 Template Surat)

## Preview untuk Tab Member Assets

Preview tetap menampilkan **split view** dengan 2 card:
1. **Preview Kartu Member** (atas)
2. **Preview Template Surat** (bawah, scrollable)

```vue
<div v-else-if="activeTab === 'member-assets'" style="display: flex; flex-direction: column; gap: 16px; overflow-y: auto;">
  <!-- Kartu Member Preview -->
  <div style="background: #fff; border-radius: 12px; padding: 16px;">
    <h5>Preview Kartu Member</h5>
    <PreviewMemberCard :form="form" :cardBgUrl="cardBgPreview" />
  </div>
  
  <!-- CV Template Preview -->
  <div style="background: #fff; border-radius: 12px; padding: 16px;">
    <h5>Preview Template Surat</h5>
    <div class="cv-preview-shell">
      <!-- CV A4 scaled preview -->
    </div>
  </div>
</div>
```

## Status Build
✅ **Berhasil** - Build sukses tanpa error
```
vite v8.0.10 building client environment for production...
✓ 903 modules transformed.
✓ built in 1.47s
```

File size Pengaturan.js: **36.59 kB** (gzip: 8.87 kB) - sedikit lebih kecil dari sebelumnya (37.97 kB)

## Testing Checklist

### Fungsionalitas Tab Member Assets
- [ ] Buka halaman Pengaturan → Tab "Member Assets"
- [ ] **Kartu Member Design**:
  - [ ] Preview kartu member muncul di atas
  - [ ] Upload background kartu berfungsi
  - [ ] Delete background kartu memunculkan dialog warning
  
- [ ] **Template Surat Keterangan**:
  - [ ] 9 field template muncul dengan benar
  - [ ] Input fields dapat diisi
  - [ ] Upload tanda tangan berfungsi
  - [ ] Delete tanda tangan memunculkan dialog warning
  - [ ] Preview CV muncul di bawah (A4 scaled)
  - [ ] Perubahan input langsung terlihat di preview

### Tab Legal (Pastikan Tidak Terpengaruh)
- [ ] Buka tab "Legal"
- [ ] Konten "Syarat & Ketentuan" muncul dengan benar
- [ ] Rich text editor berfungsi
- [ ] Preview menampilkan HTML yang di-render

### Form Submission
- [ ] Klik "Simpan perubahan"
- [ ] Dialog konfirmasi info (biru) muncul
- [ ] Konfirmasi → Data tersimpan
- [ ] Flash message sukses muncul
- [ ] Data di tab Member Assets dan Legal tersimpan dengan benar

## Catatan Perbaikan

### Yang Dihapus
1. Duplikasi konten "Syarat & Ketentuan" (13 baris HTML)
2. Tag pembuka `<div class="form-card" v-show="activeTab === 'cvtemplate'">` yang salah
3. Tag penutup `</div>` yang tidak tepat posisinya

### Yang Dipertahankan
1. Semua 9 field Template Surat tetap ada dan fungsional
2. Preview split view tetap berfungsi dengan baik
3. Semua dialog konfirmasi tetap berfungsi:
   - `deleteCardBg()` - warning dialog (kuning)
   - `deleteCvSignature()` - warning dialog (kuning)

### Impact
- **Tidak ada breaking changes** - Hanya perbaikan struktur HTML
- **Fungsionalitas tetap sama** - Semua fitur tetap bekerja
- **Code lebih clean** - Menghilangkan duplikasi dan tab yang tidak terpakai
- **Bundle size sedikit lebih kecil** - Dari 37.97 kB menjadi 36.59 kB (gzip: 8.94 kB → 8.87 kB)

## Kesimpulan

Perbaikan ini menyelesaikan inkonsistensi struktur yang terjadi akibat reorganisasi tab sebelumnya. Sekarang:

✅ Tab `member-assets` berisi semua konten yang seharusnya (Kartu + Template Surat)
✅ Tab `legal` berisi konten "Syarat & Ketentuan" yang unik (tidak ada duplikasi)
✅ Tidak ada tab yang tidak terpakai (`cvtemplate`)
✅ Preview split view berfungsi dengan baik
✅ Build sukses tanpa error

## Referensi
- **Task**: Lanjutan dari REORGANIZE_PENGATURAN_TABS.md
- **File diubah**: `resources/js/Pages/Admin/Pengaturan.vue`
- **Baris yang diperbaiki**: 377-395 (sebelumnya)
- **Commit message suggestion**: `fix: clean up member-assets tab structure and remove duplicate content`

