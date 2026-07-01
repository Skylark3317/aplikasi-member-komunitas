# Fix Tab Bar Scroll - Halaman Pengaturan

## 30 Juni 2026

### Problem:
- Tab bar di halaman Pengaturan tidak bisa di-scroll secara horizontal dengan mouse wheel
- Scrollbar horizontal tidak terlihat
- User harus manual drag atau klik untuk navigasi antar tab

### Solution:

#### 1. Tampilkan Scrollbar Horizontal
Mengubah CSS tab bar untuk menampilkan scrollbar horizontal yang stylish:

```css
.tab-bar {
  overflow-x: auto;
  overflow-y: hidden;
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 #f1f5f9;
}

/* Webkit browsers (Chrome, Safari, Edge) */
.tab-bar::-webkit-scrollbar {
  height: 8px;
}
.tab-bar::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}
.tab-bar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
.tab-bar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
```

#### 2. Enable Mouse Wheel Horizontal Scroll
Menambahkan JavaScript handler untuk mengkonversi scroll vertical (mouse wheel) menjadi scroll horizontal:

```javascript
const tabBarRef = ref(null);

function handleTabBarScroll(e) {
  if (!tabBarRef.value) return;
  // Prevent vertical scroll and scroll horizontally instead
  if (e.deltaY !== 0) {
    e.preventDefault();
    tabBarRef.value.scrollLeft += e.deltaY;
  }
}

onMounted(() => {
  setTimeout(() => {
    if (tabBarRef.value) {
      tabBarRef.value.addEventListener('wheel', handleTabBarScroll, { passive: false });
    }
  }, 0);
});

onUnmounted(() => {
  if (tabBarRef.value) {
    tabBarRef.value.removeEventListener('wheel', handleTabBarScroll);
  }
});
```

#### 3. Template Ref
Menambahkan ref ke element tab-bar:

```html
<div class="tab-bar" ref="tabBarRef">
  <!-- tabs -->
</div>
```

### Features:
✅ Scrollbar horizontal terlihat dengan styling modern
✅ Mouse wheel scroll vertical otomatis dikonversi ke horizontal scroll
✅ Smooth scrolling experience
✅ Auto cleanup event listener saat component unmounted
✅ Responsive dan tetap berfungsi di semua viewport size

### Browser Support:
- ✅ Chrome/Edge (webkit scrollbar styling)
- ✅ Firefox (scrollbar-width & scrollbar-color)
- ✅ Safari (webkit scrollbar styling)
- ✅ All modern browsers (wheel event)

### File yang Diubah:
- `resources/js/Pages/Admin/Pengaturan.vue`
  - CSS: Update `.tab-bar` styling
  - JavaScript: Tambah `tabBarRef`, `handleTabBarScroll()`, event listeners
  - Template: Tambah `ref="tabBarRef"` ke element `.tab-bar`

### Testing:
- Build Vite berhasil tanpa error
- Scrollbar horizontal terlihat
- Mouse wheel scroll berfungsi dengan smooth
- Event listener cleanup berfungsi dengan baik
