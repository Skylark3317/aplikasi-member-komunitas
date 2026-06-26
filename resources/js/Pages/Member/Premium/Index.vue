<template>
  <MemberLayout>
    <Head title="Pilih Rencana Keanggotaan - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <div>
        <h1 class="page-title">Pilih Rencana Keanggotaan</h1>
        <p class="page-subtitle">Pilih paket yang sesuai dengan kebutuhan Anda dan mulai nikmati manfaatnya.</p>
      </div>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Flash messages -->
      <div v-if="$page.props.flash?.error" class="flash flash-error">{{ $page.props.flash.error }}</div>
      <div v-if="$page.props.flash?.info"  class="flash flash-info">{{ $page.props.flash.info }}</div>

      <div v-if="plans.length === 0" class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
        <p>Belum ada paket premium yang tersedia saat ini.</p>
      </div>

      <div v-else class="plans-wrapper">
        <!-- Free plan -->
        <div class="plan-card free-card">
          <div class="card-top">
            <span class="plan-badge current-badge">Paket Anda</span>
            <h2 class="plan-name">Member Dasar</h2>
            <p class="plan-tagline">Akses dasar untuk menjelajahi komunitas kami.</p>
            <div class="price-row">
              <span class="price-currency">Rp</span>
              <span class="price-value">0</span>
              <span class="price-period">/ selamanya</span>
            </div>
          </div>
          <div class="card-divider" />
          <ul class="features-list">
            <li v-for="(feature, i) in settings.available_benefits" :key="i" class="feature-item locked">
              <span class="icon-lock"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
              <span>{{ feature }}</span>
            </li>
            <li v-if="!settings.available_benefits || settings.available_benefits.length === 0" class="feature-item muted">
              <span class="icon-lock"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg></span>
              <span>Tidak ada benefit yang ditentukan</span>
            </li>
          </ul>
          <button class="btn-plan btn-current" disabled>Paket Aktif</button>
        </div>

        <!-- Dynamic premium plans -->
        <div
          v-for="plan in plans"
          :key="plan.id"
          :class="['plan-card', 'premium-card', plan.is_recommended ? 'recommended-card' : '']"
        >
          <!-- Recommended ribbon -->
          <div v-if="plan.is_recommended" class="ribbon">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Direkomendasikan
          </div>

          <div class="card-top">
            <h2 class="plan-name">{{ plan.name }}</h2>
            <p v-if="plan.description" class="plan-tagline">{{ plan.description }}</p>
            <div class="price-row">
              <span class="price-currency">Rp</span>
              <span class="price-value">{{ formatCurrency(plan.price) }}</span>
              <span class="price-period">/ {{ plan.duration_label }}</span>
            </div>
          </div>
          <div class="card-divider" />

          <!-- Features -->
          <ul class="features-list">
            <li v-for="(feature, i) in plan.features" :key="i" class="feature-item">
              <span class="icon-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
              <span>{{ feature }}</span>
            </li>
            <li v-if="!plan.features || plan.features.length === 0" class="feature-item muted">
              <span class="icon-lock"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg></span>
              <span>Tidak ada benefit yang ditentukan</span>
            </li>
          </ul>

          <button
            @click="choosePlan(plan)"
            :class="['btn-plan', plan.is_recommended ? 'btn-recommended' : 'btn-premium']"
            :disabled="processing"
          >
            {{ processing ? 'Memproses...' : 'Pilih Paket Ini' }}
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
  plans: { type: Array, default: () => [] },
  isPremium: Boolean,
});

const processing = ref(false);

function choosePlan(plan) {
  processing.value = true;
  router.post(route('member.premium.join'), { plan_id: plan.id }, {
    onFinish: () => { processing.value = false; }
  });
}

