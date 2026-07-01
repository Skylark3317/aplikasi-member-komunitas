<template>
  <AdminLayout>
    <Head title="Riwayat Aktivitas - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <div class="top-bar-left">
        <h1 class="page-title">Riwayat Aktivitas</h1>
        <span class="total-badge">{{ totalCount }} total log</span>
      </div>
    </div>
    <div class="divider" />
    <div v-if="$page.props.flash?.success" class="flash-success" style="margin: 12px 32px 0; background: #d1fae5; color: #065f46; padding: 10px 16px; border-radius: 8px; font-size: 13.5px;">
      {{ $page.props.flash.success }}
    </div>
    <div v-if="$page.props.flash?.error" class="flash-error" style="margin: 12px 32px 0; background: #fee2e2; color: #991b1b; padding: 10px 16px; border-radius: 8px; font-size: 13.5px;">
      {{ $page.props.flash.error }}
    </div>

    <!-- Content -->
    <div class="content-area">

      <!-- Filter Toolbar -->
      <div class="toolbar">
        <!-- Search -->
        <div class="search-wrap">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input
            v-model="form.search"
            type="text"
            placeholder="Cari aksi atau target…"
            class="input-search"
            @keyup.enter="applyFilters"
          />
          <button v-if="form.search" class="clear-btn" @click="form.search = ''; applyFilters()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <button class="btn-search" @click="applyFilters">Cari</button>

        <div class="filters">
          <!-- Action type filter -->
          <div class="filter-group" v-if="actionTypes.length">
            <span class="filter-label">Aksi</span>
            <select v-model="form.action" class="filter-select" @change="applyFilters">
              <option value="semua">Semua</option>
              <option v-for="a in actionTypes" :key="a" :value="a">{{ a }}</option>
            </select>
          </div>

          <!-- Date range -->
          <div class="filter-group">
            <span class="filter-label">Dari</span>
            <input type="date" v-model="form.dari" class="filter-date" @change="applyFilters" />
          </div>
          <div class="filter-group">
            <span class="filter-label">Sampai</span>
            <input type="date" v-model="form.sampai" class="filter-date" @change="applyFilters" />
          </div>
        </div>

        <button v-if="isFiltered" class="reset-link" @click="resetFilters">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
          Reset
        </button>
      </div>

      <!-- Summary strip -->
      <div class="table-meta">
        <span class="table-count">{{ logs.total }} log ditemukan</span>
        <div class="pagination-info" v-if="logs.last_page > 1">
          Halaman {{ logs.current_page }} / {{ logs.last_page }}
        </div>
      </div>

      <!-- Table -->
      <div class="table-wrap">
        <table class="log-table">
          <thead>
            <tr>
              <th class="th-num">#</th>
              <th>Waktu</th>
              <th>Aksi</th>
              <th>Target</th>
              <th>Detail</th>
              <th>IP</th>
              <th class="th-opsi">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="logs.data.length === 0">
              <td colspan="7" class="empty-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                Belum ada riwayat aktivitas.
              </td>
            </tr>
            <tr v-for="(log, idx) in logs.data" :key="log.id" class="log-row">
              <td class="td-num">{{ (logs.current_page - 1) * logs.per_page + idx + 1 }}</td>
              <td class="td-time">
                <div class="time-main">{{ formatTime(log.created_at_iso) }}</div>
                <div class="time-sub">{{ log.created_at }}</div>
              </td>
              <td>
                <span :class="['action-badge', actionClass(log.action)]">
                  <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <component :is="actionIcon(log.action)" />
                  </svg>
                  {{ log.action }}
                </span>
              </td>
              <td class="td-target">
                <div class="target-label">{{ log.target_label ?? '-' }}</div>
                <div v-if="log.target_type" class="target-type">{{ log.target_type }}</div>
              </td>
              <td class="td-detail">
                <template v-if="log.metadata">
                  <div v-for="(val, key) in log.metadata" :key="key" class="meta-row">
                    <span class="meta-key">{{ metaLabel(key) }}:</span>
                    <span class="meta-val" v-if="isChangeObject(val)">
                      <del class="old-val">{{ formatVal(key, val.old) }}</del>
                      <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                      <span class="new-val">{{ formatVal(key, val.new) }}</span>
                    </span>
                    <span class="meta-val" v-else>{{ formatVal(key, val) }}</span>
                  </div>
                </template>
                <span v-else class="text-muted">—</span>
              </td>
              <td class="td-ip">{{ log.ip_address ?? '-' }}</td>
              <td class="td-opsi">
                <button
                  v-if="log.action.includes('Ubah') || log.action.includes('Update')"
                  class="btn-revert"
                  @click="confirmRevert(log)"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 1 3 5 7 9"/></svg>
                  Revert
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination" v-if="logs.last_page > 1">
        <button
          class="page-btn"
          :disabled="!logs.prev_page_url"
          @click="goPage(logs.current_page - 1)"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          Sebelumnya
        </button>

        <div class="page-numbers">
          <button
            v-for="p in pageRange"
            :key="p"
            :class="['page-num', p === logs.current_page ? 'active' : '', p === '…' ? 'ellipsis' : '']"
            :disabled="p === '…'"
            @click="p !== '…' && goPage(p)"
          >{{ p }}</button>
        </div>

        <button
          class="page-btn"
          :disabled="!logs.next_page_url"
          @click="goPage(logs.current_page + 1)"
        >
          Berikutnya
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive, computed, getCurrentInstance } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  logs:        Object,
  actionTypes: Array,
  targetTypes: Array,
  filters:     Object,
  totalCount:  Number,
});

