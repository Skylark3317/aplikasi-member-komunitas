<template>
  <MemberLayout>
    <Head title="Riwayat Pembayaran - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Pembayaran</h1>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <div class="table-wrapper">
        <table class="payment-table">
          <thead>
            <tr>
              <th>Nomor Invoice</th>
              <th>Tanggal Tagihan</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="paginatedInvoices.length === 0">
              <td colspan="5" class="empty-state">
                Belum ada data tagihan atau riwayat pembayaran.
              </td>
            </tr>
            <tr v-for="invoice in paginatedInvoices" :key="invoice.id">
              <td class="font-semibold">{{ invoice.number }}</td>
              <td>{{ formatDate(invoice.created_at) }}</td>
              <td class="font-semibold">Rp{{ formatCurrency(invoice.amount) }}</td>
              <td>
                <span :class="['status-badge', getBadgeClass(invoice)]">
                  {{ getStatusLabel(invoice) }}
                </span>
              </td>
              <td class="text-center">
                <Link :href="route('member.premium.payment_detail', { invoice: invoice.id })" class="btn-action-view" title="Lihat Detail">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="eye-icon">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination (client-side) -->
      <div v-if="totalPages > 1" class="pagination-wrapper">
        <button 
          @click="prevPage" 
          :disabled="currentPage === 1" 
          :class="['page-link-nav', currentPage === 1 ? 'disabled' : '']"
        >
          &lt; Sebelumnya
        </button>
        
        <button 
          v-for="page in totalPages" 
          :key="page" 
          @click="setPage(page)" 
          :class="['page-link-num', currentPage === page ? 'active' : '']"
        >
          {{ page }}
        </button>
        
        <button 
          @click="nextPage" 
          :disabled="currentPage === totalPages" 
          :class="['page-link-nav', currentPage === totalPages ? 'disabled' : '']"
        >
          Berikutnya &gt;
        </button>
      </div>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  invoices: {
    type: Array,
    default: () => [],
  },
});

const currentPage = ref(1);
const itemsPerPage = 8;

const totalPages = computed(() => Math.ceil(props.invoices.length / itemsPerPage));

const paginatedInvoices = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return props.invoices.slice(start, end);
});

function setPage(page) {
  currentPage.value = page;
}

function prevPage() {
  if (currentPage.value > 1) currentPage.value--;
}

function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++;
}

function formatDate(dateStr) {
  const date = new Date(dateStr);
  const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
  const formattedDate = date.toLocaleDateString('id-ID', options);
  
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  
  return `${formattedDate}, ${hours}:${minutes}`;
}

function formatCurrency(val) {
  return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(val);
}

function getBadgeClass(invoice) {
  if (!invoice.payment) return 'badge-danger';
  
  const status = invoice.payment.status;
  if (status === 'menunggu') return 'badge-warning';
  if (status === 'diverifikasi') return 'badge-success';
  return 'badge-danger'; // ditolak
}

function getStatusLabel(invoice) {
  if (!invoice.payment) return 'Belum dibayar';
  
  const status = invoice.payment.status;
  if (status === 'menunggu') return 'Menunggu verifikasi';
  if (status === 'diverifikasi') return 'Diverifikasi';
  return 'Ditolak';
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  display: flex;
  align-items: center;
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
  padding: 32px;
  background: #fff;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
}

/* Table Wrapper */
.table-wrapper {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
  margin-bottom: 24px;
}

.payment-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.payment-table th {
  background: #f9fafb;
  color: #374151;
  font-weight: 600;
  font-size: 13.5px;
  padding: 14px 20px;
  border-bottom: 1px solid #e5e7eb;
}

.payment-table td {
  padding: 16px 20px;
  border-bottom: 1px solid #f3f4f6;
  font-size: 13.5px;
  color: #4b5563;
  vertical-align: middle;
}

.payment-table tr:last-child td {
  border-bottom: none;
}

.font-semibold {
  font-weight: 600;
  color: #111827 !important;
}

.text-center {
  text-align: center;
}

.empty-state {
  text-align: center;
  padding: 40px;
  color: #9ca3af;
  font-size: 14px;
}

/* Badges */
.status-badge {
  display: inline-block;
  padding: 4px 10px;
  font-size: 11px;
  font-weight: 700;
  border-radius: 6px;
  text-align: center;
}

.badge-danger {
  background: #fde8e8;
  color: #e53e3e;
}

.badge-warning {
  background: #fef3c7;
  color: #d97706;
}

.badge-success {
  background: #def7ec;
  color: #03543f;
}

/* Action Button */
.btn-action-view {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 6px;
  color: #4b5563;
  background: #f3f4f6;
  transition: all 0.15s ease;
  text-decoration: none;
}

.btn-action-view:hover {
  background: #e5e7eb;
  color: #111827;
}

.eye-icon {
  width: 16px;
  height: 16px;
}

/* Pagination */
.pagination-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 32px;
}

.page-link-nav, .page-link-num {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 13.5px;
  font-weight: 600;
  background: transparent;
  border: none;
  cursor: pointer;
  height: 32px;
  transition: all 0.15s ease;
}

.page-link-nav {
  color: #007bff;
  padding: 0 12px;
}

.page-link-nav.disabled {
  color: #9ca3af;
  cursor: not-allowed;
}

.page-link-num {
  width: 32px;
  color: #4b5563;
  border-radius: 4px;
}

.page-link-num.active {
  background: #007bff;
  color: #fff;
}

.page-link-num:hover:not(.active) {
  background: #f3f4f6;
  color: #111827;
}
</style>
