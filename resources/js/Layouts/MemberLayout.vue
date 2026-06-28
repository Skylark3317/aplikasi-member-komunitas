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
        <!-- Konten -->
        <Link
          :href="route('member.konten.index')"
          :class="['nav-item', isActive('member.konten') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <line x1="3" y1="9" x2="21" y2="9"/>
            <line x1="9" y1="21" x2="9" y2="9"/>
          </svg>
          <span>Konten</span>
        </Link>

        <!-- Pertanyaan -->
        <Link
          :href="route('member.pertanyaan.index')"
          :class="['nav-item', isActive('member.pertanyaan') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
          <span>Pertanyaan</span>
        </Link>

        <!-- Pembayaran -->
        <Link
          :href="route('member.premium.payment')"
          :class="['nav-item', isActive('member.pembayaran') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="2" y="5" width="20" height="14" rx="2" ry="2"/>
            <line x1="2" y1="10" x2="22" y2="10"/>
          </svg>
          <span>Pembayaran</span>
        </Link>

        <!-- Home -->
        <Link
          :href="route('home')"
          :class="['nav-item', isActive('home') ? 'active' : '']"
        >
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
          <span>Home</span>
        </Link>
      </nav>

      <!-- Gabung Member Premium (only if not premium) -->
      <div v-if="!$page.props.auth.user.is_premium" class="sidebar-premium-btn-wrapper">
        <Link :href="route('member.premium.index')" class="btn-sidebar-premium">
          <svg class="lightning-icon" viewBox="0 0 24 24" fill="currentColor">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
          </svg>
          <span>Gabung Member Premium</span>
        </Link>
      </div>

      <!-- User section with popup -->
      <div class="sidebar-user" @click="togglePopup" ref="userBtn">
        <div class="user-avatar-wrapper">
          <img v-if="$page.props.auth.user.avatar_url" :src="$page.props.auth.user.avatar_url" alt="Avatar" class="sidebar-avatar-img" />
          <div v-else class="user-avatar-initial">
            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
          </div>
        </div>
        
        <div class="user-info-text">
          <span class="user-name">
            {{ $page.props.auth.user.name }}
            <i v-if="$page.props.auth.user.profile_completion_percent === 100" class="bi bi-patch-check-fill" style="color: #0d6efd; margin-left: 4px; font-size: 14px;" title="Profil Lengkap"></i>
          </span>
          <span :class="['user-role', $page.props.auth.user.is_premium ? 'role-premium' : 'role-biasa']">
            {{ $page.props.auth.user.is_premium ? 'Member Premium' : 'Member' }}
          </span>
        </div>

        <!-- Popup menu -->
        <div v-if="showPopup" class="user-popup" @click.stop>
          <Link :href="route('member.profil.show')" class="popup-item" @click="showPopup = false">
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
      <div v-if="showExpiringAlert" class="membership-alert">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div class="alert-content">
          <strong>Perhatian:</strong> Masa aktif Member Premium Anda akan berakhir dalam <strong>{{ daysRemaining }} hari</strong> ({{ formattedExpireDate }}). Segera perpanjang agar akses tidak terputus.
        </div>
        <Link :href="route('member.premium.index')" class="alert-action">Perpanjang Sekarang</Link>
      </div>
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
const memberProfile = computed(() => $page.props.memberProfile);

const daysRemaining = computed(() => {
  if (!memberProfile.value || !memberProfile.value.expire_date) return null;
  const expireDate = new Date(memberProfile.value.expire_date);
  const now = new Date();
  const diffTime = expireDate - now;
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
});

const formattedExpireDate = computed(() => {
  if (!memberProfile.value || !memberProfile.value.expire_date) return '';
  const d = new Date(memberProfile.value.expire_date);
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
});

const showExpiringAlert = computed(() => {
  if (!$page.props.auth.user.is_premium) return false;
  const alertDays = parseInt(settings.value.membership_alert_days || '7');
  return daysRemaining.value !== null && daysRemaining.value > 0 && daysRemaining.value <= alertDays;
});

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
  if (routeName === 'member.konten') return current.startsWith('/member/konten');
  if (routeName === 'member.profil') return current.startsWith('/member/profil');
  if (routeName === 'member.pertanyaan') return current.startsWith('/member/pertanyaan');
  if (routeName === 'member.pembayaran') return current.startsWith('/member/premium/pembayaran');
  if (routeName === 'member.premium') return current.startsWith('/member/premium') && !current.startsWith('/member/premium/pembayaran');
  if (routeName === 'home') return current === '/';
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
  color: var(--primary-color);
  text-decoration: none;
  transition: background 0.15s, color 0.15s;
  cursor: pointer;
}
.nav-item:hover {
  background: var(--surface-color);
  color: var(--primary-color);
}
.nav-item.active {
  background: var(--surface-color);
  color: var(--primary-color);
  font-weight: 600;
}

.nav-icon {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

.premium-link-text {
  color: #ffcc92;
  font-weight: 600;
}

/* ── Premium Sidebar Button ── */
.sidebar-premium-btn-wrapper {
  padding: 12px 16px;
}
.btn-sidebar-premium {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #007bff;
  color: #fff;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.15s;
  width: 100%;
  box-sizing: border-box;
}
.btn-sidebar-premium:hover {
  background: #0056b3;
}
.lightning-icon {
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}

/* ── User section ── */
.sidebar-user {
  position: relative;
  padding: 16px 20px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  user-select: none;
}
.sidebar-user:hover { background: #f9fafb; }

.user-avatar-wrapper {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  overflow: hidden;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  border: 1px solid #d1d5db;
}

.user-avatar-initial {
  font-weight: 700;
  font-size: 14px;
  color: #4b5563;
}

.sidebar-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-info-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.user-name { font-weight: 600; font-size: 13px; color: #111; line-height: 1.2; }

.user-role {
  font-size: 10px;
  padding: 1px 7px;
  border-radius: 10px;
  display: inline-block;
  font-weight: 500;
  width: fit-content;
}

.role-biasa {
  background: var(--surface-color);
  color: var(--primary-color);
}

.role-premium {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  color: var(--primary-color);
  border: 1.5px solid #f59e0b;
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
  display: flex;
  flex-direction: column;
}

/* ── Alert ── */
.membership-alert {
  background: #fffbeb;
  border-left: 4px solid #f59e0b;
  border-bottom: 1px solid #fde68a;
  padding: 12px 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  color: #92400e;
  font-size: 13.5px;
}
.membership-alert svg { width: 20px; height: 20px; color: #f59e0b; flex-shrink: 0; }
.alert-content { flex: 1; }
.alert-action {
  background: #f59e0b;
  color: #fff;
  padding: 6px 14px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 12.5px;
  text-decoration: none;
  transition: filter 0.2s;
  white-space: nowrap;
}
.alert-action:hover { filter: brightness(0.9); }
</style>
