<template>
  <MemberLayout>
    <Head title="Detail Pembayaran - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <div class="header-left">
        <Link :href="route('member.premium.payment')" class="btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="back-icon">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </Link>
        <h1 class="page-title">Pembayaran</h1>
      </div>
      
      <!-- Top Actions -->
      <div class="top-actions">
        <button 
          v-if="step !== 3" 
          @click="cancelInvoice" 
          class="btn-top-cancel"
          :disabled="isCanceling"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="cancel-icon-sm">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          <span>Batalkan Pesanan</span>
        </button>
      </div>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <div class="payment-flow-wrapper">
        
        <!-- Step Tracker -->
        <div class="steps-tracker">
          <div class="step-item" :class="{ 'step-active': step === 1, 'step-done': step > 1 }">
            <div class="step-circle">
              <svg v-if="step > 1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="check-svg">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span v-else>1</span>
            </div>
            <span class="step-label">Pembayaran</span>
          </div>
          
          <div class="step-line" :class="{ 'line-done': step > 1 }"></div>
          
          <div class="step-item" :class="{ 'step-active': step === 2, 'step-done': step > 2 }">
            <div class="step-circle">
              <svg v-if="step > 2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="check-svg">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span v-else>2</span>
            </div>
            <span class="step-label">Verifikasi</span>
          </div>
          
          <div class="step-line" :class="{ 'line-done': step > 2 }"></div>
          
          <div class="step-item" :class="{ 'step-active': step === 3 }">
            <div class="step-circle">
              <svg v-if="step === 3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="check-svg">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span v-else>3</span>
            </div>
            <span class="step-label">Aktif</span>
          </div>
        </div>

        <!-- Alert Banners -->
        <div v-if="showAlert" class="alert-container">
          <!-- Step 1 Alert -->
          <div v-if="step === 1" class="alert-banner alert-warning">
            <span class="alert-text">
              Silahkan lakukan transfer ke rekening tujuan dengan jumlah yang ditentukan, lalu unggah bukti pembayaran sebelum <strong>{{ formatDueDate(invoice.due_date) }}</strong>.
            </span>
            <button @click="showAlert = false" class="btn-close-alert">×</button>
          </div>

          <!-- Step 2 Alert -->
          <div v-if="step === 2" class="alert-banner alert-info">
            <span class="alert-text">
              Silahkan tunggu verifikasi dari petugas keuangan.
            </span>
            <button @click="showAlert = false" class="btn-close-alert">×</button>
          </div>

          <!-- Step 3 Alert -->
          <div v-if="step === 3" class="alert-banner alert-success">
            <span class="alert-text">
              Pembayaran telah berhasil diverifikasi.
            </span>
            <button @click="showAlert = false" class="btn-close-alert">×</button>
          </div>
        </div>

        <!-- Tagihan & Form Pembayaran -->
        <div class="payment-form-box">
          <!-- Rincian Tagihan -->
          <div class="bill-details-section">
            <h3 class="box-title">Rincian Tagihan</h3>
            
            <div class="details-grid">
              <div class="detail-row">
                <span class="detail-label">Nomor Invoice</span>
                <span class="detail-value font-semibold">{{ invoice.number }}</span>
              </div>
              
              <div class="detail-row" v-if="invoice.plan">
                <span class="detail-label">Paket</span>
                <span class="detail-value font-medium">{{ invoice.plan.name }}</span>
              </div>
              
              <div class="detail-row">
                <span class="detail-label">Tanggal Tagihan</span>
                <span class="detail-value">{{ formatInvoiceDate(invoice.created_at) }}</span>
              </div>
              
              <div class="detail-row">
                <span class="detail-label">Jumlah</span>
                <span class="detail-value font-semibold price-val">Rp{{ formatCurrency(invoice.amount) }}</span>
              </div>
              
              <div class="detail-row">
                <span class="detail-label">Atas Nama Rekening Tujuan</span>
                <span class="detail-value font-medium">{{ settings.bank_account_name || 'AMK' }}</span>
              </div>
              
              <div class="detail-row">
                <span class="detail-label">Nomor Rekening Tujuan</span>
                <span class="detail-value font-semibold select-all">{{ settings.bank_account_number || '000000001111' }}</span>
              </div>
              
              <div class="detail-row">
                <span class="detail-label">Nama Bank Tujuan</span>
                <span class="detail-value font-medium">{{ settings.bank_name || 'Bank BRI' }}</span>
              </div>
              
              <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                  <span :class="['status-badge', getBadgeClass()]">
                    {{ getStatusLabel() }}
                  </span>
                </span>
              </div>

              <!-- Rejection Reason (If any) -->
              <div v-if="status === 'ditolak' && payment && payment.reject_reason" class="detail-row reject-reason-row">
                <span class="detail-label text-red-600">Alasan Penolakan</span>
                <span class="detail-value text-red-600 font-semibold">{{ payment.reject_reason }}</span>
              </div>
            </div>
          </div>

          <div class="divider-box" />

          <!-- Form Pembayaran -->
          <form @submit.prevent="submitPayment" class="payment-upload-section">
            <h3 class="box-title">Pembayaran</h3>

            <!-- Bukti Pembayaran -->
            <div class="form-group">
              <label class="field-label">Bukti Pembayaran</label>
              
              <!-- Preview/Box -->
              <div class="proof-preview-wrapper">
                <div v-if="imagePreview" class="preview-box">
                  <img :src="imagePreview" alt="Bukti Transfer" class="preview-img" />
                </div>
                <div v-else class="empty-preview-box">
                  <!-- Landscape Icon -->
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="landscape-icon">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                  </svg>
                </div>
              </div>

              <span class="help-text">Format JPG, PNG, atau PDF, ukuran maksimal 1MB</span>

              <!-- Upload Button (Editable only in step 1) -->
              <div v-if="step === 1" class="upload-btn-container">
                <input 
                  type="file" 
                  id="proof-file" 
                  ref="fileInput"
                  @change="handleFileChange" 
                  accept="image/*,application/pdf"
                  class="hidden-file-input"
                />
                <button type="button" @click="triggerFileInput" class="btn-upload-trigger">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="upload-icon">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                  </svg>
                  <span>Unggah bukti pembayaran</span>
                </button>
                <div v-if="form.errors.payment_proof" class="error-msg">{{ form.errors.payment_proof }}</div>
              </div>
            </div>

            <!-- Tanggal Pembayaran -->
            <div class="form-group">
              <label for="payment-date" class="field-label">Tanggal Pembayaran</label>
              <input 
                id="payment-date"
                type="date"
                v-model="form.payment_date"
                :disabled="step > 1"
                :class="['form-input', step > 1 ? 'input-disabled' : '', form.errors.payment_date ? 'has-error' : '']"
              />
              <div v-if="form.errors.payment_date" class="error-msg">{{ form.errors.payment_date }}</div>
            </div>

            <!-- Atas Nama Pengirim -->
            <div class="form-group">
              <label for="sender-name" class="field-label">Atas Nama Pengirim</label>
              <input 
                id="sender-name"
                type="text"
                v-model="form.account_holder_name"
                placeholder="Atas Nama Pengirim"
                :disabled="step > 1"
                :class="['form-input', step > 1 ? 'input-disabled' : '', form.errors.account_holder_name ? 'has-error' : '']"
              />
              <div v-if="form.errors.account_holder_name" class="error-msg">{{ form.errors.account_holder_name }}</div>
            </div>

            <!-- Nomor Rekening Pengirim -->
            <div class="form-group">
              <label for="sender-account" class="field-label">Nomor Rekening Pengirim</label>
              <input 
                id="sender-account"
                type="text"
                v-model="form.account_number"
                placeholder="000000000000"
                :disabled="step > 1"
                :class="['form-input', step > 1 ? 'input-disabled' : '', form.errors.account_number ? 'has-error' : '']"
              />
              <div v-if="form.errors.account_number" class="error-msg">{{ form.errors.account_number }}</div>
            </div>

            <!-- Nama Bank Pengirim -->
            <div class="form-group">
              <label for="sender-bank" class="field-label">Nama Bank Pengirim</label>
              <input 
                id="sender-bank"
                type="text"
                v-model="form.account_bank_name"
                placeholder="Nama Bank Pengirim"
                :disabled="step > 1"
                :class="['form-input', step > 1 ? 'input-disabled' : '', form.errors.account_bank_name ? 'has-error' : '']"
              />
              <div v-if="form.errors.account_bank_name" class="error-msg">{{ form.errors.account_bank_name }}</div>
            </div>

            <!-- Bottom Send Button (Only in Step 1) -->
            <div v-if="step === 1" class="form-actions-bottom">
              <button 
                type="submit" 
                class="btn-bottom-send"
                :disabled="form.processing"
              >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="send-icon">
                  <line x1="22" y1="2" x2="11" y2="13"/>
                  <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
                <span>Kirim bukti pembayaran</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref, computed, getCurrentInstance } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  invoice: Object,
  payment: Object,
  settings: Object,
  status: String, // 'none', 'menunggu', 'diverifikasi', 'ditolak'
});

