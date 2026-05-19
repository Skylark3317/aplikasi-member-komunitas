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
        <div class="search-wrap">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input v-model="search" class="search-input" placeholder="Cari data..." />
          <button v-if="search" class="search-clear" @click="search = ''">×</button>
        </div>
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
                <template v-else>
                  <span class="td-text" :title="displayVal(row, col)">{{ displayVal(row, col) }}</span>
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
  </KetuaLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import KetuaLayout from '@/Layouts/KetuaLayout.vue';

const props = defineProps({
  type:    String,
  title:   String,
  rows:    Array,
  columns: Array,
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
  return BADGE_COLORS[val] ?? 'badge-gray';
}
</script>

<style scoped>
/* ── Header ── */
.page-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; gap: 16px;
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
.table-card { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden; }
.table-wrap { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }

thead { background: #f9fafb; }
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

/* Pagination */
.pagination {
  display: flex; align-items: center; gap: 4px;
  padding: 14px 16px; border-top: 1px solid #f3f4f6; flex-wrap: wrap;
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
</style>
