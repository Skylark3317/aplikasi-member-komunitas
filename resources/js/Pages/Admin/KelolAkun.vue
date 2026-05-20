<template>
  <AdminLayout>
    <Head title="Kelola Akun - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Kelola Akun</h1>
      <Link :href="route('superadmin.kelol-akun.create')" class="btn-primary">
        Buat akun baru
      </Link>
    </div>
    <div class="divider" />

    <!-- Content -->
    <div class="content-area">
      <!-- Search -->
      <div class="section-label">Cari Akun</div>
      <div class="search-row">
        <input
          v-model="form.search"
          type="text"
          placeholder="Nama lengkap atau email akun"
          class="input-search"
          @keyup.enter="applyFilters"
        />
        <button class="btn-search" @click="applyFilters">Cari</button>
      </div>

      <!-- Filters -->
      <div class="filter-row">
        <div class="select-wrap">
          <span class="select-label">Role</span>
          <select v-model="form.role" class="select-filter" @change="applyFilters">
            <option value="semua">Semua</option>
            <option value="member">Member</option>
            <option value="staff">Petugas</option>
            <option value="finance">Keuangan</option>
            <option value="leader">Ketua</option>
          </select>
          <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </div>

        <div class="select-wrap">
          <span class="select-label">Status</span>
          <select v-model="form.status" class="select-filter" @change="applyFilters">
            <option value="semua">Semua</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
          <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </div>
      </div>

      <!-- Table -->
      <div class="table-wrap">
        <table class="akun-table">
          <thead>
            <tr>
              <th>Nama Lengkap</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="users.length === 0">
              <td colspan="5" class="empty-row">Tidak ada akun ditemukan.</td>
            </tr>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ roleLabel(user.role) }}</td>
              <td>
                <span :class="['badge', user.is_active ? 'badge-aktif' : 'badge-nonaktif']">
                  {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td>
                <Link :href="route('superadmin.kelol-akun.show', user.id)" class="btn-view" title="Lihat Detail">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  users:   Array,
  filters: Object,
});

const form = reactive({
  search: props.filters?.search ?? '',
  role:   props.filters?.role   ?? 'semua',
  status: props.filters?.status ?? 'semua',
});

function applyFilters() {
  router.get(route('superadmin.kelol-akun.index'), {
    search: form.search || undefined,
    role:   form.role !== 'semua' ? form.role : undefined,
    status: form.status !== 'semua' ? form.status : undefined,
  }, { preserveState: true, replace: true });
}

function roleLabel(role) {
  const map = {
    member:      'Member',
    staff:       'Petugas',
    finance:     'Keuangan',
    leader:      'Ketua',
  };
  return map[role] ?? role;
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 32px;
}
.page-title {
  font-size: 20px;
  font-weight: 700;
  color: #111;
}
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
  transition: filter 0.2s;
  border: 1px solid transparent;
  box-sizing: border-box;
  background: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
}
.btn-primary:hover { filter: brightness(0.9); }

.divider { height: 1px; background: #e5e7eb; }

/* Content */
.content-area { padding: 28px 32px; }

.section-label {
  font-size: 13.5px;
  font-weight: 600;
  color: #111;
  margin-bottom: 8px;
}

/* Search */
.search-row { display: flex; gap: 10px; margin-bottom: 16px; }
.input-search {
  flex: 1;
  max-width: 440px;
  border: 1px solid #d1d5db;
  border-radius: 7px;
  padding: 9px 14px;
  font-size: 13.5px;
  color: #111;
  outline: none;
  transition: border 0.2s;
}
.input-search:focus { border-color: var(--primary-color); }
.btn-search {
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 9px 20px;
  border-radius: 7px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-search:hover { background: var(--primary-color); }

/* Filters */
.filter-row { display: flex; gap: 10px; margin-bottom: 24px; }
.select-wrap {
  position: relative;
  display: flex;
  align-items: center;
  gap: 6px;
  border: 1px solid #d1d5db;
  border-radius: 7px;
  padding: 7px 0 7px 12px;
  background: #fff;
  cursor: pointer;
}
.select-label {
  font-size: 13px;
  color: #555;
  white-space: nowrap;
}
.select-filter {
  border: none;
  outline: none;
  font-size: 13px;
  color: #111;
  background: transparent;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  padding-right: 32px;
  width: 100%;
}
.select-arrow {
  position: absolute;
  right: 8px;
  width: 14px;
  height: 14px;
  color: #888;
  pointer-events: none;
}

/* Table */
.table-wrap {
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
}
.akun-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13.5px;
}
.akun-table thead tr {
  background: #f9fafb;
}
.akun-table th {
  padding: 12px 18px;
  text-align: left;
  font-weight: 600;
  color: #374151;
  font-size: 13px;
  border-bottom: 1px solid #e5e7eb;
}
.akun-table td {
  padding: 13px 18px;
  color: #374151;
  border-bottom: 1px solid #f3f4f6;
}
.akun-table tbody tr:last-child td { border-bottom: none; }
.akun-table tbody tr:hover { background: #fafafa; }

.empty-row {
  text-align: center;
  color: #9ca3af;
  padding: 32px !important;
}

/* Badge */
.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}
.badge-aktif   { background: #d1fae5; color: #059669; }
.badge-nonaktif { background: #fee2e2; color: #dc2626; }

/* View button */
.btn-view {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #6b7280;
  transition: color 0.2s;
  text-decoration: none;
}
.btn-view:hover { color: var(--primary-color); }
.btn-view svg { width: 18px; height: 18px; }
</style>