const { proxy } = getCurrentInstance();

const form = reactive({
  search: props.filters?.search ?? '',
  action: props.filters?.action ?? 'semua',
  dari:   props.filters?.dari   ?? '',
  sampai: props.filters?.sampai ?? '',
});

const isFiltered = computed(() =>
  form.search || form.action !== 'semua' || form.dari || form.sampai
);

function applyFilters() {
  router.get(route('superadmin.riwayat-aktivitas'), {
    search: form.search  || undefined,
    action: form.action !== 'semua' ? form.action : undefined,
    dari:   form.dari   || undefined,
    sampai: form.sampai || undefined,
  }, { preserveState: true, replace: true });
}

function resetFilters() {
  form.search = '';
  form.action = 'semua';
  form.dari   = '';
  form.sampai = '';
  applyFilters();
}

function goPage(page) {
  router.get(route('superadmin.riwayat-aktivitas'), {
    page,
    search: form.search  || undefined,
    action: form.action !== 'semua' ? form.action : undefined,
    dari:   form.dari   || undefined,
    sampai: form.sampai || undefined,
  }, { preserveState: true });
}

async function confirmRevert(log) {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Kembalikan Perubahan',
      message: 'Yakin ingin mengembalikan perubahan dari log ini? Pastikan data masih relevan.',
      variant: 'warning',
      confirmText: 'Kembalikan',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      router.post(route('superadmin.riwayat-aktivitas.revert', log.id), {}, {
        preserveScroll: true,
      });
    }
  } catch {
    // User cancelled
  }
}

// Compute visible page numbers with ellipsis
const pageRange = computed(() => {
  const total   = props.logs.last_page;
  const current = props.logs.current_page;
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  const pages = [];
  const add = (n) => { if (!pages.includes(n)) pages.push(n); };
  add(1); add(2);
  if (current - 2 > 3) pages.push('…');
  for (let i = Math.max(3, current - 1); i <= Math.min(total - 2, current + 1); i++) add(i);
  if (current + 2 < total - 2) pages.push('…');
  add(total - 1); add(total);
  return pages;
});

