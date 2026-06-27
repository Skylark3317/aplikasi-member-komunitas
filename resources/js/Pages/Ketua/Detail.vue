<template>
  <KetuaLayout>
    <!-- Header -->
    <div class="page-header">
      <div class="header-left">
        <Link :href="route('ketua.statistik')" class="back-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </Link>
        <h1 class="page-title">{{ title }}</h1>
        <span class="row-count">{{ filtered.length }} data</span>
      </div>
      <div class="header-right">
        <a :href="exportUrl" class="export-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="export-icon">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          Export Excel
        </a>
        <div class="search-wrap">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input v-model="search" class="search-input" placeholder="Cari data..." />
          <button v-if="search" class="search-clear" @click="search = ''">×</button>
        </div>
      </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
      <!-- Filters Bar -->
      <div class="filters-bar">
        <div class="filters-left">
          <!-- Date Range Filter -->
          <div class="filter-group">
            <label class="filter-label">Mulai Tanggal</label>
            <input type="date" v-model="startDateFilter" class="filter-input date-input" />
          </div>
          <div class="filter-group">
            <label class="filter-label">Sampai Tanggal</label>
            <input type="date" v-model="endDateFilter" class="filter-input date-input" />
          </div>

          <!-- Membership Filter (Member only) -->
          <div v-if="type === 'member'" class="filter-group">
            <label class="filter-label">Membership</label>
            <select v-model="membershipFilter" class="filter-input select-input">
              <option value="">Semua</option>
              <option value="premium">Premium</option>
              <option value="regular">Regular</option>
            </select>
          </div>

          <!-- Status Filter (Member, Pertanyaan, Payment) -->
          <div v-if="type === 'member' || type === 'pertanyaan' || type === 'payment'" class="filter-group">
            <label class="filter-label">Status</label>
            <select v-model="statusFilter" class="filter-input select-input">
              <option value="">Semua</option>
              
              <template v-if="type === 'member'">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
              </template>
              
              <template v-else-if="type === 'pertanyaan'">
                <option value="selesai">Selesai</option>
                <option value="direspond">Direspond</option>
                <option value="belum_direspond">Belum direspond</option>
              </template>
              
              <template v-else-if="type === 'payment'">
                <option value="diverifikasi">Diterima</option>
                <option value="ditolak">Ditolak</option>
                <option value="menunggu">Menunggu</option>
              </template>
            </select>
          </div>

          <!-- Content Type Filter (Konten only) -->
          <div v-if="type === 'konten'" class="filter-group">
            <label class="filter-label">Tipe Konten</label>
            <select v-model="contentTypeFilter" class="filter-input select-input">
              <option value="">Semua</option>
              <option value="video">Video</option>
              <option value="ebook">Ebook</option>
            </select>
          </div>

          <!-- Category Filter (Blog only) -->
          <div v-if="type === 'blog'" class="filter-group">
            <label class="filter-label">Kategori</label>
            <select v-model="categoryIdFilter" class="filter-input select-input">
              <option value="">Semua</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
        </div>

        <div class="filters-right">
          <!-- Clear Filters Button -->
          <button v-if="hasActiveFilters" class="clear-filters-btn" @click="resetFilters">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="clear-icon"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Reset Filter
          </button>
        </div>
      </div>

      <!-- Table Card -->
      <div class="table-card">
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th class="th-num">#</th>
                <th
                  v-for="col in columns"
                  :key="col.key"
                  :class="['th', col.sortable ? 'sortable' : '']"
                  @click="col.sortable && sort(col.key)"
                >
                  <div class="th-inner">
                    {{ col.label }}
                    <span v-if="col.sortable" class="sort-icon">
                      <svg v-if="sortKey === col.key && sortDir === 'asc'" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                      <svg v-else-if="sortKey === col.key && sortDir === 'desc'" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                      <svg v-else viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2"><polyline points="18 15 12 9 6 15"/><polyline points="6 9 12 15 18 9" opacity=".4"/></svg>
                    </span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!paginated.length">
                <td :colspan="columns.length + 1" class="empty-row">
                  <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>Tidak ada data ditemukan</span>
                  </div>
                </td>
              </tr>
              <tr v-for="(row, idx) in paginated" :key="row.id" class="data-row">
                <td class="td-num">{{ (page - 1) * perPage + idx + 1 }}</td>
                <td v-for="col in columns" :key="col.key" class="td">
                  <template v-if="col.badge">
                    <span :class="['badge', badgeClass(col.key, displayVal(row, col))]">
                      {{ displayVal(row, col) }}
                    </span>
                  </template>
                  <template v-else-if="col.link && Array.isArray(row[col.key])">
                    <div class="proof-preview-container-sm">
                      <div v-for="(url, idx) in row[col.key]" :key="idx" class="proof-preview-box-sm">
                        <img v-if="isImage(url)" :src="url" class="proof-thumb" @click.prevent="viewLarge(url)" title="Lihat"/>
                        <div v-else class="proof-doc" @click.prevent="viewLarge(url)" title="Lihat PDF">
                          <span>PDF</span>
                        </div>
                      </div>
                    </div>
                  </template>
                  <template v-else-if="col.link && row[col.key]">
                    <a :href="row[col.key]" target="_blank" style="color: #007bff; text-decoration: none; font-weight: 600;">
                      {{ col.linkLabel || 'Lihat' }}
                    </a>
                  </template>
                  <template v-else>
                    <div style="display: flex; align-items: center; gap: 4px;">
                      <span class="td-text" :title="displayVal(row, col)">{{ displayVal(row, col) }}</span>
                      <svg v-if="col.key === 'nama' && row.premium === 'Premium'" viewBox="0 0 24 24" fill="#3b82f6" style="width: 16px; height: 16px; flex-shrink: 0;" title="Premium Member">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                      </svg>
                    </div>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="pagination">
          <button class="pg-btn" :disabled="page === 1" @click="page = 1">«</button>
          <button class="pg-btn" :disabled="page === 1" @click="page--">‹</button>
          <button
            v-for="p in pageRange"
            :key="p"
            :class="['pg-btn', p === page ? 'pg-active' : '']"
            @click="page = p"
          >{{ p }}</button>
          <button class="pg-btn" :disabled="page === totalPages" @click="page++">›</button>
          <button class="pg-btn" :disabled="page === totalPages" @click="page = totalPages">»</button>
          <span class="pg-info">Hal {{ page }} dari {{ totalPages }}</span>
        </div>
      </div>
    </div>
  </KetuaLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import KetuaLayout from '@/Layouts/KetuaLayout.vue';

