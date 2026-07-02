<template>
  <PetugasLayout>
    <Head title="Profil - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Profil</h1>
      <Link :href="route('petugas.profil.edit')" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        Edit profil
      </Link>
    </div>
    <div class="divider" />

    <!-- Content -->
    <div class="content-area">
      <h2 class="user-name">{{ user.name }}</h2>
      <div class="user-email">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
        {{ user.email }}
      </div>

      <h3 class="section-title">Informasi Pribadi</h3>

      <div class="info-table">
        <div class="info-row">
          <div class="info-key">Nama Lengkap</div>
          <div class="info-val">{{ user.name }}</div>
        </div>
        <div class="info-row">
          <div class="info-key">Email</div>
          <div class="info-val">{{ user.email }}</div>
        </div>
        <div class="info-row">
          <div class="info-key">Nomor Telepon</div>
          <div class="info-val">{{ user.telephone || '-' }}</div>
        </div>
        <div class="info-row">
          <div class="info-key">Role</div>
          <div class="info-val">Petugas</div>
        </div>
        <div class="info-row">
          <div class="info-key">Status</div>
          <div class="info-val">
            <span class="badge badge-aktif">Aktif</span>
          </div>
        </div>
        <div class="info-row">
          <div class="info-key">Bergabung Sejak</div>
          <div class="info-val">{{ new Date(user.created_at).toLocaleDateString() }}</div>
        </div>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { onMounted, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

defineProps({
  user: Object,
});

// Animations
onMounted(() => {
  nextTick(() => {
    // Top bar animation
    const topBar = document.querySelector('.top-bar');
    if (topBar) {
      setTimeout(() => {
      }, 50);
    }

    // User info animation
    const userName = document.querySelector('.user-name');
    const userEmail = document.querySelector('.user-email');
    if (userName) {
      setTimeout(() => {
      }, 200);
    }
    if (userEmail) {
      setTimeout(() => {
      }, 300);
    }

    // Section title animation
    const sectionTitle = document.querySelector('.section-title');
    if (sectionTitle) {
      setTimeout(() => {
      }, 400);
    }

    // Info rows stagger animation
    const infoRows = document.querySelectorAll('.info-row');
    infoRows.forEach((row, index) => {
      setTimeout(() => {
      }, 500 + index * 80);
    });
  });
});
</script>

<style scoped>
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 32px;
}
.page-title { font-size: 20px; font-weight: 700; color: #111; }

.btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  line-height: 1;
  height: 38px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  border: 1px solid transparent;
  box-sizing: border-box;
  background: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
}
.btn-primary:hover { 
  filter: brightness(0.9);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 123, 255, 0.3);
}
.btn-primary svg { width: 16px; height: 16px; }

.divider { height: 1px; background: #e5e7eb; }
.content-area { padding: 28px 32px; }

.user-name { font-size: 20px; font-weight: 700; color: #111; margin-bottom: 6px; }
.user-email {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13.5px;
  color: #9ca3af;
  margin-bottom: 28px;
}
.user-email svg { width: 15px; height: 15px; }

.section-title {
  font-size: 17px;
  font-weight: 700;
  color: #111;
  margin-bottom: 18px;
}

.info-table { display: flex; flex-direction: column; }
.info-row {
  display: flex;
  padding: 13px 0;
  border-bottom: 1px solid #f3f4f6;
  gap: 16px;
  transition: all 0.3s ease;
}
.info-row:hover {
  background: #f9fafb;
  padding-left: 12px;
  margin-left: -12px;
  padding-right: 12px;
  margin-right: -12px;
  border-radius: 8px;
}
.info-row:last-child { border-bottom: none; }
.info-key { width: 200px; font-size: 13.5px; color: #6b7280; flex-shrink: 0; }
.info-val { font-size: 13.5px; color: #111; }

.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}
.badge-aktif { background: #d1fae5; color: #059669; }

/* ── Responsive ── */
@media (max-width: 1024px) {
  .top-bar { padding: 12px 16px; }
  .page-title { font-size: 16px; }
  .btn-primary { font-size: 12px; padding: 7px 12px; height: auto; }
  .content-area { padding: 16px; }
  .info-key { width: 130px; font-size: 12.5px; }
  .info-val { font-size: 12.5px; }
}
</style>
