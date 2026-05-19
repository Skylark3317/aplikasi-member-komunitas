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
          :href="route('petugas.konten.index')"
          :class="['nav-item', isActive('petugas.konten') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <line x1="3" y1="9" x2="21" y2="9"/>
            <line x1="9" y1="21" x2="9" y2="9"/>
          </svg>
          <span>Konten</span>
        </Link>

        <Link
          :href="route('petugas.blog.index')"
          :class="['nav-item', isActive('petugas.blog') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
          </svg>
          <span>Blog</span>
        </Link>

        <Link
          :href="route('petugas.pertanyaan.index')"
          :class="['nav-item', isActive('petugas.pertanyaan') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
          <span>Pertanyaan</span>
        </Link>
      </nav>

      <!-- User section with popup -->
      <div class="sidebar-user" @click="togglePopup" ref="userBtn">
        <span class="user-name">{{ $page.props.auth.user.name }}</span>
        <span class="user-role">Petugas</span>

        <!-- Popup menu -->
        <div v-if="showPopup" class="user-popup" @click.stop>
          <Link :href="route('petugas.profil')" class="popup-item" @click="showPopup = false">
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
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const $page = usePage();
const showPopup = ref(false);
const userBtn = ref(null);

const settings = computed(() => $page.props.settings || {});

function togglePopup() {
  showPopup.value = !showPopup.value;
}

function closePopup(e) {
  if (userBtn.value && !userBtn.value.contains(e.target)) {
    showPopup.value = false;
  }
}

function isActive(routeName) {
  const current = $page.url;
  if (routeName === 'petugas.konten') return current.startsWith('/petugas/konten');
  if (routeName === 'petugas.blog') return current.startsWith('/petugas/blog');
  if (routeName === 'petugas.pertanyaan') return current.startsWith('/petugas/pertanyaan');
  return false;
}

onMounted(() => document.addEventListener('click', closePopup));
onBeforeUnmount(() => document.removeEventListener('click', closePopup));
</script>

<style scoped>
.admin-wrapper {
  display: flex;
  min-height: 100vh;
  background: #f9fafb;
  font-family: 'Inter', sans-serif;
}

/* ── Sidebar ── */
.sidebar {
  width: 220px;
  background: #fff;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  padding: 12px 0 0;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
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

.sidebar-img {
  width: 100%;
  display: flex;
  justify-content: center;
}

.sidebar-img img {
  width: 100%;
  max-height: 80px;
  object-fit: contain;
}

.sidebar-logo-text {
  font-weight: 800;
  font-size: 14px;
  letter-spacing: 1px;
  color: #111;
}

/* ── Nav ── */
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
.nav-item:hover {
  background: #f0f4ff;
  color: var(--primary-color);
}
.nav-item.active {
  background: #f3f4f6;
  color: var(--primary-color);
  font-weight: 600;
}

.nav-icon {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

/* ── User section ── */
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

/* ── Popup ── */
.user-popup {
  position: absolute;
  bottom: calc(100% + 6px);
  left: 12px;
  right: 12px;
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
  margin-left: 220px;
  flex: 1;
  min-height: 100vh;
}
</style>



