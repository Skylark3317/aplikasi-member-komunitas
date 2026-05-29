<template>
  <AdminLayout>
    <Head :title="`Detail Akun - ${user.name}`" />

    <!-- Top Bar -->
    <div class="top-bar">
      <Link :href="route('superadmin.kelol-akun.index')" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        <h1 class="page-title">Detail Akun</h1>
      </Link>
    </div>
    <div class="divider" />

    <!-- Content -->
    <div class="content-area">
      <!-- Header -->
      <div class="user-header">
        <div class="avatar">
          <img v-if="user.avatar" :src="user.avatar" alt="Avatar" />
          <span v-else class="avatar-initials">{{ initials }}</span>
        </div>
        <div class="user-meta">
          <h2 class="user-name">{{ user.name }}</h2>
          <div class="user-sub-info">
            <span v-if="user.member_profile?.institution" class="sub-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
              </svg>
              {{ user.member_profile.institution }}
            </span>
            <span v-if="user.member_profile?.department" class="sub-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4l3 3"/>
              </svg>
              {{ user.member_profile.department }}
            </span>
            <span class="sub-item email">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
              </svg>
              {{ user.email }}
            </span>
          </div>

          <!-- Member info cards -->
          <div v-if="user.member_profile" class="info-cards">
            <div class="info-card">
              <div class="card-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <rect x="2" y="7" width="20" height="14" rx="2"/>
                  <path d="M16 3l-4-2-4 2"/>
                </svg>
              </div>
              <div>
                <div class="card-val">{{ user.member_profile.member_number }}</div>
                <div class="card-key">No. Anggota</div>
              </div>
            </div>

            <div class="info-card">
              <div class="card-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                  <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
              </div>
              <div>
                <div class="card-val">{{ user.is_active ? 'Aktif' : 'Nonaktif' }}</div>
                <div class="card-key">Status</div>
              </div>
            </div>

            <div class="info-card">
              <div class="card-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
              </div>
              <div>
                <div class="card-val">{{ user.created_at }}</div>
                <div class="card-key">Bergabung Sejak</div>
              </div>
            </div>

            <div class="info-card">
              <div class="card-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
              <div>
                <div class="card-val">{{ Math.round(user.member_profile.days_remaining) }} hari lagi</div>
                <div class="card-key">Sisa Masa Aktif Membership</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Personal Info -->
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
          <div class="info-key">Role</div>
          <div class="info-val">{{ roleLabel(user.role) }}</div>
        </div>

        <!-- Member-only fields -->
        <template v-if="user.member_profile">
          <div class="info-row">
            <div class="info-key">Nomor Anggota</div>
            <div class="info-val">{{ user.member_profile.member_number }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Status</div>
            <div class="info-val">
              <span :class="['badge', user.is_active ? 'badge-aktif' : 'badge-nonaktif']">
                {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </div>
          </div>
          <div class="info-row">
            <div class="info-key">Bergabung Sejak</div>
            <div class="info-val">{{ user.created_at }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Membership Hingga</div>
            <div class="info-val">{{ user.member_profile.expire_date }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Institusi</div>
            <div class="info-val">{{ user.member_profile.institution ?? '-' }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Jurusan</div>
            <div class="info-val">{{ user.member_profile.department ?? '-' }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Jenis Kelamin</div>
            <div class="info-val">{{ user.member_profile.gender ?? '-' }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Golongan Darah</div>
            <div class="info-val">{{ user.member_profile.blood_type ?? '-' }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Pendidikan Terakhir</div>
            <div class="info-val">{{ user.member_profile.last_education ?? '-' }}</div>
          </div>
          <div class="info-row">
            <div class="info-key">Alamat Rumah</div>
            <div class="info-val">{{ user.member_profile.address ?? '-' }}</div>
          </div>
        </template>

        <!-- Non-member fields -->
        <template v-else>
          <div class="info-row">
            <div class="info-key">Status</div>
            <div class="info-val">
              <span :class="['badge', user.is_active ? 'badge-aktif' : 'badge-nonaktif']">
                {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </div>
          </div>
          <div class="info-row">
            <div class="info-key">Bergabung Sejak</div>
            <div class="info-val">{{ user.created_at }}</div>
          </div>
        </template>

        <div class="info-row">
          <div class="info-key">Nomor Telepon</div>
          <div class="info-val">{{ user.telephone }}</div>
        </div>
      </div>

      <!-- Actions -->
      <div class="action-buttons">
        <!-- Toggle status button -->
        <form @submit.prevent="toggleStatus">
          <button
            type="submit"
            :class="['btn-toggle', user.is_active ? 'btn-nonaktif' : 'btn-aktif']"
            :disabled="user.id === $page.props.auth.user.id"
            :title="user.id === $page.props.auth.user.id ? 'Anda tidak bisa mengubah status Anda sendiri' : ''"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <line x1="15" y1="9" x2="9" y2="15"/>
              <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{ user.is_active ? 'Nonaktifkan akun' : 'Aktifkan akun' }}
          </button>
        </form>

        <!-- Delete button -->
        <form v-if="user.role !== 'member'" @submit.prevent="deleteAccount">
          <button
            type="submit"
            class="btn-toggle btn-nonaktif"
            :disabled="user.id === $page.props.auth.user.id"
            :title="user.id === $page.props.auth.user.id ? 'Anda tidak bisa menghapus akun Anda sendiri' : ''"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
            Hapus akun
          </button>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  user: Object,
});

const initials = computed(() => {
  return props.user.name
    .split(' ')
    .slice(0, 2)
    .map(w => w[0])
    .join('')
    .toUpperCase();
});

function roleLabel(role) {
  const map = {
    member:      'Member',
    staff:       'Petugas',
    finance:     'Keuangan',
    leader:      'Ketua',
    super_admin: 'Super Admin',
  };
  return map[role] ?? role;
}

function toggleStatus() {
  if (!confirm(`Yakin ingin ${props.user.is_active ? 'menonaktifkan' : 'mengaktifkan'} akun ini?`)) return;
  router.patch(route('superadmin.kelol-akun.toggle-status', props.user.id));
}

function deleteAccount() {
  if (!confirm('Yakin ingin menghapus akun ini secara permanen?')) return;
  router.delete(route('superadmin.kelol-akun.destroy', props.user.id));
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  display: flex;
  align-items: center;
  padding: 18px 32px;
}
.back-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  text-decoration: none;
  color: #111;
}
.back-btn svg { width: 18px; height: 18px; color: #555; }
.page-title { font-size: 20px; font-weight: 700; color: #111; }

.divider { height: 1px; background: #e5e7eb; }
.content-area { padding: 28px 32px; }

/* User header */
.user-header { display: flex; gap: 24px; margin-bottom: 32px; align-items: flex-start; }

.avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  background: #dbeafe;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-initials { font-size: 26px; font-weight: 700; color: var(--primary-color); }

.user-meta { flex: 1; }
.user-name { font-size: 20px; font-weight: 700; color: #111; margin-bottom: 6px; }

.user-sub-info { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
.sub-item {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 13px;
  color: #6b7280;
}
.sub-item svg { width: 14px; height: 14px; }
.sub-item.email { color: #6b7280; }

/* Info cards */
.info-cards { display: flex; gap: 12px; flex-wrap: wrap; }
.info-card {
  display: flex;
  align-items: center;
  gap: 12px;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 12px 16px;
  min-width: 160px;
  background: #fff;
}
.card-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.card-icon svg { width: 18px; height: 18px; }
.card-icon.green  { background: #d1fae5; color: #059669; }
.card-icon.blue   { background: #dbeafe; color: var(--primary-color); }
.card-icon.purple { background: #ede9fe; color: #7c3aed; }
.card-icon.red    { background: #fee2e2; color: #ef4444; }

.card-val { font-size: 13.5px; font-weight: 700; color: #111; }
.card-key { font-size: 11.5px; color: #9ca3af; margin-top: 2px; }

/* Section title */
.section-title {
  font-size: 17px;
  font-weight: 700;
  color: #111;
  margin-bottom: 18px;
}

/* Info table */
.info-table { display: flex; flex-direction: column; gap: 0; margin-bottom: 24px; }
.info-row {
  display: flex;
  padding: 13px 0;
  border-bottom: 1px solid #f3f4f6;
  gap: 16px;
}
.info-row:last-child { border-bottom: none; }
.info-key { width: 200px; font-size: 13.5px; color: #6b7280; flex-shrink: 0; }
.info-val { font-size: 13.5px; color: #111; }

/* Badge */
.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}
.badge-aktif    { background: #d1fae5; color: #059669; }
.badge-nonaktif { background: #fee2e2; color: #dc2626; }

/* Actions */
.action-buttons {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}

.btn-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s;
}
.btn-toggle:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.btn-toggle svg { width: 16px; height: 16px; }
.btn-toggle:hover:not(:disabled) { opacity: 0.85; }

.btn-nonaktif { background: #ef4444; color: #fff; }
.btn-aktif    { background: #22c55e; color: #fff; }
</style>