// ── formatting helpers ──────────────────────────────────────
function formatTime(iso) {
  if (!iso) return '-';
  const d = new Date(iso);
  return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function metaLabel(key) {
  const map = {
    email:     'Email',
    role:      'Role',
    is_active: 'Status',
    name:      'Nama',
    telephone: 'Telepon',
    password:  'Password',
    community_name: 'Nama Komunitas',
    phone: 'No. Telepon',
    address: 'Alamat',
    membership_fee: 'Biaya Member',
    membership_duration: 'Durasi Member (Bulan)',
    invoice_countdown: 'Batas Bayar (Jam)',
    account_deletion_duration: 'Batas Hapus Akun (Menit)',
    // Paket Premium
    description: 'Deskripsi',
    price: 'Biaya',
    duration: 'Durasi',
    duration_unit: 'Satuan Durasi',
    is_lifetime: 'Seumur Hidup',
    features: 'Fitur Paket',
    is_recommended: 'Direkomendasikan',
    sort_order: 'Urutan Tampil',
    primary_color: 'Warna Utama',
    surface_color: 'Warna Surface',
    hero_title: 'Judul Utama',
    hero_description: 'Deskripsi Utama',
    about_title: 'Judul Tentang Kami',
    about_description: 'Deskripsi Tentang Kami',
    stat_member_aktif: 'Stat Member Aktif',
    stat_member_pasif: 'Stat Member Pasif',
    stat_member_company: 'Stat Member Perusahaan',
    stat_member_personal: 'Stat Member Personal',
    community_logo: 'Logo Komunitas',
    bg_image: 'Gambar Background',
    card_background: 'Background Kartu Member',
    about_image: 'Gambar Tentang Kami',
  };
  return map[key] ?? key;
}

function isChangeObject(val) {
  return typeof val === 'object' && val !== null && 'old' in val;
}

function formatVal(key, val) {
  if (key === 'is_active') return val ? 'Aktif' : 'Nonaktif';
  if (key === 'role') {
    const roleMap = { member: 'Member', staff: 'Petugas', finance: 'Keuangan', leader: 'Ketua', super_admin: 'Super Admin' };
    return roleMap[val] ?? val;
  }
  if (typeof val === 'string' && val.length > 50) return val.substring(0, 50) + '...';
  return val ?? '-';
}

// ── action badge class ──────────────────────────────────────
function actionClass(action) {
  if (action?.includes('Hapus'))        return 'action-danger';
  if (action?.includes('Buat'))         return 'action-success';
  if (action?.includes('Aktifkan'))     return 'action-success';
  if (action?.includes('Nonaktifkan'))  return 'action-warning';
  if (action?.includes('Ubah'))         return 'action-info';
  return 'action-default';
}

function actionIcon(action) {
  // Returns an SVG path string used inline
  return 'span'; // placeholder – icons are rendered via CSS symbols below
}
</script>

<style scoped>
/* ── Top bar ── */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 32px;
}
.top-bar-left { display: flex; align-items: center; gap: 12px; }
.page-title   { font-size: 20px; font-weight: 700; color: #111; margin: 0; }
.total-badge  {
  background: #f3f4f6;
  color: #6b7280;
  font-size: 12px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 20px;
}
.divider { height: 1px; background: #e5e7eb; }

/* ── Content ── */
.content-area { padding: 24px 32px; }

/* ── Toolbar ── */
.toolbar {
  display: flex;
  align-items: flex-end;
  gap: 16px;
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
  min-width: 200px;
  max-width: 300px;
}
.search-icon {
  position: absolute; left: 10px;
  width: 15px; height: 15px; color: #9ca3af; pointer-events: none;
}
.input-search {
  width: 100%;
  border: 1px solid #d1d5db; border-radius: 7px;
  padding: 8px 32px 8px 34px;
  font-size: 13.5px; color: #111;
  outline: none; background: #fff; transition: border 0.2s;
}
.input-search:focus { border-color: var(--primary-color); }
.clear-btn {
  position: absolute; right: 8px;
  background: none; border: none; cursor: pointer; padding: 2px; color: #9ca3af;
}
.clear-btn svg { width: 13px; height: 13px; }

.btn-search {
  background: var(--primary-color); color: #fff;
  border: none; padding: 8px 20px; border-radius: 7px;
  font-size: 13.5px; font-weight: 600; cursor: pointer;
  transition: filter 0.2s; align-self: flex-end;
}
.btn-search:hover { filter: brightness(0.9); }

/* Filters */
.filters { display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; }
.filter-group { display: flex; flex-direction: column; gap: 5px; }
.filter-label { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.06em; }
.filter-select, .filter-date {
  border: 1px solid #d1d5db; border-radius: 7px;
  padding: 8px 10px; font-size: 13px; color: #111;
  background: #fff; outline: none; transition: border 0.2s;
  cursor: pointer;
}
.filter-select:focus, .filter-date:focus { border-color: var(--primary-color); }

.reset-link {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12.5px; color: var(--primary-color); background: none;
  border: none; cursor: pointer; font-weight: 500; padding: 0; align-self: flex-end;
}
.reset-link svg { width: 13px; height: 13px; }
.reset-link:hover { text-decoration: underline; }

/* Table meta */
.table-meta {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 10px;
}
.table-count   { font-size: 13px; color: #6b7280; }
.pagination-info { font-size: 13px; color: #9ca3af; }

/* Table */
.table-wrap { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
.log-table  { width: 100%; border-collapse: collapse; font-size: 13.5px; }

.log-table thead tr { background: #f9fafb; }
.log-table th {
  padding: 11px 16px;
  text-align: left;
  font-size: 12px; font-weight: 700; color: #6b7280;
  border-bottom: 1px solid #e5e7eb;
  text-transform: uppercase; letter-spacing: 0.05em;
  white-space: nowrap;
}
.th-num { text-align: center; width: 40px; }

.log-table td {
  padding: 12px 16px;
  color: #374151;
  border-bottom: 1px solid #f3f4f6;
  vertical-align: top;
}
.log-table tbody tr:last-child td { border-bottom: none; }
.log-row:hover { background: #fafafa; }

.td-num { text-align: center; color: #9ca3af; font-size: 12px; }

/* Time */
.td-time  { min-width: 160px; }
.time-main { font-size: 15px; font-weight: 700; color: #111; }
.time-sub  { font-size: 11.5px; color: #9ca3af; margin-top: 2px; }

/* Action badge */
.action-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px; border-radius: 20px;
  font-size: 12px; font-weight: 700;
  white-space: nowrap;
}
.action-icon { width: 12px; height: 12px; flex-shrink: 0; }
.action-success { background: #d1fae5; color: #065f46; }
.action-danger  { background: #fee2e2; color: #991b1b; }
.action-warning { background: #fef3c7; color: #92400e; }
.action-info    { background: #dbeafe; color: #1e40af; }
.action-default { background: #f3f4f6; color: #6b7280; }

/* Target */
.td-target   { min-width: 130px; }
.target-label { font-weight: 600; color: #111; }
.target-type  { font-size: 11.5px; color: #9ca3af; margin-top: 2px; }

/* Metadata */
.td-detail { min-width: 180px; }
.meta-row  { display: flex; gap: 4px; font-size: 12px; margin-bottom: 3px; align-items: center; flex-wrap: wrap; }
.meta-key  { color: #9ca3af; font-weight: 600; flex-shrink: 0; }
.meta-val  { color: #374151; display: inline-flex; align-items: center; }
.old-val   { color: #ef4444; text-decoration: line-through; margin-right: 4px; }
.new-val   { color: #10b981; font-weight: 600; margin-left: 4px; }
.arrow-icon { width: 12px; height: 12px; color: #9ca3af; }
.text-muted { color: #9ca3af; }

/* IP */
.td-ip { font-size: 12px; color: #9ca3af; white-space: nowrap; }

/* Empty */
.empty-row {
  text-align: center; color: #9ca3af; padding: 56px !important;
}
.empty-row svg { width: 32px; height: 32px; display: block; margin: 0 auto 12px; opacity: 0.35; }

/* Pagination */
.pagination {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: 20px; gap: 12px; flex-wrap: wrap;
}
.page-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 8px;
  background: #fff; font-size: 13px; font-weight: 500; color: #374151;
  cursor: pointer; transition: all 0.15s;
}
.page-btn svg { width: 16px; height: 16px; }
.page-btn:not(:disabled):hover { border-color: var(--primary-color); color: var(--primary-color); background: #eef2ff; }
.page-btn:disabled { opacity: 0.4; cursor: not-allowed; }

.page-numbers { display: flex; gap: 4px; flex-wrap: wrap; }
.page-num {
  width: 36px; height: 36px;
  display: inline-flex; align-items: center; justify-content: center;
  border: 1px solid #e5e7eb; border-radius: 7px;
  font-size: 13px; font-weight: 500; color: #374151;
  cursor: pointer; transition: all 0.15s; background: #fff;
}
.page-num:hover:not(.active):not(.ellipsis) { border-color: var(--primary-color); color: var(--primary-color); }
.page-num.active { background: var(--primary-color); border-color: var(--primary-color); color: #fff; font-weight: 700; }
.page-num.ellipsis { border: none; cursor: default; background: transparent; }

/* Opsi */
.th-opsi { width: 100px; text-align: center; }
.td-opsi { text-align: center; }
.btn-revert {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 5px 10px; border-radius: 6px;
  font-size: 11.5px; font-weight: 600;
  background: #fff; color: #4b5563; border: 1px solid #d1d5db;
  cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.btn-revert svg { width: 13px; height: 13px; }
.btn-revert:hover { border-color: #9ca3af; background: #f3f4f6; color: #111; }
</style>