function formatCurrency(val) {
  return new Intl.NumberFormat('id-ID').format(val || 0);
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  padding: 20px 32px 16px;
  background: #fff;
}
.page-title {
  font-size: 20px;
  font-weight: 700;
  color: #111;
  margin: 0 0 4px;
}
.page-subtitle {
  font-size: 13px;
  color: #6b7280;
  margin: 0;
}
.divider { height: 1px; background: #e5e7eb; }

.flash {
  padding: 10px 16px; border-radius: 8px;
  font-size: 13.5px; margin-bottom: 20px;
}
.flash-error { background: #fee2e2; color: #991b1b; }
.flash-info  { background: #dbeafe; color: #1e40af; }

/* Content Area */
.content-area {
  padding: 32px;
  background: #f3f4f6;
  min-height: calc(100vh - 66px);
}

.empty-state {
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 80px 20px; text-align: center; color: #9ca3af;
}
.empty-state svg { width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.4; }
.empty-state p { font-size: 14px; }

/* Plans Grid */
.plans-wrapper {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 24px;
  max-width: 1200px;
  margin: 0 auto;
  align-items: stretch;
}

/* Plan Card Base */
.plan-card {
  background: #fff;
  border-radius: 20px;
  border: 1.5px solid #e5e7eb;
  padding: 28px 24px 24px;
  position: relative;
  display: flex;
  flex-direction: column;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  transition: transform 0.25s, box-shadow 0.25s;
  overflow: hidden;
}
.plan-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.08);
}

/* Recommended card accent */
.recommended-card {
  border-color: var(--primary-color, #007bff);
  box-shadow: 0 4px 20px rgba(0, 123, 255, 0.12);
}

/* Free card subtle styling */
.free-card {
  background: #fafafa;
}

/* Ribbon */
.ribbon {
  position: absolute;
  top: 0; right: 0;
  background: var(--primary-color, #007bff);
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  padding: 6px 14px 6px 10px;
  border-radius: 0 18px 0 14px;
  display: flex;
  align-items: center;
  gap: 4px;
  letter-spacing: 0.02em;
}
.ribbon svg { width: 11px; height: 11px; fill: #fff; }

/* Badge */
.plan-badge {
  display: inline-block;
  font-size: 10px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 10px;
}
.current-badge {
  background: #f3f4f6;
  color: #6b7280;
}

/* Card top section */
.card-top { margin-bottom: 2px; }
.plan-name {
  font-size: 20px; font-weight: 700; color: #111;
  margin: 0 0 6px;
}
.plan-tagline {
  font-size: 13px; color: #6b7280; line-height: 1.5;
  margin: 0 0 14px;
}

.price-row {
  display: flex; align-items: baseline; gap: 2px;
  margin-bottom: 6px; flex-wrap: wrap;
}
.price-currency { font-size: 15px; font-weight: 600; color: #374151; }
.price-value    { font-size: 34px; font-weight: 800; color: #111; letter-spacing: -1px; line-height: 1; }
.price-period   { font-size: 13px; color: #9ca3af; margin-left: 4px; }

.card-divider { height: 1px; background: #e5e7eb; margin: 18px 0; }

/* Features list */
.features-list {
  list-style: none; padding: 0; margin: 0 0 24px;
  display: flex; flex-direction: column; gap: 12px;
  flex-grow: 1;
}
.feature-item {
  display: flex; align-items: center; gap: 10px;
  font-size: 13.5px; color: #374151; font-weight: 500;
}
.feature-item.locked {
  color: #9ca3af;
}
.feature-item.muted {
  color: #d1d5db; font-style: italic;
}

.icon-check {
  width: 20px; height: 20px; border-radius: 50%;
  background: #d1fae5; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.icon-check svg { width: 11px; height: 11px; stroke: #059669; }

.icon-lock {
  width: 20px; height: 20px; border-radius: 50%;
  background: #f3f4f6; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.icon-lock svg { width: 11px; height: 11px; stroke: #9ca3af; }

/* Buttons */
.btn-plan {
  width: 100%; padding: 13px;
  border-radius: 10px; border: none;
  font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
  font-family: inherit;
  margin-top: auto;
}
.btn-current {
  background: #f3f4f6; color: #9ca3af; cursor: not-allowed;
}
.btn-premium {
  background: #f0f7ff;
  color: var(--primary-color, #007bff);
  border: 1.5px solid var(--primary-color, #007bff);
}
.btn-premium:hover:not(:disabled) {
  background: var(--primary-color, #007bff);
  color: #fff;
  transform: translateY(-1px);
}
.btn-recommended {
  background: var(--primary-color, #007bff);
  color: #fff;
  box-shadow: 0 4px 14px rgba(0, 123, 255, 0.3);
}
.btn-recommended:hover:not(:disabled) {
  filter: brightness(1.08);
  transform: translateY(-1px);
}
.btn-plan:disabled { opacity: 0.6; cursor: not-allowed; transform: none !important; }

@media (max-width: 768px) {
  .plans-wrapper { grid-template-columns: 1fr; max-width: 440px; margin: 0 auto; }
  .content-area { padding: 20px 16px; }
}
</style>