const props = defineProps({
  type:       String,
  title:      String,
  rows:       Array,
  columns:    Array,
  categories: { type: Array, default: () => [] },
  filters:    { type: Object, default: () => ({}) },
});

// Filters
const statusFilter = ref(props.filters.status || '');
const membershipFilter = ref(props.filters.membership || '');
const contentTypeFilter = ref(props.filters.content_type || '');
const categoryIdFilter = ref(props.filters.category_id || '');
const startDateFilter = ref(props.filters.start_date || '');
const endDateFilter = ref(props.filters.end_date || '');

const hasActiveFilters = computed(() => {
  return !!(
    statusFilter.value ||
    membershipFilter.value ||
    contentTypeFilter.value ||
    categoryIdFilter.value ||
    startDateFilter.value ||
    endDateFilter.value
  );
});

function resetFilters() {
  statusFilter.value = '';
  membershipFilter.value = '';
  contentTypeFilter.value = '';
  categoryIdFilter.value = '';
  startDateFilter.value = '';
  endDateFilter.value = '';
}

function applyFilters() {
  router.get(
    route('ketua.statistik.detail', { type: props.type }),
    {
      status: statusFilter.value || undefined,
      membership: membershipFilter.value || undefined,
      content_type: contentTypeFilter.value || undefined,
      category_id: categoryIdFilter.value || undefined,
      start_date: startDateFilter.value || undefined,
      end_date: endDateFilter.value || undefined,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
}

// Watch filters to trigger update
watch([statusFilter, membershipFilter, contentTypeFilter, categoryIdFilter, startDateFilter, endDateFilter], () => {
  applyFilters();
});

const exportUrl = computed(() => {
  return route('ketua.statistik.detail.export', {
    type: props.type,
    status: statusFilter.value || undefined,
    membership: membershipFilter.value || undefined,
    content_type: contentTypeFilter.value || undefined,
    category_id: categoryIdFilter.value || undefined,
    start_date: startDateFilter.value || undefined,
    end_date: endDateFilter.value || undefined,
  });
});

// Search
const search = ref('');

// Sort
const sortKey = ref('');
const sortDir = ref('asc');

function sort(key) {
  if (sortKey.value === key) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortDir.value = 'asc';
  }
  page.value = 1;
}

// Get display value for a column (handle _sort_xxx prefix)
function displayVal(row, col) {
  const display = col.display ?? col.key;
  return row[display] ?? '-';
}

// Filter by search
const filtered = computed(() => {
  if (!search.value.trim()) return props.rows;
  const q = search.value.toLowerCase();
  return props.rows.filter(row =>
    Object.values(row).some(v =>
      String(v).toLowerCase().includes(q)
    )
  );
});

// Sort
const sorted = computed(() => {
  if (!sortKey.value) return filtered.value;
  return [...filtered.value].sort((a, b) => {
    const av = a[sortKey.value] ?? '';
    const bv = b[sortKey.value] ?? '';
    const cmp = typeof av === 'number' ? av - bv : String(av).localeCompare(String(bv), 'id');
    return sortDir.value === 'asc' ? cmp : -cmp;
  });
});

// Pagination
const page    = ref(1);
const perPage = 20;

watch(search, () => { page.value = 1; });

const totalPages = computed(() => Math.max(1, Math.ceil(sorted.value.length / perPage)));
const paginated  = computed(() => sorted.value.slice((page.value - 1) * perPage, page.value * perPage));

const pageRange = computed(() => {
  const total = totalPages.value;
  const cur   = page.value;
  const delta = 2;
  const start = Math.max(1, cur - delta);
  const end   = Math.min(total, cur + delta);
  return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});

// Badge colors
const BADGE_COLORS = {
  'Premium':       'badge-blue',
  'Regular':       'badge-gray',
  'Aktif':         'badge-green',
  'Nonaktif':      'badge-red',
  'Selesai':       'badge-green',
  'Direspond':     'badge-blue',
  'Belum direspond': 'badge-orange',
  'Diterima':      'badge-blue',
  'Ditolak':       'badge-red',
  'Menunggu':      'badge-orange',
  'Video':         'badge-purple',
  'Ebook':         'badge-pink',
  'Berita':        'badge-amber',
  'Acara':         'badge-red',
};

function badgeClass(colKey, val) {
  if (colKey === '_sort_kelengkapan') {
    const num = parseInt(val) || 0;
    if (num === 100) return 'badge-gold';
    if (num >= 60) return 'badge-blue';
    if (num >= 40) return 'badge-orange';
    return 'badge-red';
  }
  return BADGE_COLORS[val] ?? 'badge-gray';
}

function isImage(url) {
  if (!url) return false;
  return !!url.match(/\.(jpeg|jpg|gif|png|webp)$/i);
}

function viewLarge(url) {
  window.open(url, '_blank');
}
</script>

<style scoped>
/* Restrict the layout container of KetuaLayout to the viewport size */
:deep(.admin-main) {
  height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  max-width: calc(100vw - 200px);
}

/* ── Header ── */
.page-header {
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 32px; border-bottom: 1px solid #e5e7eb; gap: 16px;
  background: #fff;
}

.content-area {
  flex: 1;
  min-height: 0;
  display: flex;
  flex-direction: column;
  padding: 20px 32px 24px;
  background: #f9fafb;
  overflow: hidden;
}
.header-left { display: flex; align-items: center; gap: 12px; }
.back-btn {
  display: flex; align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: 8px; border: 1px solid #e5e7eb;
  background: #fff; text-decoration: none; color: #374151;
  transition: background .15s;
}
.back-btn:hover { background: #f3f4f6; }
.back-btn svg { width: 18px; height: 18px; }
.page-title { font-size: 18px; font-weight: 700; color: #111; margin: 0; }
.row-count { font-size: 12px; color: #9ca3af; background: #f3f4f6; padding: 2px 8px; border-radius: 20px; }

.header-right { display: flex; align-items: center; gap: 12px; }
.export-btn {
  display: flex; align-items: center; gap: 6px;
  padding: 8px 14px; border-radius: 8px; border: 1px solid #e5e7eb;
  background: #fff; text-decoration: none; color: #374151; font-size: 13.5px;
  font-weight: 500; cursor: pointer; transition: all 0.2s ease;
}
.export-btn:hover {
  background: #f9fafb;
  border-color: var(--primary-color);
  color: var(--primary-color);
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.export-btn:active {
  transform: translateY(0);
}
.export-icon { width: 16px; height: 16px; }

/* ── Filters Bar ── */
.filters-bar {
  flex-shrink: 0;
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 16px;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  padding: 16px;
  margin-bottom: 16px;
  flex-wrap: wrap;
}
.filters-left {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
  flex-grow: 1;
}
.filters-right {
  display: flex;
  align-items: center;
}
.filter-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.filter-label {
  font-size: 11px;
  font-weight: 700;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.filter-input {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 7px 12px;
  font-size: 13px;
  color: #374151;
  background-color: #fff;
  outline: none;
  transition: all 0.15s ease;
  min-width: 130px;
  height: 36px;
}
.filter-input:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
}
.date-input {
  min-width: 145px;
}
.select-input {
  cursor: pointer;
}
.clear-filters-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #fee2e2;
  background: #fef2f2;
  color: #ef4444;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.15s ease;
  height: 36px;
}
.clear-filters-btn:hover {
  background: #fee2e2;
  color: #dc2626;
}
.clear-icon {
  width: 14px;
  height: 14px;
}

/* Search */
.search-wrap {
  position: relative; display: flex; align-items: center;
}
.search-icon { position: absolute; left: 10px; width: 16px; height: 16px; color: #9ca3af; pointer-events: none; }
.search-input {
  border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 34px 8px 34px;
  font-size: 13.5px; color: #111; outline: none; width: 240px;
  transition: border .2s;
}
.search-input:focus { border-color: var(--primary-color); }
.search-clear {
  position: absolute; right: 10px; background: none; border: none;
  font-size: 16px; color: #9ca3af; cursor: pointer; line-height: 1;
}
.search-clear:hover { color: #374151; }

/* Table Card */
.table-card {
  flex: 1;
  min-height: 0;
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}
.table-wrap {
  flex: 1;
  overflow: auto;
  background: #f9fafb;
}
.data-table { width: 100%; border-collapse: collapse; background: #fff; }

thead {
  position: sticky;
  top: 0;
  z-index: 5;
  background: #f9fafb;
}
.th-num, .td-num {
  width: 48px; text-align: center; font-size: 12px; color: #9ca3af;
  padding: 12px 8px; border-bottom: 1px solid #e5e7eb;
}
.th {
  padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600;
  color: #374151; border-bottom: 1px solid #e5e7eb; white-space: nowrap;
}
.th.sortable { cursor: pointer; user-select: none; }
.th.sortable:hover { background: #f3f4f6; }
.th-inner { display: flex; align-items: center; gap: 4px; }
.sort-icon svg { width: 14px; height: 14px; flex-shrink: 0; }

.data-row { transition: background .1s; }
.data-row:hover { background: #f9fafb; }
.data-row:not(:last-child) .td,
.data-row:not(:last-child) .td-num { border-bottom: 1px solid #f3f4f6; }

.td { padding: 12px 16px; font-size: 13.5px; color: #374151; }
.td-text { display: block; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Empty */
.empty-row { padding: 48px 16px; }
.empty-state { display: flex; flex-direction: column; align-items: center; gap: 10px; color: #9ca3af; }
.empty-state svg { width: 32px; height: 32px; }
.empty-state span { font-size: 14px; }

/* Badges */
.badge {
  display: inline-block; padding: 3px 10px; border-radius: 20px;
  font-size: 11.5px; font-weight: 600; white-space: nowrap;
}
.badge-blue   { background: #dbeafe; color: #1d4ed8; }
.badge-green  { background: #dcfce7; color: #16a34a; }
.badge-red    { background: #fee2e2; color: #dc2626; }
.badge-orange { background: #ffedd5; color: #ea580c; }
.badge-gray   { background: #f3f4f6; color: #6b7280; }
.badge-purple { background: #ede9fe; color: #7c3aed; }
.badge-pink   { background: #fdf2f8; color: #be185d; }
.badge-amber  { background: #fef3c7; color: #b45309; }
.badge-gold   { background: #fef08a; color: #a16207; }

/* Pagination */
.pagination {
  flex-shrink: 0;
  display: flex; align-items: center; gap: 4px;
  padding: 14px 16px; border-top: 1px solid #f3f4f6; flex-wrap: wrap;
  background: #fff;
}
.pg-btn {
  min-width: 32px; height: 32px; border-radius: 6px; border: 1px solid #e5e7eb;
  background: #fff; font-size: 13px; color: #374151; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s; padding: 0 8px;
}
.pg-btn:hover:not(:disabled) { background: #f3f4f6; }
.pg-btn:disabled { opacity: .4; cursor: not-allowed; }
.pg-btn.pg-active { background: var(--primary-color); color: #fff; border-color: var(--primary-color); font-weight: 700; }
.pg-info { font-size: 12px; color: #9ca3af; margin-left: 8px; }

/* Proof preview small styles for table */
.proof-preview-container-sm {
  display: flex;
  gap: 4px;
  overflow-x: auto;
  max-width: 150px;
  padding-bottom: 2px;
}

.proof-preview-box-sm {
  width: 30px;
  height: 30px;
  border-radius: 4px;
  border: 1px solid #e5e7eb;
  background: #f9fafb;
  overflow: hidden;
  cursor: pointer;
  flex-shrink: 0;
}

.proof-preview-box-sm .proof-thumb {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: opacity 0.2s;
}
.proof-preview-box-sm .proof-thumb:hover {
  opacity: 0.8;
}

.proof-preview-box-sm .proof-doc {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ef4444;
  font-size: 9px;
  font-weight: bold;
  background: #fff;
  transition: background 0.2s;
}
.proof-preview-box-sm .proof-doc:hover {
  background: #fef2f2;
}
</style>