<template>
  <AdminLayout>
    <Head title="Kelola Akun - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Kelola Akun</h1>
      <Link :href="route('superadmin.kelol-akun.create')" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Buat akun baru
      </Link>
    </div>
    <div class="divider" />

    <!-- Content -->
    <div class="content-area">

      <!-- Search + Filter row -->
      <div class="toolbar">
        <div class="search-wrap">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input
            v-model="form.search"
            type="text"
            placeholder="Cari nama atau email…"
            class="input-search"
            @keyup.enter="applyFilters"
          />
          <button v-if="form.search" class="clear-btn" @click="form.search = ''; applyFilters()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <button class="btn-search" @click="applyFilters">Cari</button>

        <div class="filters">
          <!-- Role filter as toggle buttons -->
          <div class="filter-group">
            <span class="filter-label">Role</span>
            <div class="toggle-group">
              <button v-for="r in roleOptions" :key="r.value"
                :class="['toggle-btn', form.role === r.value ? 'active' : '']"
                @click="form.role = r.value; applyFilters()">
                {{ r.label }}
              </button>
            </div>
          </div>

          <!-- Status filter as toggle buttons -->
          <div class="filter-group">
            <span class="filter-label">Status</span>
            <div class="toggle-group">
              <button v-for="s in statusOptions" :key="s.value"
                :class="['toggle-btn', form.status === s.value ? 'active' : '']"
                @click="form.status = s.value; applyFilters()">
                {{ s.label }}
              </button>
            </div>
          </div>
        </div>

        <!-- old standalone button removed (now inline next to input) -->
      </div>

      <!-- Table info row -->
      <div class="table-meta">
        <span v-if="showCount" class="table-count">{{ sortedUsers.length }} akun ditemukan</span>
        <button v-if="isFiltered" class="reset-link" @click="resetFilters">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
          Reset filter
        </button>
      </div>

      <!-- Table -->
      <div class="table-wrap">
        <table class="akun-table">
          <thead>
            <tr>
              <th class="th-num">#</th>
              <th v-for="col in columns" :key="col.key"
                :class="['th-sortable', sortKey === col.key ? 'sorted' : '']"
                @click="toggleSort(col.key)">
                <span class="th-inner">
                  {{ col.label }}
                  <span class="sort-icons">
                    <svg class="sort-icon" :class="{ lit: sortKey === col.key && sortDir === 'asc' }" viewBox="0 0 10 6" fill="currentColor"><path d="M5 0L10 6H0L5 0Z"/></svg>
                    <svg class="sort-icon flip" :class="{ lit: sortKey === col.key && sortDir === 'desc' }" viewBox="0 0 10 6" fill="currentColor"><path d="M5 0L10 6H0L5 0Z"/></svg>
                  </span>
                </span>
              </th>
              <th class="th-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="sortedUsers.length === 0">
              <td :colspan="columns.length + 2" class="empty-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                Tidak ada akun ditemukan.
              </td>
            </tr>
            <tr v-for="(user, idx) in sortedUsers" :key="user.id">
              <td class="td-num">{{ idx + 1 }}</td>
              <td class="td-name">
                <div class="user-avatar">{{ user.name?.[0]?.toUpperCase() }}</div>
                <div style="display: flex; align-items: center; gap: 4px;">
                  <span>{{ user.name }}</span>
                  <svg v-if="user.is_premium" viewBox="0 0 24 24" fill="#3b82f6" style="width: 16px; height: 16px;" title="Premium Member">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </div>
              </td>
              <td>{{ user.email }}</td>
              <td>
                <span :class="['role-badge', `role-${user.role}`]">{{ roleLabel(user.role) }}</span>
              </td>
              <td>
                <span v-if="user.role === 'member'" :class="['badge', user.is_premium ? 'badge-premium' : 'badge-regular']">
                  {{ user.premium_status }}
                </span>
                <span v-else class="dash">&mdash;</span>
              </td>
              <td>
                <span v-if="user.role === 'member' && user.plan_name" class="plan-name">{{ user.plan_name }}</span>
                <span v-else class="dash">&mdash;</span>
              </td>
              <td>
                <span :class="['badge', user.is_active ? 'badge-aktif' : 'badge-nonaktif']">
                  {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="td-aksi">
                <Link :href="route('superadmin.kelol-akun.show', user.id)" class="btn-view" title="Lihat Detail">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  Detail
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
import { reactive, ref, computed } from 'vue';
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

// whether the user has triggered a search/filter action
const searchedOnce = ref(!!(props.filters && (props.filters.search || props.filters.role || props.filters.status)));

// show count only after user has searched/filtered at least once
const showCount = computed(() => searchedOnce.value);

const roleOptions = [
  { value: 'semua',   label: 'Semua' },
  { value: 'member',  label: 'Member' },
  { value: 'staff',   label: 'Petugas' },
  { value: 'finance', label: 'Keuangan' },
  { value: 'leader',  label: 'Ketua' },
];
const statusOptions = [
  { value: 'semua',    label: 'Semua' },
  { value: 'aktif',    label: 'Aktif' },
  { value: 'nonaktif', label: 'Nonaktif' },
];

const columns = [
  { key: 'name',           label: 'Nama' },
  { key: 'email',          label: 'Email' },
  { key: 'role',           label: 'Role' },
  { key: 'premium_status', label: 'Status Premium' },
  { key: 'plan_name',      label: 'Paket Premium' },
  { key: 'is_active',      label: 'Status' },
];

// Sorting state
const sortKey = ref('name');
const sortDir = ref('asc');

function toggleSort(key) {
  if (sortKey.value === key) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortDir.value = 'asc';
  }
}