const { proxy } = getCurrentInstance();

const showAlert = ref(true);
const fileInput = ref(null);
const localImagePreview = ref(null);
const isCanceling = ref(false);

// Determine the active visual step (1: Pembayaran, 2: Verifikasi, 3: Aktif)
const step = computed(() => {
  if (props.status === 'diverifikasi') return 3;
  if (props.status === 'menunggu') return 2;
  return 1; // 'none' or 'ditolak' is Step 1 (allow upload/re-upload)
});

// Setup Form State
const form = useForm({
  invoice_id: props.invoice.id,
  account_holder_name: props.payment ? props.payment.account_holder_name : '',
  account_number: props.payment ? props.payment.account_number : '',
  account_bank_name: props.payment ? props.payment.account_bank_name : '',
  payment_proof: null,
  payment_date: props.payment ? formatInputDate(props.payment.date) : getTodayDate(),
});

// File upload preview URL
const imagePreview = computed(() => {
  if (localImagePreview.value) {
    return localImagePreview.value;
  }
  if (props.payment && props.payment.payment_proof_url) {
    return props.payment.payment_proof_url;
  }
  return null;
});

function getTodayDate() {
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, '0');
  const dd = String(today.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
}

function formatInputDate(dateStr) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  const yyyy = date.getFullYear();
  const mm = String(date.getMonth() + 1).padStart(2, '0');
  const dd = String(date.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
}

