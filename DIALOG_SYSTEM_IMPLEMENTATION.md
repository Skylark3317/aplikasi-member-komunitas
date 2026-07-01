# Dialog System Implementation - Konsisten dengan Tema

## 📦 Files Created:

1. **`resources/js/Components/ConfirmDialog.vue`** - Komponen konfirmasi dialog
2. **`resources/js/Components/AlertDialog.vue`** - Komponen alert/notification dialog
3. **`resources/js/composables/useDialog.js`** - Composable untuk manajemen state dialog
4. **`resources/js/plugins/dialog.js`** - Vue plugin untuk global dialog system
5. **`resources/js/app.js`** - Updated untuk registrasi plugin

---

## 🎨 Features:

✅ **Konsisten dengan Tema Web**
- Menggunakan warna primary dari settings (via CSS variables)
- Desain modern dengan backdrop blur
- Smooth animations & transitions
- Responsive untuk mobile & desktop

✅ **4 Variant Styles:**
- **Danger** (merah) - untuk aksi berbahaya seperti delete
- **Warning** (kuning) - untuk peringatan
- **Info** (biru) - untuk informasi umum
- **Success** (hijau) - untuk konfirmasi sukses

✅ **Promise-based API**
- Mudah digunakan dengan async/await
- Loading state built-in untuk async operations
- Cancel handling yang proper

---

## 💻 Cara Penggunaan:

### 1. Confirm Dialog (menggantikan `confirm()`)

```vue
<script setup>
import { getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3';

const { proxy } = getCurrentInstance();

async function deleteAccount() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus Akun',
      message: 'Yakin ingin menghapus akun ini secara permanen?',
      variant: 'danger',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      router.delete(route('superadmin.kelol-akun.destroy', userId));
    }
  } catch {
    // User clicked cancel or closed dialog
  }
}
</script>
```

### 2. Alert Dialog (menggantikan `alert()`)

```vue
<script setup>
import { getCurrentInstance } from 'vue';

const { proxy } = getCurrentInstance();

async function validateFile(file) {
  if (file.size > 1024 * 1024) {
    await proxy.$dialog.alert({
      title: 'File Terlalu Besar',
      message: 'Ukuran file maksimal adalah 1MB.',
      variant: 'error',
      buttonText: 'Mengerti'
    });
    return false;
  }
  return true;
}
</script>
```

### 3. Dengan Loading State

```vue
<script setup>
import { getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3';

const { proxy } = getCurrentInstance();

async function toggleStatus() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Ubah Status',
      message: 'Yakin ingin mengubah status akun ini?',
      variant: 'warning'
    });
    
    if (confirmed) {
      proxy.$dialog.setConfirmLoading(true);
      
      router.patch(route('superadmin.kelol-akun.toggle-status', userId), {}, {
        onFinish: () => {
          proxy.$dialog.setConfirmLoading(false);
        }
      });
    }
  } catch {
    // User cancelled
  }
}
</script>
```

---

## 📝 Daftar File yang Perlu Diupdate:

### 1. **Admin/DetailAkun.vue** ✅
**Lokasi:** `resources/js/Pages/Admin/DetailAkun.vue`
**Line:** 316, 321
**Perubahan:**
```javascript
// SEBELUM:
if (!confirm('Yakin ingin mengaktifkan akun ini?')) return;

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Konfirmasi',
  message: `Yakin ingin ${props.user.is_active ? 'menonaktifkan' : 'mengaktifkan'} akun ini?`,
  variant: 'warning'
});
if (!confirmed) return;
```

### 2. **Member/Profil/Edit.vue** ✅
**Lokasi:** `resources/js/Pages/Member/Profil/Edit.vue`
**Line:** 430, 483, 493
**Perubahan:**
```javascript
// SEBELUM:
alert('Ukuran file maksimal adalah 1MB.');

// SESUDAH:
await this.$dialog.alert({
  message: 'Ukuran file maksimal adalah 1MB.',
  variant: 'error'
});
```

### 3. **Bendahara/Pembayaran/Show.vue** ✅
**Lokasi:** `resources/js/Pages/Bendahara/Pembayaran/Show.vue`
**Line:** 159
**Perubahan:**
```javascript
// SEBELUM:
if (confirm('Apakah Anda yakin ingin menerima pembayaran ini?'))

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Terima Pembayaran',
  message: 'Apakah Anda yakin ingin menerima pembayaran ini?',
  variant: 'success',
  confirmText: 'Terima'
});
if (confirmed)
```

