# Update Dialog Sistem untuk Halaman Pengaturan

## Tanggal
Sesuai context transfer (lanjutan dari implementasi dialog system)

## Ringkasan
Menambahkan custom dialog konfirmasi ke halaman **Pengaturan (Super Admin)** untuk semua aksi penting seperti submit dan delete gambar.

## File yang Diupdate
`resources/js/Pages/Admin/Pengaturan.vue`

## Perubahan Detail

### 1. Import Statement
```javascript
// Menambahkan getCurrentInstance
import { ref, computed, onMounted, onUnmounted, getCurrentInstance } from 'vue';

// Menambahkan proxy instance
const { proxy } = getCurrentInstance();
```

### 2. Fungsi yang Diupdate (6 fungsi)

#### a. `submit()` - Simpan Perubahan Pengaturan
- **Varian Dialog**: `info` (biru)
- **Aksi**: Konfirmasi sebelum menyimpan semua perubahan pengaturan
- **Pesan**: "Apakah Anda yakin ingin menyimpan semua perubahan pengaturan?"

```javascript
async function submit() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Simpan Perubahan Pengaturan',
      message: 'Apakah Anda yakin ingin menyimpan semua perubahan pengaturan?',
      variant: 'info',
      confirmText: 'Simpan',
      cancelText: 'Batal'
    });
    
    if (!confirmed) return;
    
    // Existing form.post logic...
  } catch {
    // User cancelled
  }
}
```

#### b. `deleteLogo()` - Hapus Logo Komunitas
- **Varian Dialog**: `warning` (kuning)
- **Tab**: Identitas
- **Pesan**: "Apakah Anda yakin ingin menghapus logo komunitas?"

#### c. `deleteBg()` - Hapus Background Hero Section
- **Varian Dialog**: `warning` (kuning)
- **Tab**: Landing Page
- **Pesan**: "Apakah Anda yakin ingin menghapus gambar latar belakang hero section?"

#### d. `deleteCardBg()` - Hapus Background Kartu Member
- **Varian Dialog**: `warning` (kuning)
- **Tab**: Kartu Member
- **Pesan**: "Apakah Anda yakin ingin menghapus gambar latar belakang kartu member?"

#### e. `deleteAbout()` - Hapus Gambar About Section
- **Varian Dialog**: `warning` (kuning)
- **Tab**: Landing Page
- **Pesan**: "Apakah Anda yakin ingin menghapus gambar about section?"

#### f. `deleteCvSignature()` - Hapus Tanda Tangan Template Surat
- **Varian Dialog**: `warning` (kuning)
- **Tab**: Template Surat
- **Pesan**: "Apakah Anda yakin ingin menghapus gambar tanda tangan untuk template surat?"

## Pola Implementasi untuk Fungsi Delete

Semua fungsi delete mengikuti pola yang sama:

```javascript
async function deleteX() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus X',
      message: 'Apakah Anda yakin ingin menghapus X?',
      variant: 'warning',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      // Logic untuk menghapus
      form.x = null;
      form.delete_x = true;
      xPreview.value = null;
      form.errors.x = null;
    }
  } catch {
    // User cancelled
  }
}
```

## Status Build
✅ **Berhasil** - Build sukses tanpa error
```
vite v8.0.10 building client environment for production...
✓ 903 modules transformed.
✓ built in 1.38s
```

## Fitur yang Ditambahkan

### Tombol "Simpan Perubahan"
- Ketika user klik tombol "Simpan perubahan" di top bar
- Muncul dialog konfirmasi info (biru) sebelum submit form
- User dapat membatalkan atau melanjutkan

### Tombol "Hapus" di Setiap Gambar
- Ketika user klik tombol "Hapus" untuk logo/gambar apapun
- Muncul dialog konfirmasi warning (kuning) 
- User dapat membatalkan atau melanjutkan hapus
- Semua tab yang memiliki upload gambar terlindungi

## Testing Checklist

### Tombol Simpan Perubahan
- [ ] Klik "Simpan perubahan" di top bar
- [ ] Dialog info (biru) muncul dengan pesan yang benar
- [ ] Klik "Batal" - tidak terjadi submit
- [ ] Klik "Simpan" - form di-submit dan data tersimpan
- [ ] Flash message sukses muncul setelah submit

### Tab Identitas
- [ ] Upload logo baru - preview muncul
- [ ] Klik "Hapus logo komunitas"
- [ ] Dialog warning (kuning) muncul
- [ ] Konfirmasi - logo preview hilang
- [ ] Cancel - logo tetap ada

### Tab Kartu Member
- [ ] Upload background kartu - preview muncul
- [ ] Klik "Hapus background kartu"
- [ ] Dialog warning muncul dan berfungsi dengan benar

### Tab Landing Page
- [ ] Test hapus background hero dengan dialog
- [ ] Test hapus gambar about dengan dialog
- [ ] Kedua dialog berfungsi independen

### Tab Template Surat
- [ ] Upload tanda tangan - preview muncul
- [ ] Klik "Hapus tanda tangan"
- [ ] Dialog warning muncul dan berfungsi

## Catatan Teknis

1. **Konsistensi**: Semua dialog menggunakan sistem yang sama dengan 9 file lainnya
2. **Varian yang Digunakan**:
   - `info` (biru) - untuk simpan perubahan pengaturan
   - `warning` (kuning) - untuk hapus gambar/asset
3. **UX**: User tetap bisa cancel setiap saat dengan ESC atau tombol Batal
4. **Tidak Ada Breaking Changes**: Form tetap bekerja sama seperti sebelumnya, hanya ditambah konfirmasi

## Total File dengan Dialog System
**10 file** telah menggunakan custom dialog system yang konsisten dan sesuai tema website.
