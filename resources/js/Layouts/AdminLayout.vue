<template>
  <div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-logo">AMK</div>
      <nav class="sidebar-nav">
        <Link :href="route('admin.statistik')" class="nav-item active">
          <span class="nav-icon">📊</span>
          <span>Statistik</span>
        </Link>
      </nav>
      <div class="sidebar-user">
        <span class="user-name">{{ $page.props.auth.user.name }}</span>
        <span class="user-role">{{ $page.props.auth.user.role }}</span>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const $page = usePage();
</script>

<style scoped>
.admin-wrapper {
  display: flex;
  min-height: 100vh;
  background: #f3f4f6;
}
.sidebar {
  width: 200px;
  background: #fff;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  padding: 24px 0;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  z-index: 50;
}
.sidebar-logo {
  font-weight: 700;
  font-size: 18px;
  letter-spacing: 2px;
  border: 2px solid #111;
  padding: 4px 12px;
  display: inline-block;
  margin: 0 20px 32px;
}
.sidebar-nav { flex: 1; }
.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 20px;
  font-size: 14px;
  color: #555;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.nav-item:hover, .nav-item.active {
  background: #eff6ff;
  color: #2563eb;
  font-weight: 600;
}
.nav-icon { font-size: 16px; }
.sidebar-user {
  padding: 16px 20px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
}
.user-name { font-weight: 600; font-size: 14px; color: #111; }
.user-role {
  font-size: 11px;
  background: #2563eb;
  color: #fff;
  padding: 2px 8px;
  border-radius: 10px;
  display: inline-block;
  margin-top: 4px;
  text-transform: capitalize;
}
.admin-main {
  margin-left: 200px;
  flex: 1;
  padding: 32px;
  min-height: 100vh;
}
</style>