function triggerFileInput() {
  fileInput.value.click();
}

function handleFileChange(e) {
  const file = e.target.files[0];
  if (file) {
    form.payment_proof = file;
    localImagePreview.value = URL.createObjectURL(file);
  }
}

function submitPayment() {
  if (step.value !== 1) return;
  form.post(route('member.premium.pay'), {
    forceFormData: true,
  });
}

async function cancelInvoice() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Batalkan Pesanan',
      message: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
      variant: 'danger',
      confirmText: 'Ya, Batalkan',
      cancelText: 'Tidak'
    });
    
    if (confirmed) {
      isCanceling.value = true;
      router.delete(route('member.premium.cancel_invoice', props.invoice.id), {
        onFinish: () => {
          isCanceling.value = false;
        }
      });
    }
  } catch {
    // User cancelled
  }
}

// Visual labels and formatting helpers
function getBadgeClass() {
  if (props.status === 'menunggu') return 'badge-warning';
  if (props.status === 'diverifikasi') return 'badge-success';
  return 'badge-danger'; // none or ditolak
}

function getStatusLabel() {
  if (props.status === 'menunggu') return 'Menunggu verifikasi';
  if (props.status === 'diverifikasi') return 'Diverifikasi';
  if (props.status === 'ditolak') return 'Ditolak';
  return 'Belum dibayar';
}

function formatCurrency(val) {
  return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(val);
}

function formatInvoiceDate(dateStr) {
  const date = new Date(dateStr);
  const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
  const formattedDate = date.toLocaleDateString('id-ID', options);
  
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  
  return `${formattedDate}, ${hours}:${minutes}`;
}

function formatDueDate(dateStr) {
  const date = new Date(dateStr);
  const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
  const formattedDate = date.toLocaleDateString('id-ID', options);
  
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  
  return `${formattedDate}, ${hours}:${minutes}`;
}
</script>

<style scoped>
/* Top Bar */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  color: #4b5563;
  transition: all 0.15s ease;
}

.btn-back:hover {
  background: #f3f4f6;
  color: #111;
}

.back-icon {
  width: 20px;
  height: 20px;
}

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}

.top-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-top-cancel {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #fef2f2;
  color: #ef4444;
  border: 1px solid #fca5a5;
  padding: 7px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}

.btn-top-cancel:hover:not(:disabled) {
  background: #fee2e2;
  border-color: #f87171;
}

.btn-top-cancel:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.cancel-icon-sm {
  width: 14px;
  height: 14px;
}

.btn-top-send {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.15s ease;
}

.btn-top-send:hover:not(:disabled) {
  filter: brightness(0.9);
}

