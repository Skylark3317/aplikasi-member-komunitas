<template>
  <KeuanganLayout>
    <!-- Top Bar -->
    <div class="top-bar">
      <div class="top-left">
        <Link :href="route('keuangan.pembayaran.index')" class="back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </Link>
        <h1 class="page-title">Detail Pembayaran</h1>
      </div>
      <div class="header-actions">
        <button class="btn-cancel" @click="goBack">Batal</button>
        <button v-if="payment.status === 'menunggu'" class="btn-reject" @click="showRejectModal = true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          Tolak pembayaran
        </button>
        <button v-if="payment.status === 'menunggu'" class="btn-accept" @click="handleVerify">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          Terima pembayaran
        </button>
      </div>
    </div>
    <div class="divider" />

    <div class="content-area">
      <div class="detail-wrapper">
        <!-- Rincian Tagihan -->
        <div class="section-block">
          <h2 class="block-title">Rincian Tagihan</h2>
          <div class="info-list">
            <div class="info-row">
              <span class="info-label">Nomor Invoice</span>
              <span class="info-value">{{ payment.invoice.number }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Tanggal Tagihan</span>
              <span class="info-value">{{ formatDate(payment.invoice.created_at) }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Jumlah</span>
              <span class="info-value">{{ formatCurrency(payment.invoice.amount) }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Atas Nama Rekening Tujuan</span>
              <span class="info-value">{{ settings.community_name || 'AMK' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Nomor Rekening Tujuan</span>
              <span class="info-value">000000001111</span>
            </div>
            <div class="info-row">
              <span class="info-label">Nama Bank Tujuan</span>
              <span class="info-value">Bank BRI</span>
            </div>
            <div class="info-row">
              <span class="info-label">Status</span>
              <span :class="['status-badge', payment.status]">
                 {{ payment.status === 'menunggu' ? 'Menunggu verifikasi' : (payment.status === 'diverifikasi' ? 'Diverifikasi' : 'Ditolak') }}
              </span>
            </div>
            <div v-if="payment.status === 'ditolak'" class="info-row">
              <span class="info-label">Alasan Penolakan</span>
              <span class="info-value reason">{{ payment.reject_reason }}</span>
            </div>
          </div>
        </div>

        <!-- Pembayaran -->
        <div class="section-block">
          <h2 class="block-title">Pembayaran</h2>
          <div class="info-list">
            <div class="info-row vertical">
              <span class="info-label">Bukti Pembayaran</span>
              <div class="proof-box" @click="zoomImage = true">
                <img :src="payment.payment_proof_url" alt="Bukti Pembayaran" />
              </div>
            </div>
            <div class="info-row">
              <span class="info-label">Tanggal Pembayaran</span>
              <span class="info-value">{{ formatDate(payment.date) }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Atas Nama Pengirim</span>
              <span class="info-value">{{ payment.account_holder_name }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Nomor Rekening Pengirim</span>
              <span class="info-value">{{ payment.account_number }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Nama Bank Pengirim</span>
              <span class="info-value">{{ payment.account_bank_name }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reject Modal -->
    <div v-if="showRejectModal" class="modal-overlay" @click.self="showRejectModal = false">
      <div class="modal-content">
        <h3>Tolak Pembayaran</h3>
        <p>Berikan alasan mengapa pembayaran ini ditolak.</p>
        <textarea 
          v-model="rejectReason" 
          placeholder="Contoh: Bukti transfer tidak terbaca atau nominal tidak sesuai..."
        ></textarea>
        <div class="modal-actions">
          <button class="btn-cancel" @click="showRejectModal = false">Batal</button>
          <button class="btn-reject-confirm" :disabled="!rejectReason" @click="handleReject">
            Tolak Sekarang
          </button>
        </div>
      </div>
    </div>

    <!-- Zoom Image Modal -->
    <div v-if="zoomImage" class="modal-overlay" @click="zoomImage = false">
      <img :src="payment.payment_proof_url" class="zoomed-img" />
    </div>
  </KeuanganLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import KeuanganLayout from '@/Layouts/KeuanganLayout.vue';

const props = defineProps({
  payment: Object,
});

const $page = usePage();
const settings = computed(() => $page.props.settings || {});

const showRejectModal = ref(false);
const rejectReason = ref('');
const zoomImage = ref(false);

function goBack() {
  window.history.back();
}

function handleVerify() {
  if (confirm('Apakah Anda yakin ingin menerima pembayaran ini?')) {
    router.post(route('keuangan.pembayaran.verify', props.payment.id));
  }
}

function handleReject() {
  router.post(route('keuangan.pembayaran.reject', props.payment.id), {
    reject_reason: rejectReason.value,
  }, {
    onSuccess: () => {
      showRejectModal.value = false;
      rejectReason.value = '';
    }
  });
}

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
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}
.top-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.back-link {
  color: #111;
  display: flex;
  align-items: center;
}
.back-link svg { width: 18px; height: 18px; }

.page-title {
  font-size: 18px;
  font-weight: 600;
  color: #111;
}

.header-actions {
  display: flex;
  gap: 12px;
}

/* Button Styles based on Mockup */
.btn-cancel, .btn-reject, .btn-accept {
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
}

.btn-accept { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
.btn-reject { background: #ef4444; color: #fff; border-color: #ef4444; }
.btn-cancel { background: #fff; color: #374151; border-color: #d1d5db; }

.btn-accept:hover, .btn-reject:hover { filter: brightness(0.9); }
.btn-cancel:hover { background: #f3f4f6; }

.btn-reject svg, .btn-accept svg { width: 16px; height: 16px; }

.divider { height: 1px; background: #e5e7eb; }

.content-area {
  padding: 32px;
  background: #fff;
  min-height: calc(100vh - 65px);
}

.detail-wrapper {
  max-width: 900px;
}

.section-block {
  margin-bottom: 48px;
}

.block-title {
  font-size: 18px;
  font-weight: 600;
  color: #111;
  margin-bottom: 24px;
}

.info-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.info-row {
  display: flex;
  align-items: flex-start;
  font-size: 14px;
}

.info-row.vertical {
  flex-direction: column;
  gap: 12px;
}

.info-label {
  width: 220px;
  color: #555;
  flex-shrink: 0;
}

.info-value {
  color: #111;
  font-weight: 500;
}

.status-badge {
  font-size: 12px;
  padding: 2px 8px;
  border-radius: 4px;
  font-weight: 500;
}
.status-badge.menunggu {
  background: #fff7ed;
  color: #f97316;
}
.status-badge.diverifikasi {
  background: #f0fdf4;
  color: #16a34a;
}
.status-badge.ditolak {
  background: #fef2f2;
  color: #dc2626;
}

.proof-box {
  width: 140px;
  height: 140px;
  background: #f3f4f6;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
}
.proof-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Modal Styling */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
}
.modal-content {
  background: #fff;
  padding: 32px;
  border-radius: 12px;
  width: 100%;
  max-width: 440px;
}
.modal-content h3 { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
.modal-content p { color: #666; font-size: 14px; margin-bottom: 16px; }
.modal-content textarea {
  width: 100%;
  height: 100px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 20px;
  font-family: inherit;
  resize: none;
}
.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}
.btn-reject-confirm {
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
  background: #ef4444;
  color: #fff;
  border-color: #ef4444;
}
.btn-reject-confirm:hover:not(:disabled) { filter: brightness(0.9); }
.btn-reject-confirm:disabled { opacity: 0.6; cursor: not-allowed; }

.zoomed-img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 8px;
}
</style>
