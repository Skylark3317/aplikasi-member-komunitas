<template>
  <MemberLayout>
    <Head title="Pilih Rencana Keanggotaan - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Pilih Rencana Keanggotaan</h1>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <div class="plans-container">
        <!-- Card 1: Member Biasa -->
        <div class="plan-card normal-plan active-plan">
          <div class="plan-badge">Rencana Anda Saat Ini</div>
          <h2 class="plan-title">Member Biasa</h2>
          <div class="plan-price">
            <span class="currency">Rp</span>
            <span class="amount">0</span>
            <span class="period">/ selamanya</span>
          </div>
          <p class="plan-desc">Akses dasar ke forum diskusi komunitas.</p>
          <div class="divider-card" />
          <ul class="plan-features">
            <li>
              <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>Akses Halaman Tanya Jawab</span>
            </li>
            <li class="disabled-feature">
              <svg class="cross-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
              <span>Video Pembelajaran Eksklusif</span>
            </li>
            <li class="disabled-feature">
              <svg class="cross-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
              <span>E-Book & Modul Edukasi Lengkap</span>
            </li>
          </ul>
          <button class="btn-plan btn-plan-disabled" disabled>Rencana Aktif</button>
        </div>

        <!-- Card 2: Member Premium -->
        <div class="plan-card premium-plan highlight-plan">
          <div class="plan-badge-premium">Rekomendasi</div>
          <h2 class="plan-title">Member Premium</h2>
          <div class="plan-price">
            <span class="currency">Rp</span>
            <span class="amount">{{ formatCurrency(settings.membership_fee || 50000) }}</span>
            <span class="period">/ tahun</span>
          </div>
          <p class="plan-desc">Akses penuh tanpa batas ke semua layanan & konten pembelajaran berkualitas tinggi.</p>
          <div class="divider-card" />
          <ul class="plan-features">
            <li>
              <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>Akses Halaman Tanya Jawab Petugas Ahli</span>
            </li>
            <li>
              <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>Nonton Puluhan Video Pembelajaran Eksklusif</span>
            </li>
            <li>
              <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>Download E-Book & Modul Praktik Terstruktur</span>
            </li>
          </ul>
          <button @click="choosePremium" class="btn-plan btn-plan-active" :disabled="processing">
            {{ processing ? 'Memproses...' : 'Pilih Member Premium' }}
          </button>
        </div>
      </div>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  settings: Object,
  isPremium: Boolean,
});

const processing = ref(false);

function choosePremium() {
  processing.value = true;
  router.post(route('member.premium.join'), {}, {
    onFinish: () => {
      processing.value = false;
    }
  });
}

function formatCurrency(val) {
  return new Intl.NumberFormat('id-ID').format(val);
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
  padding: 40px 32px;
  background: #f9fafb;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
}

.plans-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 32px;
  max-width: 900px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .plans-container {
    grid-template-columns: 1fr;
    max-width: 450px;
  }
}

/* Plan Card */
.plan-card {
  background: #fff;
  border-radius: 16px;
  border: 1px solid #e5e7eb;
  padding: 32px;
  position: relative;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
  transition: all 0.25s ease;
}

.plan-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
}

.highlight-plan {
  border: 2px solid #007bff;
  box-shadow: 0 8px 20px rgba(0, 123, 255, 0.08);
}

.plan-badge {
  position: absolute;
  top: 16px;
  right: 16px;
  background: #e3f2fd;
  color: #007bff;
  font-size: 10px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 12px;
  text-transform: uppercase;
}

.plan-badge-premium {
  position: absolute;
  top: 16px;
  right: 16px;
  background: #ffc107;
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 12px;
  text-transform: uppercase;
}

.plan-title {
  font-size: 20px;
  font-weight: 700;
  color: #111;
  margin-top: 12px;
  margin-bottom: 8px;
}

.plan-desc {
  font-size: 13px;
  color: #6b7280;
  line-height: 1.5;
  margin-bottom: 24px;
}

.plan-price {
  display: flex;
  align-items: baseline;
  margin-bottom: 16px;
}

.plan-price .currency {
  font-size: 16px;
  font-weight: 600;
  color: #111;
  margin-right: 2px;
}

.plan-price .amount {
  font-size: 32px;
  font-weight: 800;
  color: #111;
  letter-spacing: -0.5px;
}

.plan-price .period {
  font-size: 13px;
  color: #6b7280;
  margin-left: 4px;
}

.divider-card {
  height: 1px;
  background: #e5e7eb;
  margin: 12px 0 24px;
}

/* Features */
.plan-features {
  list-style: none;
  padding: 0;
  margin: 0 0 32px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  flex-grow: 1;
}

.plan-features li {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: #374151;
  font-weight: 500;
}

.check-icon {
  width: 16px;
  height: 16px;
  color: #28a745;
  flex-shrink: 0;
}

.cross-icon {
  width: 16px;
  height: 16px;
  color: #dc3545;
  flex-shrink: 0;
}

.disabled-feature {
  color: #9ca3af !important;
  text-decoration: line-through;
}

/* Plan Button */
.btn-plan {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: all 0.15s ease;
  box-sizing: border-box;
}

.btn-plan-disabled {
  background: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
}

.btn-plan-active {
  background: #007bff;
  color: #fff;
}

.btn-plan-active:hover:not(:disabled) {
  background: #0056b3;
  transform: translateY(-1px);
}
</style>