.btn-top-send:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.send-icon-sm {
  width: 14px;
  height: 14px;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content Area */
.content-area {
  padding: 32px;
  background: #f9fafb;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
}

.payment-flow-wrapper {
  max-width: 900px;
  margin: 0 auto;
}

/* Steps Tracker */
.steps-tracker {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px 40px;
  margin-bottom: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
}

.step-item {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #9ca3af;
  font-weight: 600;
  font-size: 14px;
}

.step-circle {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 700;
  color: #9ca3af;
}

.step-line {
  flex-grow: 1;
  height: 1.5px;
  background: #e5e7eb;
  margin: 0 20px;
}

.check-svg {
  width: 14px;
  height: 14px;
  color: #fff;
}

/* Active & Done Step Classes */
.step-active {
  color: var(--primary-color) !important;
}
.step-active .step-circle {
  background: var(--primary-color) !important;
  color: #fff !important;
}

.step-done {
  color: #28a745 !important;
}
.step-done .step-circle {
  background: #28a745 !important;
  color: #fff !important;
}
.line-done {
  background: #28a745 !important;
}

/* Alert Banners */
.alert-container {
  margin-bottom: 24px;
}

.alert-banner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 20px;
  border-radius: 8px;
  font-size: 13.5px;
  line-height: 1.5;
}

.alert-text {
  flex-grow: 1;
  font-weight: 500;
}

.btn-close-alert {
  background: transparent;
  border: none;
  font-size: 20px;
  font-weight: 500;
  line-height: 1;
  cursor: pointer;
  padding: 0 4px;
}

.alert-warning {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeeba;
}
.alert-warning .btn-close-alert {
  color: #856404;
}

.alert-info {
  background: #e8f4fd;
  color: #004085;
  border: 1px solid #d6d8db;
}
.alert-info .btn-close-alert {
  color: #004085;
}

.alert-success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}
.alert-success .btn-close-alert {
  color: #155724;
}

/* Payment Form Box (Single Panel with vertical sections) */
.payment-form-box {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
  padding: 40px;
}

.box-title {
  font-size: 16px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 24px;
}

.details-grid {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.detail-row {
  display: grid;
  grid-template-columns: 240px 1fr;
  align-items: center;
  font-size: 13.5px;
}

@media (max-width: 600px) {
  .detail-row {
    grid-template-columns: 1fr;
    gap: 4px;
  }
}

.detail-label {
  color: #6b7280;
  font-weight: 500;
}

.detail-value {
  color: #111827;
}

.price-val {
  color: #111827 !important;
  font-size: 15px;
}

.font-semibold {
  font-weight: 600;
}
.font-medium {
  font-weight: 500;
}
.font-bold {
  font-weight: 700;
}

.select-all {
  user-select: all;
}

/* Status Badges */
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

.divider-box {
  height: 1px;
  background: #e5e7eb;
  margin: 32px 0;
}

/* Form Section */
.payment-upload-section {
  display: flex;
  flex-direction: column;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 24px;
  max-width: 480px;
}

.field-label {
  font-size: 13.5px;
  font-weight: 600;
  color: #374151;
}

.form-input {
  padding: 10px 14px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 13.5px;
  font-family: inherit;
  width: 100%;
  box-sizing: border-box;
  outline: none;
  transition: all 0.15s ease;
}

.form-input:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 1px var(--primary-color);
}

.form-input.has-error {
  border-color: #ef4444;
}

.input-disabled {
  background: #f3f4f6;
  color: #6b7280;
  cursor: not-allowed;
  border-color: #e5e7eb;
}

.error-msg {
  font-size: 12px;
  color: #ef4444;
  font-weight: 500;
  margin-top: 2px;
}

/* Bukti Pembayaran Box Placement */
.proof-preview-wrapper {
  width: 120px;
  height: 120px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: #f9fafb;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.preview-box, .empty-preview-box {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.landscape-icon {
  width: 48px;
  height: 48px;
  color: #d1d5db;
}

.help-text {
  font-size: 11px;
  color: #6b7280;
}

/* Upload Button container */
.upload-btn-container {
  margin-top: 8px;
}

.hidden-file-input {
  display: none;
}

.btn-upload-trigger {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.15s ease;
}

.btn-upload-trigger:hover {
  filter: brightness(0.9);
}

.upload-icon {
  width: 14px;
  height: 14px;
}

/* Bottom Actions */
.form-actions-bottom {
  border-top: 1px solid #f3f4f6;
  padding-top: 24px;
  margin-top: 8px;
}

.btn-bottom-send {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.15s ease;
}

.btn-bottom-send:hover:not(:disabled) {
  filter: brightness(0.9);
}

.btn-bottom-send:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.send-icon {
  width: 14px;
  height: 14px;
}
</style>
