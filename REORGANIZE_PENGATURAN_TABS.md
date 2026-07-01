# Reorganisasi Tab Pengaturan Super Admin

## Ringkasan
Mereorganisasi tab di halaman **Pengaturan (Super Admin)** dari 7 tab menjadi 5 tab berdasarkan **User Journey** yang lebih logis dan intuitif.

## File yang Diupdate
`resources/js/Pages/Admin/Pengaturan.vue`

## Perubahan Tab

### Sebelum (7 Tab)
1. Identitas
2. Kontak & Sosial
3. Keanggotaan
4. Kartu Member
5. Landing Page
6. Template Surat
7. Syarat & Ketentuan

### Sesudah (5 Tab) - User Journey Based ✨

#### 1. 🏠 **Identitas & Kontak**
**Key**: `identitas-kontak`
**Isi**:
- **Profil Komunitas**: Nama Komunitas, Logo Komunitas
- **Kontak**: Email, Nomor Telepon, Alamat
- **Media Sosial**: X, Facebook, LinkedIn, Skype, Instagram, YouTube

**Preview**: PreviewLandingPage (menampilkan profil)

---

#### 2. 💎 **Premium & Pembayaran**
**Key**: `premium-pembayaran`
**Isi**:
- **Bank Account**: Nama Pemilik Rekening, Nomor Rekening, Nama Bank
- **Membership Rules**: Peringatan Expired, Countdown Invoice, Durasi Penghapusan Akun
- **Premium Benefits**: Daftar benefit yang tersedia (dynamic list)

**Preview**: PreviewMembership (menampilkan rules membership)

---

#### 3. 🌐 **Landing Page**
**Key**: `landing-page`
**Isi**:
- **Colors**: Warna Primer, Warna Permukaan
- **Hero Section**: Background Image, Judul Hero, Deskripsi Hero
- **About Section**: Gambar About, Judul About, Deskripsi About
- **Statistik Member**: Member Aktif, Pasif, Company, Personal

**Preview**: PreviewLandingPage (menampilkan landing page)

---

#### 4. 🎫 **Member Assets**
**Key**: `member-assets`
**Isi**:
- **Kartu Member Design**: Background Kartu Member
- **Template Surat Keterangan**: 
  - Informasi Kop Surat (Nama, Email, Website, Judul)
  - Konten Surat (Teks Pembuka, Penutup, Tempat TTD, Jabatan, Nama Penandatangan, Gambar TTD)

**Preview**: Split view dengan 2 card:
1. Preview Kartu Member (PreviewMemberCard)
2. Preview Template Surat (CV A4 scaled)

---

#### 5. ⚖️ **Legal**
**Key**: `legal`
**Isi**:
- **Syarat & Ketentuan**: Rich text editor untuk konten legal

**Preview**: Rendered HTML dari Syarat & Ketentuan

---

## Detail Implementasi

### 1. Update Tabs Array

```javascript
const activeTab = ref('identitas-kontak');
const tabs = [
  { key: 'identitas-kontak', label: 'Identitas & Kontak' },
  { key: 'premium-pembayaran', label: 'Premium & Pembayaran' },
  { key: 'landing-page', label: 'Landing Page' },
  { key: 'member-assets', label: 'Member Assets' },
  { key: 'legal', label: 'Legal' },
];
```

### 2. Update Template Form Cards

Semua `v-show` directive diupdate untuk menggunakan tab key yang baru:
- `v-show="activeTab === 'identitas-kontak'"`
- `v-show="activeTab === 'premium-pembayaran'"`
- `v-show="activeTab === 'landing-page'"`
- `v-show="activeTab === 'member-assets'"`
- `v-show="activeTab === 'legal'"`

### 3. Update Preview Logic

```javascript
// Identitas & Kontak dan Landing Page menggunakan PreviewLandingPage
v-if="['identitas-kontak', 'landing-page'].includes(activeTab)"

// Premium & Pembayaran menggunakan PreviewMembership
v-else-if="activeTab === 'premium-pembayaran'"

// Legal menampilkan HTML content
v-else-if="activeTab === 'legal'"

// Member Assets menampilkan split view dengan 2 preview
v-else-if="activeTab === 'member-assets'"
```

### 4. Member Assets Preview - Split View

Khusus untuk tab Member Assets, preview dibagi menjadi 2 section dalam container yang scrollable:

```vue
<div v-else-if="activeTab === 'member-assets'" style="...flex-direction: column; gap: 16px...">
  <!-- Kartu Member Preview -->
  <div style="background: #fff; border-radius: 12px; padding: 16px...">
    <h5>Preview Kartu Member</h5>
    <PreviewMemberCard :form="form" :cardBgUrl="cardBgPreview" />
  </div>
  
  <!-- CV Template Preview -->
  <div style="background: #fff; border-radius: 12px; padding: 16px...">
    <h5>Preview Template Surat</h5>
    <div class="cv-preview-shell">
      <!-- CV A4 Preview -->
    </div>
  </div>
</div>
```

## Keuntungan Reorganisasi

### 1. **Logical Grouping**
- Field-field yang related dikelompokkan bersama
- Mengurangi cognitive load saat navigasi

