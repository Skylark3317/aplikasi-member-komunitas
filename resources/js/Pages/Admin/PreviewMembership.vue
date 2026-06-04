<template>
  <div class="preview-membership-wrapper">
    <div class="mock-invoice-container">
      
      <!-- RINCIAN TAGIHAN -->
      <h3 class="section-heading">Rincian Tagihan</h3>
      <div class="details-table">
        <div class="detail-row">
          <span class="detail-label">Nomor Invoice</span>
          <span class="detail-value">-</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Tanggal Tagihan</span>
          <span class="detail-value">-</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Jumlah</span>
          <span class="detail-value text-bold">Rp{{ formatNumber(form.membership_fee || 50000) }},00</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Atas Nama Rekening Tujuan</span>
          <span class="detail-value">{{ form.bank_account_name || 'AMK' }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Nomor Rekening Tujuan</span>
          <span class="detail-value">{{ form.bank_account_number || '000000001111' }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Nama Bank Tujuan</span>
          <span class="detail-value">{{ form.bank_name || 'Bank BRI' }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Status</span>
          <span class="detail-value"><span class="badge-unpaid">Belum dibayar</span></span>
        </div>
      </div>

      <div class="divider"></div>

      <!-- PEMBAYARAN -->
      <h3 class="section-heading">Pembayaran</h3>
      
      <div class="form-group">
        <label class="form-label">Bukti Pembayaran</label>
        <div class="upload-placeholder">
          <svg viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        </div>
        <p class="upload-hint">Format JPG, PNG, atau PDF, ukuran maksimal 1MB</p>
        <button class="btn-blue w-auto">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          Unggah bukti pembayaran
        </button>
      </div>

      <div class="form-group">
        <label class="form-label">Tanggal Pembayaran</label>
        <div class="input-wrap">
          <input type="text" class="form-input" value="04/06/2026" readonly />
          <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Atas Nama Pengirim</label>
        <input type="text" class="form-input" placeholder="Atas Nama Pengirim" readonly />
      </div>

      <div class="form-group">
        <label class="form-label">Nomor Rekening Pengirim</label>
        <input type="text" class="form-input" placeholder="000000000000" readonly />
      </div>

      <div class="form-group">
        <label class="form-label">Nama Bank Pengirim</label>
        <input type="text" class="form-input" placeholder="Nama Bank Pengirim" readonly />
      </div>

      <div class="divider" style="margin-top: 30px;"></div>

      <button class="btn-blue w-auto mt-2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        Kirim bukti pembayaran
      </button>

    </div>
  </div>
</template>

<script setup>
defineProps({
  form: Object,
});

function formatNumber(num) {
  return Number(num).toLocaleString('id-ID');
}
</script>

<style scoped>
.preview-membership-wrapper {
  width: 100%;
  height: 100%;
  background: #f8fafc;
  overflow-y: auto;
  display: flex;
  justify-content: center;
  padding: 20px;
}
.preview-membership-wrapper::-webkit-scrollbar { width: 4px; }
.preview-membership-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

.mock-invoice-container {
  width: 100%;
  max-width: 650px;
  height: fit-content;
  background: #ffffff;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  padding: 30px 40px;
  font-family: 'Inter', sans-serif;
  color: #374151;
  /* Scale down slightly to fit the preview panel better without needing too much scroll */
  transform-origin: top center;
  transform: scale(0.9);
  margin-bottom: -10%; /* Adjust margin since scaled */
}

.section-heading {
  font-size: 15px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 20px 0;
}

.details-table {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.detail-row {
  display: flex;
}

.detail-label {
  width: 220px;
  font-size: 13.5px;
  color: #6b7280;
  flex-shrink: 0;
}

.detail-value {
  font-size: 13.5px;
  color: #111827;
  font-weight: 500;
}

.text-bold {
  font-weight: 700;
}

.badge-unpaid {
  display: inline-block;
  background: #fee2e2;
  color: #ef4444;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 4px;
}

.divider {
  height: 1px;
  background: #f3f4f6;
  margin: 30px 0;
}

.form-group {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}

.upload-placeholder {
  width: 120px;
  height: 120px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.upload-placeholder svg {
  width: 48px;
  height: 48px;
}

.upload-hint {
  font-size: 11px;
  color: #6b7280;
  margin: 0 0 4px 0;
}

.btn-blue {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #007bff;
  color: #ffffff;
  border: none;
  border-radius: 4px;
  padding: 10px 16px;
  font-size: 13px;
  font-weight: 600;
  cursor: default;
}
.btn-blue svg { width: 16px; height: 16px; }
.w-auto { width: fit-content; }
.mt-2 { margin-top: 8px; }

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.form-input {
  width: 100%;
  max-width: 450px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  padding: 10px 14px;
  font-size: 13px;
  color: #6b7280;
  background: #ffffff;
  outline: none;
}
.form-input::placeholder { color: #9ca3af; }

.input-icon {
  position: absolute;
  right: 14px;
  width: 16px;
  height: 16px;
  color: #6b7280;
  /* Since max-width is used, we have to calculate position or just adjust wrap */
}
.input-wrap .form-input { max-width: 100%; } /* override for relative wrap */
.input-wrap { max-width: 450px; }
</style>
