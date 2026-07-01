# Update Fitur Search di Kelola Akun (Super Admin)

## Ringkasan
Mengubah fitur search di halaman **Kelola Akun (Super Admin)** dari sistem "klik tombol search" menjadi **instant search** (ketik langsung muncul hasil) seperti yang ada di role Ketua.

## File yang Diupdate
`resources/js/Pages/Admin/KelolAkun.vue`

## Perubahan Detail

### 1. Script Setup - Menambahkan Instant Search

**Sebelum:**
- Search menggunakan `form.search` yang dikirim ke backend saat klik tombol
- Memerlukan user klik tombol "Cari" untuk trigger pencarian
- Menggunakan `@keyup.enter` untuk search dengan Enter

**Sesudah:**
- Menambahkan `localSearch` ref untuk instant filtering di frontend
- Menambahkan `watch` dari Vue untuk monitoring perubahan search
- Filter dilakukan di computed `searchFiltered` secara real-time
- Menghapus kebutuhan tombol "Cari"

**Kode yang Ditambahkan:**

```javascript
import { reactive, ref, computed, watch } from 'vue'; // tambah watch

// Local search state for instant filtering
const localSearch = ref(form.search);

// Update showCount untuk menampilkan count saat ada search
const showCount = computed(() => searchedOnce.value || localSearch.value);

// Filter by local search (instant)
const searchFiltered = computed(() => {
  if (!localSearch.value.trim()) return props.users ?? [];
  const q = localSearch.value.toLowerCase();
  return (props.users ?? []).filter(user => {
    return (
      user.name?.toLowerCase().includes(q) ||
      user.email?.toLowerCase().includes(q) ||
      user.role?.toLowerCase().includes(q) ||
      user.premium_status?.toLowerCase().includes(q) ||
      user.plan_name?.toLowerCase().includes(q)
    );
  });
});

// sortedUsers sekarang menggunakan searchFiltered
const sortedUsers = computed(() => {
  const arr = [...searchFiltered.value];
  // ... sorting logic
});

// Watch local search to update immediately
watch(localSearch, () => {
  if (localSearch.value) {
    searchedOnce.value = true;
  }
});

// Update clearSearch function
function clearSearch() {
  localSearch.value = '';
  form.search = '';
  searchedOnce.value = false;
}
```

### 2. Template - Update Input & Hapus Tombol

**Perubahan:**
- `v-model="form.search"` → `v-model="localSearch"`
- Hapus `@keyup.enter="applyFilters"`
- Hapus tombol `<button class="btn-search" @click="applyFilters">Cari</button>`
- Update `@click` di clear button untuk memanggil `clearSearch()`

```vue
<!-- Sebelum -->
<input
  v-model="form.search"
  type="text"
  placeholder="Cari nama atau email…"
  class="input-search"
  @keyup.enter="applyFilters"
/>
<button class="btn-search" @click="applyFilters">Cari</button>

<!-- Sesudah -->
<input
  v-model="localSearch"
  type="text"
  placeholder="Cari nama atau email…"
  class="input-search"
/>
<!-- Tombol "Cari" dihapus -->
```

### 3. Style - Hapus CSS Tombol Search

**Dihapus:**
```css
.btn-search {
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 8px 20px;
  border-radius: 7px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.2s;
  align-self: flex-end;
}
.btn-search:hover { filter: brightness(0.9); }
```

## Cara Kerja Instant Search

1. **User mengetik** di input search
2. **localSearch ref** otomatis terupdate (v-model)
3. **watch** mendeteksi perubahan dan set `searchedOnce = true`
4. **searchFiltered computed** otomatis re-evaluate:
   - Filter array `props.users` berdasarkan query
   - Cek di field: name, email, role, premium_status, plan_name
5. **sortedUsers computed** menggunakan hasil filter
6. **Table otomatis update** dengan hasil yang sudah difilter

## Kolom yang Dicari
Search akan mencari di kolom:
- ✅ Nama (name)
- ✅ Email (email)
- ✅ Role (role)
- ✅ Status Premium (premium_status)
- ✅ Paket Premium (plan_name)

## Status Build
✅ **Berhasil** - Build sukses tanpa error
```
vite v8.0.10 building client environment for production...
✓ 903 modules transformed.
✓ built in 1.06s
```

## Testing Checklist

### Fitur Search
- [ ] Buka halaman Kelola Akun (Super Admin)
- [ ] Ketik "nem" di search box
- [ ] Hasil langsung muncul tanpa perlu klik tombol
- [ ] Ketik "staff" - filter berdasarkan role
- [ ] Ketik "@" - filter berdasarkan email
- [ ] Ketik "premium" - filter berdasarkan status premium
- [ ] Tombol "X" (clear) menghapus search dan tampilkan semua data
- [ ] Counter "X akun ditemukan" muncul saat ada search

### Fitur Lainnya (Pastikan Tetap Berfungsi)
- [ ] Filter Role (toggle buttons) masih berfungsi
- [ ] Filter Status (toggle buttons) masih berfungsi
- [ ] Sorting kolom masih berfungsi
- [ ] Reset filter menghapus semua filter termasuk search
- [ ] Tombol "Detail" membuka halaman detail akun

## Perbedaan dengan Role Ketua

| Fitur | Ketua/Detail.vue | Admin/KelolAkun.vue |
|-------|------------------|---------------------|
| Search Type | Instant (frontend) | Instant (frontend) ✅ SAMA |
| Filter Backend | Ya (via router.get) | Ya (Role & Status) |
| Pagination | Ya (20 per page) | Tidak (show all) |
| Export Excel | Ya | Tidak |
| Date Range Filter | Ya | Tidak |

## Catatan Teknis

1. **Performance**: Search dilakukan di frontend (filtering array), cocok untuk jumlah data yang tidak terlalu besar (< 1000 users)
2. **Real-time**: Menggunakan Vue computed property yang otomatis re-evaluate saat dependency berubah
3. **Case-insensitive**: Search tidak case-sensitive (`.toLowerCase()`)
4. **Partial match**: Search akan match substring (menggunakan `.includes()`)
5. **Multi-field**: Search di 5 kolom sekaligus untuk fleksibilitas

## Improvement di Masa Depan (Opsional)

1. **Debounce**: Tambahkan debounce jika jumlah user sangat banyak untuk performa lebih baik
2. **Backend Search**: Pindah ke backend search jika data > 1000 records
3. **Highlight**: Highlight text yang match dengan query di hasil
4. **Advanced Filter**: Tambahkan filter per kolom seperti di role Ketua