const sortedUsers = computed(() => {
  const arr = [...(props.users ?? [])];
  arr.sort((a, b) => {
    let va = a[sortKey.value];
    let vb = b[sortKey.value];
    if (typeof va === 'boolean') { va = va ? 1 : 0; vb = vb ? 1 : 0; }
    if (typeof va === 'string')  { va = va.toLowerCase(); vb = vb.toLowerCase(); }
    if (va < vb) return sortDir.value === 'asc' ? -1 : 1;
    if (va > vb) return sortDir.value === 'asc' ? 1 : -1;
    return 0;
  });
  return arr;
});

const isFiltered = computed(() =>
  form.search || form.role !== 'semua' || form.status !== 'semua'
);

function applyFilters() {
  // mark that the user has actively applied filters/search
  searchedOnce.value = true;

  router.get(route('superadmin.kelol-akun.index'), {
    search: form.search || undefined,
    role:   form.role !== 'semua' ? form.role : undefined,
    status: form.status !== 'semua' ? form.status : undefined,
  }, { preserveState: true, replace: true });
}

function clearSearch() {
  form.search = '';
  searchedOnce.value = false;
  applyFilters();
}

function resetFilters() {
  form.search = '';
  form.role   = 'semua';
  form.status = 'semua';
  searchedOnce.value = false;
  applyFilters();
}

function roleLabel(role) {
  const map = { member: 'Member', staff: 'Petugas', finance: 'Keuangan', leader: 'Ketua' };
  return map[role] ?? role;
}
</script>