### 2. **User Journey Aligned**
- Tab disusun berdasarkan alur pikir user saat setup komunitas:
  1. Setup identitas & kontak dulu
  2. Atur sistem premium & pembayaran
  3. Customize landing page
  4. Design member assets
  5. Tambahkan legal terms

### 3. **Reduced Tabs**
- Dari 7 tab menjadi 5 tab (28% reduction)
- Lebih mudah dinavigasi
- Tab bar lebih rapi

### 4. **Better Information Architecture**
- Identitas + Kontak + Sosmed dalam 1 tab (related)
- Bank + Membership + Benefits dalam 1 tab (payment ecosystem)
- Kartu + Surat dalam 1 tab (member assets)

## Status Build
✅ **Berhasil** - Build sukses tanpa error
```
vite v8.0.10 building client environment for production...
✓ 903 modules transformed.
✓ built in 1.63s
```

## Testing Checklist

### Tab Navigation
- [ ] Buka halaman Pengaturan
- [ ] Verify ada 5 tab: Identitas & Kontak, Premium & Pembayaran, Landing Page, Member Assets, Legal
- [ ] Klik setiap tab dan pastikan konten muncul dengan benar
- [ ] Horizontal scroll pada tab bar berfungsi (mouse wheel)

### Tab 1: Identitas & Kontak
- [ ] **Profil Komunitas**: Input nama, upload/hapus logo
- [ ] **Kontak**: Input email, telepon, alamat
- [ ] **Media Sosial**: Input 6 social media links
- [ ] Preview menampilkan logo dan info profil

### Tab 2: Premium & Pembayaran
- [ ] **Bank Account**: Input 3 field rekening
- [ ] **Membership Rules**: Input 3 field aturan
- [ ] Link ke Paket Premium berfungsi
- [ ] **Premium Benefits**: Add/remove benefit items
- [ ] Preview menampilkan membership rules

### Tab 3: Landing Page
- [ ] **Colors**: Color picker untuk 2 warna
- [ ] **Hero Section**: Upload/hapus gambar, input judul & deskripsi
- [ ] **About Section**: Upload/hapus gambar, input judul & deskripsi
- [ ] **Statistik Member**: Input 4 angka statistik
- [ ] Preview menampilkan landing page dengan semua element

### Tab 4: Member Assets
- [ ] **Kartu Member Design**: Upload/hapus background kartu
- [ ] Preview Kartu Member muncul di section atas
- [ ] **Template Surat**: Input 9 field template
- [ ] Upload/hapus tanda tangan
- [ ] Preview CV muncul di section bawah (A4 scaled)
- [ ] Scroll dalam preview berfungsi

### Tab 5: Legal
- [ ] Rich text editor berfungsi
- [ ] Preview menampilkan rendered HTML
- [ ] Formatting (bold, list, heading) terlihat di preview

### Form Submission
- [ ] Klik "Simpan perubahan" menampilkan dialog konfirmasi (info - biru)
- [ ] Konfirmasi menyimpan semua data di 5 tab
- [ ] Flash message sukses muncul
- [ ] Data tersimpan dan preview terupdate

### Dialog Konfirmasi (Existing)
- [ ] Hapus logo: warning dialog
- [ ] Hapus background hero: warning dialog
- [ ] Hapus background kartu: warning dialog
- [ ] Hapus gambar about: warning dialog
- [ ] Hapus tanda tangan: warning dialog

## Mapping Konten Lama ke Baru

| Konten | Tab Lama | Tab Baru |
|--------|----------|----------|
| Nama Komunitas, Logo | Identitas | 🏠 Identitas & Kontak |
| Email, Phone, Alamat | Kontak & Sosial | 🏠 Identitas & Kontak |
| Media Sosial (6 links) | Kontak & Sosial | 🏠 Identitas & Kontak |
| Bank Account | Keanggotaan | 💎 Premium & Pembayaran |
| Membership Rules | Keanggotaan | 💎 Premium & Pembayaran |
| Premium Benefits | Keanggotaan | 💎 Premium & Pembayaran |
| Colors (2 warna) | Landing Page | 🌐 Landing Page |
| Hero Section | Landing Page | 🌐 Landing Page |
| About Section | Landing Page | 🌐 Landing Page |
| Statistik Member | Landing Page | 🌐 Landing Page |
| Background Kartu | Kartu Member | 🎫 Member Assets |
| Template Surat (9 fields) | Template Surat | 🎫 Member Assets |
| Syarat & Ketentuan | Syarat & Ketentuan | ⚖️ Legal |

## Catatan Teknis

1. **Backward Compatibility**: Tidak ada perubahan di backend, semua field tetap sama
2. **Default Tab**: `identitas-kontak` (user journey dimulai dari identitas)
3. **Preview Responsif**: Semua preview tetap responsif dan adaptive
4. **Scroll Behavior**: Tab bar dan preview container memiliki scrollbar yang styled
5. **Member Assets Full Height**: Tab ini menggunakan `preview-container--full` untuk memberikan ruang lebih besar untuk 2 preview

## Future Improvements (Opsional)

1. **Save Per Tab**: Tambahkan save button per tab untuk granular control
2. **Validation Per Tab**: Highlight tab yang memiliki error
3. **Progress Indicator**: Tampilkan progress setup (completed tabs)
4. **Tooltips**: Tambahkan help tooltips untuk field yang kompleks
