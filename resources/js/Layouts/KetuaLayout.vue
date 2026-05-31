<template>
  <div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div v-if="settings.community_logo" class="sidebar-img">
          <img :src="`/storage/${settings.community_logo}`" alt="Logo" />
        </div>
        <span v-else class="sidebar-logo-text">AMK</span>
      </div>

      <nav class="sidebar-nav">
        <Link
          :href="route('ketua.statistik')"
          :class="['nav-item', isActive('ketua.statistik') && !isDetailRoute() ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
          <span>Statistik</span>
        </Link>
        
        <div class="sub-nav">
          <div class="sub-nav-title">Detail Statistik</div>
          <Link :href="route('ketua.statistik.detail', { type: 'member' })" :class="['sub-nav-item', isDetailType('member') ? 'active' : '']">Member</Link>
          <Link :href="route('ketua.statistik.detail', { type: 'konten' })" :class="['sub-nav-item', isDetailType('konten') ? 'active' : '']">Konten</Link>
          <Link :href="route('ketua.statistik.detail', { type: 'blog' })" :class="['sub-nav-item', isDetailType('blog') ? 'active' : '']">Blog</Link>
          <Link :href="route('ketua.statistik.detail', { type: 'pertanyaan' })" :class="['sub-nav-item', isDetailType('pertanyaan') ? 'active' : '']">Pertanyaan</Link>
          <Link :href="route('ketua.statistik.detail', { type: 'payment' })" :class="['sub-nav-item', isDetailType('payment') ? 'active' : '']">Pendapatan</Link>
        </div>
      </nav>

      <!-- User section with popup -->
      <div class="sidebar-user" @click="togglePopup" ref="userBtn">
        <span class="user-name">{{ $page.props.auth.user.name }}</span>
        <span class="user-role">Ketua</span>

        <div v-if="showPopup" class="user-popup" @click.stop>
          <Link :href="route('ketua.profil')" class="popup-item" @click="showPopup = false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            Profil
          </Link>
          <Link :href="route('logout')" method="post" as="button" class="popup-item logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Log Out
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const $page = usePage();
const showPopup = ref(false);
const userBtn = ref(null);
const settings = computed(() => $page.props.settings || {});

function togglePopup() { showPopup.value = !showPopup.value; }
function closePopup(e) {
  if (userBtn.value && !userBtn.value.contains(e.target)) showPopup.value = false;
}
function isActive(routeName) {
  const current = $page.url;
  if (routeName === 'ketua.statistik') return current.startsWith('/ketua/statistik');
  return false;
}

function isDetailRoute() {
  return $page.url.startsWith('/ketua/statistik/detail');
}

function isDetailType(type) {
  return $page.url.startsWith(`/ketua/statistik/detail/${type}`);
}

onMounted(() => document.addEventListener('click', closePopup));
onBeforeUnmount(() => document.removeEventListener('click', closePopup));
</script>

<style scoped>
/* ── Layout ── */
.admin-wrapper {
  display: flex;
  min-height: 100vh;
  background: #f9fafb;
  font-family: 'Inter', sans-serif;
}

/* ── Sidebar ── */
.sidebar {
  width: 200px;
  background: #fff;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  padding: 12px 0 0;
  position: fixed;
  top: 0; bottom: 0; left: 0;
  z-index: 50;
}

.sidebar-brand {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 0 12px;
  margin: 0 0 16px;
  text-align: center;
}
.sidebar-img { width: 100%; display: flex; justify-content: center; }
.sidebar-img img { width: 100%; max-height: 80px; object-fit: contain; }
.sidebar-logo-text {
  font-weight: 800;
  font-size: 14px;
  letter-spacing: 1px;
  color: #111;
}

/* Nav */
.sidebar-nav { flex: 1; }
.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 20px;
  font-size: 13.5px;
  color: #555;
  text-decoration: none;
  transition: background 0.15s, color 0.15s;
  cursor: pointer;
}
.nav-item:hover { background: #f0f4ff; color: var(--primary-color); }
.nav-item.active { background: #f3f4f6; color: var(--primary-color); font-weight: 600; }
.nav-icon { width: 18px; height: 18px; flex-shrink: 0; }

.sub-nav {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
}
.sub-nav-title {
  font-size: 11px;
  font-weight: 700;
  color: #9ca3af;
  text-transform: uppercase;
  padding: 8px 20px 4px;
  letter-spacing: 0.05em;
}
.sub-nav-item {
  padding: 8px 20px 8px 48px;
  font-size: 13px;
  color: #6b7280;
  text-decoration: none;
  transition: all 0.15s;
}
.sub-nav-item:hover { color: var(--primary-color); background: #f9fafb; }
.sub-nav-item.active { color: var(--primary-color); font-weight: 600; background: #f3f4f6; position: relative; }
.sub-nav-item.active::before {
  content: '';
  position: absolute;
  left: 36px;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: var(--primary-color);
}

/* User */
.sidebar-user {
  position: relative;
  padding: 16px 20px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  cursor: pointer;
  user-select: none;
}
.sidebar-user:hover { background: #f9fafb; }
.user-name { font-weight: 600; font-size: 13.5px; color: #111; }
.user-role {
  font-size: 11px;
  background: #dbeafe;
  color: var(--primary-color);
  padding: 2px 9px;
  border-radius: 10px;
  display: inline-block;
  margin-top: 5px;
  font-weight: 500;
  width: fit-content;
}

/* Popup */
.user-popup {
  position: absolute;
  bottom: calc(100% + 6px);
  left: 12px; right: 12px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.10);
  overflow: hidden;
  z-index: 100;
}
.popup-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 16px;
  font-size: 13px;
  color: #333;
  text-decoration: none;
  cursor: pointer;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  transition: background 0.15s;
}
.popup-item:hover { background: #f3f4f6; }
.popup-item svg { width: 16px; height: 16px; }
.popup-item.logout { color: #ef4444; }
.popup-item.logout:hover { background: #fef2f2; }

/* ── Main ── */
.admin-main {
  margin-left: 200px;
  flex: 1;
  min-height: 100vh;
}

/* Print */
@media print {
  @page { size: portrait; margin: 1cm; }
  body { background: #fff !important; }
  .sidebar { display: none !important; }
  .admin-wrapper { min-height: auto !important; display: block !important; width: 100% !important; }
  .admin-main { margin-left: 0 !important; width: 100% !important; padding: 0 !important; display: block !important; }
}
</style>