<style scoped>
/* Top bar */
.top-bar { display: flex; align-items: center; justify-content: space-between; padding: 18px 32px; }
.page-title { font-size: 20px; font-weight: 700; color: #111; }
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 7px; font-size: 13.5px; font-weight: 600;
  cursor: pointer; text-decoration: none; transition: filter 0.2s; border: none;
  background: var(--primary-color); color: #fff;
}
.btn-primary svg { width: 15px; height: 15px; }
.btn-primary:hover { filter: brightness(0.9); }
.divider { height: 1px; background: #e5e7eb; }

/* Content */
.content-area { padding: 24px 32px; }

/* Toolbar */
.toolbar {
  display: flex;
  align-items: flex-end;
  gap: 20px;
  flex-wrap: wrap;
  margin-bottom: 16px;
  padding: 16px 20px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
}

/* Search */
.search-wrap {
  position: relative;
  display: flex;
  align-items: center;
  flex: 1;
  min-width: 220px;
  max-width: 320px;
}
.search-icon {
  position: absolute;
  left: 10px;
  width: 15px;
  height: 15px;
  color: #9ca3af;
  pointer-events: none;
}
.input-search {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 7px;
  padding: 8px 32px 8px 34px;
  font-size: 13.5px;
  color: #111;
  outline: none;
  background: #fff;
  transition: border 0.2s;
}
.input-search:focus { border-color: var(--primary-color); }
.clear-btn {
  position: absolute;
  right: 8px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 2px;
  color: #9ca3af;
  display: flex;
}
.clear-btn svg { width: 13px; height: 13px; }
.clear-btn:hover { color: #374151; }

/* Filters */
.filters { display: flex; gap: 20px; flex-wrap: wrap; }
.filter-group { display: flex; flex-direction: column; gap: 5px; }
.filter-label { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.06em; }

/* Toggle buttons */
.toggle-group { display: flex; gap: 2px; }
.toggle-btn {
  padding: 5px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 12.5px;
  font-weight: 500;
  background: #fff;
  color: #555;
  cursor: pointer;
  transition: all 0.15s;
}
.toggle-btn:hover { border-color: var(--primary-color); color: var(--primary-color); }
.toggle-btn.active {
  background: var(--primary-color);
  border-color: var(--primary-color);
  color: #fff;
  font-weight: 600;
}

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

/* Table meta */
.table-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}
.table-count { font-size: 13px; color: #6b7280; }
.reset-link {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12.5px; color: var(--primary-color); background: none;
  border: none; cursor: pointer; font-weight: 500; padding: 0;
}
.reset-link svg { width: 13px; height: 13px; }
.reset-link:hover { text-decoration: underline; }

/* Table */
.table-wrap { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
.akun-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }

.akun-table thead tr { background: #f9fafb; }

/* Sortable header */
.th-sortable {
  padding: 11px 16px;
  text-align: left;
  font-size: 12.5px;
  font-weight: 600;
  color: #6b7280;
  border-bottom: 1px solid #e5e7eb;
  cursor: pointer;
  user-select: none;
  transition: background 0.15s, color 0.15s;
  white-space: nowrap;
}
.th-sortable:hover { background: #f0f4ff; color: var(--primary-color); }
.th-sortable.sorted { color: var(--primary-color); background: #eef2ff; }
.th-num, .th-aksi {
  padding: 11px 16px;
  font-size: 12.5px; font-weight: 600; color: #6b7280;
  border-bottom: 1px solid #e5e7eb;
  text-align: center;
}
.th-aksi { text-align: right; }

.th-inner { display: inline-flex; align-items: center; gap: 6px; }

/* Sort icons */
.sort-icons { display: inline-flex; flex-direction: column; gap: 1px; opacity: 0.3; }
.sort-icon { width: 8px; height: 5px; transition: opacity 0.15s; }
.sort-icon.flip { transform: rotate(180deg); }
.sort-icon.lit { opacity: 1; }
.th-sortable.sorted .sort-icons { opacity: 0.6; }
.th-sortable.sorted .sort-icon.lit { opacity: 1; }

/* Table body */
.akun-table td {
  padding: 12px 16px;
  color: #374151;
  border-bottom: 1px solid #f3f4f6;
  vertical-align: middle;
}
.akun-table tbody tr:last-child td { border-bottom: none; }
.akun-table tbody tr:hover { background: #fafafa; }

.td-num { text-align: center; color: #9ca3af; font-size: 12px; width: 40px; }
.td-name { display: flex; align-items: center; gap: 10px; font-weight: 500; color: #111; }
.user-avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: #dbeafe; color: var(--primary-color);
  font-size: 12px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.td-aksi { text-align: right; }

/* Empty row */
.empty-row {
  text-align: center;
  color: #9ca3af;
  padding: 48px !important;
}
.empty-row svg { width: 28px; height: 28px; display: block; margin: 0 auto 10px; opacity: 0.4; }

/* Badges */
.badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
.badge-aktif    { background: #d1fae5; color: #059669; }
.badge-nonaktif { background: #fee2e2; color: #dc2626; }

/* Role badges */
.role-badge { display: inline-block; padding: 2px 9px; border-radius: 4px; font-size: 11.5px; font-weight: 600; }
.role-member  { background: #ede9fe; color: #7c3aed; }
.role-staff   { background: #dbeafe; color: #1d4ed8; }
.role-finance { background: #fef9c3; color: #92400e; }
.role-leader  { background: #fce7f3; color: #be185d; }

.badge-premium { background: #d1fae5; color: #059669; }
.badge-regular { background: #f3f4f6; color: #6b7280; }

.plan-name { font-size: 12.5px; color: #374151; }
.dash { color: #d1d5db; }

/* View button */
.btn-view {
  display: inline-flex; align-items: center; gap: 5px;
  color: #6b7280; text-decoration: none; font-size: 13px;
  padding: 5px 10px; border-radius: 6px; border: 1px solid #e5e7eb;
  background: #fff; transition: all 0.15s;
}
.btn-view svg { width: 15px; height: 15px; }
.btn-view:hover { border-color: var(--primary-color); color: var(--primary-color); background: #eef2ff; }
</style>