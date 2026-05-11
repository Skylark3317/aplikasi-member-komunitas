<template>
  <BendaharaLayout>
    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Pembayaran</h1>
    </div>
    <div class="divider" />

    <div class="content-area">
      <div class="card search-card">
        <div class="search-section">
          <label>Cari Pembayaran</label>
          <div class="search-input-group">
            <input
              v-model="search"
              type="text"
              placeholder="Cari pembayaran..."
              @keyup.enter="handleSearch"
            />
            <button class="btn-primary" @click="handleSearch">Cari</button>
          </div>
        </div>

        <div class="filter-section">
          <div class="filter-group">
            <label>Dari</label>
            <input v-model="from" type="date" />
          </div>
          <div class="filter-group">
            <label>Hingga</label>
            <input v-model="to" type="date" />
          </div>
          <div class="filter-group">
            <label>Status</label>
            <select v-model="status">
              <option value="Semua">Semua</option>
              <option value="menunggu">Menunggu verifikasi</option>
              <option value="diverifikasi">Diverifikasi</option>
              <option value="ditolak">Ditolak</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nomor Invoice</th>
              <th>Atas Nama Pengirim</th>
              <th>Tanggal Tagihan</th>
              <th>Tanggal Pembayaran</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payment in payments.data" :key="payment.id">
              <td>{{ payment.invoice.number }}</td>
              <td>{{ payment.payer.name }}</td>
              <td>{{ formatDate(payment.invoice.created_at) }}</td>
              <td>{{ formatDate(payment.date) }}</td>
              <td>{{ formatCurrency(payment.amount) }}</td>
              <td>
                <span :class="['status-badge', payment.status]">
                  {{ payment.status === 'menunggu' ? 'Menunggu verifikasi' : (payment.status === 'diverifikasi' ? 'Diverifikasi' : 'Ditolak') }}
                </span>
              </td>
              <td>
                <Link :href="route('bendahara.pembayaran.show', payment.id)" class="action-btn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </Link>
              </td>
            </tr>
            <tr v-if="payments.data.length === 0">
              <td colspan="7" class="empty-state">Tidak ada data pembayaran.</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="payments.links.length > 3" class="pagination">
          <Link 
            v-for="(link, k) in payments.links" 
            :key="k"
            :href="link.url || '#'"
            :class="[
              'page-btn', 
              link.active ? 'active' : '', 
              !link.url ? 'btn-disabled' : '',
              k === 0 ? 'text-muted' : (k === payments.links.length - 1 ? 'text-primary' : '')
            ]"
          >
            <template v-if="k === 0">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg> 
              Sebelumnya
            </template>
            <template v-else-if="k === payments.links.length - 1">
              Berikutnya 
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </template>
            <template v-else>
              {{ link.label }}
            </template>
          </Link>
        </div>
      </div>
    </div>
  </BendaharaLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import BendaharaLayout from '@/Layouts/BendaharaLayout.vue';

const props = defineProps({
  payments: Object,
  filters: Object,
});

const search = ref(props.filters.search || '');
const from = ref(props.filters.from || '');
const to = ref(props.filters.to || '');
const status = ref(props.filters.status || 'Semua');

function handleSearch() {
  router.get(route('bendahara.pembayaran.index'), {
    search: search.value,
    from: from.value,
    to: to.value,
    status: status.value,
  }, { preserveState: true });
}

watch([from, to, status], () => {
  handleSearch();
});

function formatDate(dateString) {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).replace(',', '');
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2,
  }).format(amount);
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}
.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content Area */
.content-area { 
  padding: 24px 32px; 
  background: #fff;
  min-height: calc(100vh - 65px);
  display: flex;
  flex-direction: column;
}

.card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  padding: 24px;
  margin-bottom: 24px;
}

.search-card {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.search-section label,
.filter-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.search-input-group {
  display: flex;
  gap: 12px;
}
.search-input-group input {
  flex: 1;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
}
.btn-primary {
  padding: 10px 24px;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.filter-section {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}
.filter-group {
  flex: 1;
  min-width: 150px;
}
.filter-group input,
.filter-group select {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  background: #fff;
}

.table-card {
  padding: 0;
  overflow: hidden;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}
.data-table th {
  background: #f9fafb;
  text-align: left;
  padding: 14px 20px;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
  border-bottom: 1px solid #e5e7eb;
}
.data-table td {
  padding: 16px 20px;
  font-size: 13.5px;
  color: #4b5563;
  border-bottom: 1px solid #f3f4f6;
}

.status-badge {
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
}
.status-badge.menunggu {
  background: #ffede6;
  color: #ff5c00;
}
.status-badge.diverifikasi {
  background: #ecfdf5;
  color: #059669;
}
.status-badge.ditolak {
  background: #fef2f2;
  color: #dc2626;
}

.action-btn {
  color: #6b7280;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 6px;
  transition: background 0.15s;
}
.action-btn:hover {
  background: #f3f4f6;
  color: #111;
}
.action-btn svg {
  width: 18px;
  height: 18px;
}

.empty-state {
  text-align: center;
  padding: 40px !important;
  color: #9ca3af;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  padding: 24px;
  border-top: 1px solid #f3f4f6;
}

.page-btn {
  background: transparent;
  border: none;
  font-size: 13.5px;
  cursor: pointer;
  padding: 4px;
  font-weight: 500;
  color: #111;
  display: flex;
  align-items: center;
  gap: 4px;
  text-decoration: none;
}

.btn-disabled {
  color: #ccc !important;
  cursor: not-allowed;
  pointer-events: none;
}

.page-btn.active {
  background: #acc000;
  color: #fff;
  border-radius: 10px;
  width: 40px;
  height: 40px;
  justify-content: center;
  font-weight: 600;
}

.text-muted { color: #ccc; }
.text-primary { color: #acc000; }
</style>