### 4. **Member/Premium/Payment.vue** ✅
**Lokasi:** `resources/js/Pages/Member/Premium/Payment.vue`
**Line:** 381
**Perubahan:**
```javascript
// SEBELUM:
if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?'))

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Batalkan Pesanan',
  message: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
  variant: 'danger',
  confirmText: 'Batalkan'
});
if (confirmed)
```

### 5. **Petugas/Konten/Edit.vue** ✅
**Lokasi:** `resources/js/Pages/Petugas/Konten/Edit.vue`
**Line:** 159
**Perubahan:**
```javascript
// SEBELUM:
if (confirm('Apakah Anda yakin ingin menghapus konten ini?'))

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Hapus Konten',
  message: 'Apakah Anda yakin ingin menghapus konten ini?',
  variant: 'danger',
  confirmText: 'Hapus'
});
if (confirmed)
```

### 6. **Member/Profil/Show.vue** ✅
**Lokasi:** `resources/js/Pages/Member/Profil/Show.vue`
**Line:** 602, 607
**Perubahan:**
```javascript
// SEBELUM:
if (!confirm(`Yakin ingin mengajukan hapus akun?`)) return;

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Hapus Akun',
  message: `Yakin ingin mengajukan hapus akun? Anda akan di-logout dan akun akan dihapus permanen setelah ${deletionDurationText.value} jika tidak login kembali.`,
  variant: 'danger',
  confirmText: 'Ya, Hapus Akun'
});
if (!confirmed) return;
```

### 7. **Admin/PaketPremium.vue** ✅
**Lokasi:** `resources/js/Pages/Admin/PaketPremium.vue`
**Line:** 323
**Perubahan:**
```javascript
// SEBELUM:
if (!window.confirm("Apakah Anda yakin ingin menyimpan perubahan pada paket ini?"))

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Simpan Perubahan',
  message: 'Apakah Anda yakin ingin menyimpan perubahan pada paket ini?',
  variant: 'warning',
  confirmText: 'Simpan'
});
if (!confirmed)
```

### 8. **Petugas/Blog/Edit.vue** ✅
**Lokasi:** `resources/js/Pages/Petugas/Blog/Edit.vue`
**Line:** 115
**Perubahan:**
```javascript
// SEBELUM:
if (confirm('Apakah Anda yakin ingin menghapus blog ini?'))

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Hapus Blog',
  message: 'Apakah Anda yakin ingin menghapus blog ini?',
  variant: 'danger',
  confirmText: 'Hapus'
});
if (confirmed)
```

### 9. **Admin/RiwayatAktivitas.vue** ✅
**Lokasi:** `resources/js/Pages/Admin/RiwayatAktivitas.vue`
**Line:** 233
**Perubahan:**
```javascript
// SEBELUM:
if (confirm('Yakin ingin mengembalikan perubahan dari log ini? Pastikan data masih relevan.'))

// SESUDAH:
const confirmed = await this.$dialog.confirm({
  title: 'Kembalikan Perubahan',
  message: 'Yakin ingin mengembalikan perubahan dari log ini? Pastikan data masih relevan.',
  variant: 'warning',
  confirmText: 'Kembalikan'
});
if (confirmed)
```

---

## 🎯 Total Files to Update: **9 Files**

---

## 🚀 Next Steps:

1. ✅ Build frontend: `npm run build`
2. ✅ Test setiap dialog di browser
3. ✅ Verifikasi warna mengikuti tema
4. ✅ Test responsive di mobile
5. ✅ Update semua 9 files sesuai panduan

---

## 📱 Screenshot/Preview:

Dialog akan tampil dengan:
- Backdrop blur effect
- Icon sesuai variant (danger/warning/info/success)
- Warna button mengikuti variant
- Smooth animation slide down
- Responsive untuk mobile

---

## ⚙️ Customization:

Untuk mengubah warna, edit file CSS di masing-masing component:
- **Danger:** `#dc2626` (merah)
- **Warning:** `#f59e0b` (kuning/orange)
- **Info:** `#3b82f6` (biru)
- **Success:** `#10b981` (hijau)

Atau gunakan CSS variables dari settings jika diperlukan.
