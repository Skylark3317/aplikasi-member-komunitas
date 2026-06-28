<template>
  <AdminLayout>
    <Head title="Paket Premium - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Paket Premium</h1>
      <button class="btn-primary" @click="openCreate">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Paket
      </button>
    </div>
    <div class="divider" />

    <!-- Flash messages -->
    <div v-if="$page.props.flash?.success" class="flash flash-success">
      {{ $page.props.flash.success }}
    </div>
    <div v-if="$page.props.flash?.error" class="flash flash-error">
      {{ $page.props.flash.error }}
    </div>

    <!-- Content -->
    <div class="content-area">
      <p class="page-desc">
        Kelola jenis paket keanggotaan premium yang ditampilkan kepada member. Atur harga,
        masa aktif, dan fitur setiap paket.
      </p>

      <!-- Empty state -->
      <div v-if="plans.length === 0" class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <p>Belum ada paket premium. Klik "Tambah Paket" untuk membuat paket pertama.</p>
      </div>

      <!-- Plans grid -->
      <div v-else class="plans-grid">
        <div
          v-for="plan in plans"
          :key="plan.id"
          :class="['plan-card', !plan.is_active ? 'inactive' : '', plan.is_recommended ? 'recommended' : '']"
        >
          <div class="card-header">
            <div class="card-titles">
              <h3 class="card-name">{{ plan.name }}</h3>
              <span v-if="plan.is_recommended" class="badge-recommended">Direkomendasikan</span>
              <span v-if="!plan.is_active" class="badge-inactive">Nonaktif</span>
            </div>
          </div>

          <div class="card-price">
            <span class="currency">Rp</span>
            <span class="amount">{{ formatCurrency(plan.price) }}</span>
          </div>
          <div class="card-period">{{ plan.duration_label }}</div>

          <p v-if="plan.description" class="card-desc">{{ plan.description }}</p>

          <ul v-if="plan.features && plan.features.length" class="card-features">
            <li v-for="(f, i) in plan.features" :key="i">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <span>{{ f }}</span>
            </li>
          </ul>

          <div class="card-actions">
            <button class="btn-action" @click="openEdit(plan)" title="Edit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Edit
            </button>
            <button
              :class="['btn-action', plan.is_active ? '' : 'btn-activate']"
              @click="toggleStatus(plan)"
              :title="plan.is_active ? 'Nonaktifkan' : 'Aktifkan'"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
              </svg>
              {{ plan.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
            <button
              :class="['btn-action', plan.is_recommended ? 'btn-unfeature' : 'btn-feature']"
              @click="toggleRecommended(plan)"
              :title="plan.is_recommended ? 'Hapus Rekomendasi' : 'Tandai Rekomendasi'"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              {{ plan.is_recommended ? 'Hapus Rekomendasi' : 'Rekomendasikan' }}
            </button>
            <button class="btn-action btn-danger" @click="confirmDelete(plan)" title="Hapus">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ════ MODAL FORM ════ -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-box">
        <div class="modal-header">
          <h2>{{ editing ? 'Edit Paket' : 'Tambah Paket Premium' }}</h2>
          <button class="modal-close" @click="closeModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitForm" class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Paket <span class="req">*</span></label>
            <input v-model="form.name" type="text" class="form-input" placeholder="mis. Membership Tahunan" />
            <span v-if="form.errors.name" class="error-msg">{{ form.errors.name }}</span>
          </div>

          <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea v-model="form.description" class="form-textarea" rows="2" placeholder="Deskripsi singkat paket"></textarea>
            <span v-if="form.errors.description" class="error-msg">{{ form.errors.description }}</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Biaya (Rp) <span class="req">*</span></label>
              <input v-model="form.price" type="number" min="0" class="form-input" placeholder="50000" />
              <span v-if="form.errors.price" class="error-msg">{{ form.errors.price }}</span>
            </div>
            <div class="form-group">
              <label class="form-label">Urutan Tampil</label>
              <input v-model="form.sort_order" type="number" min="0" class="form-input" placeholder="1" />
              <span v-if="form.errors.sort_order" class="error-msg">{{ form.errors.sort_order }}</span>
            </div>
          </div>

          <!-- Lifetime toggle -->
          <div class="lifetime-row">
            <label class="switch">
              <input type="checkbox" v-model="form.is_lifetime" />
              <span class="slider"></span>
            </label>
            <span class="lifetime-label">Masa aktif seumur hidup (lifetime)</span>
          </div>

          <!-- Duration (hidden if lifetime) -->
          <div v-if="!form.is_lifetime" class="form-row">
            <div class="form-group">
              <label class="form-label">Durasi <span class="req">*</span></label>
              <input v-model="form.duration" type="number" min="0" class="form-input" placeholder="12" />
              <span v-if="form.errors.duration" class="error-msg">{{ form.errors.duration }}</span>
            </div>
            <div class="form-group">
              <label class="form-label">Satuan <span class="req">*</span></label>
              <select v-model="form.duration_unit" class="form-input">
                <option value="day">Hari</option>
                <option value="month">Bulan</option>
                <option value="year">Tahun</option>
              </select>
              <span v-if="form.errors.duration_unit" class="error-msg">{{ form.errors.duration_unit }}</span>
            </div>
          </div>

          <!-- Features -->
          <div class="form-group">
            <label class="form-label">Fitur Paket (Pilihan Benefit)</label>
            <p class="field-hint">Pilih benefit yang termasuk dalam paket ini. (Diatur dari menu Pengaturan)</p>
            <div v-if="availableBenefits && availableBenefits.length > 0" style="display: grid; gap: 8px; margin-top: 8px;">
              <label v-for="(benefit, i) in availableBenefits" :key="i" style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; cursor: pointer; color: #374151;">
                <input type="checkbox" :value="benefit" v-model="form.features" style="width: 16px; height: 16px; accent-color: var(--primary-color); cursor: pointer;" />
                <span>{{ benefit }}</span>
              </label>
            </div>
            <div v-else style="font-size: 13px; color: #9ca3af; margin-top: 8px; padding: 12px; background: #f9fafb; border-radius: 6px; border: 1px dashed #d1d5db;">
              Belum ada pilihan benefit. Silakan tambah benefit di menu <a :href="route('superadmin.pengaturan.index')" style="color: var(--primary-color); text-decoration: none;">Pengaturan</a>.
            </div>
            <span v-if="form.errors.features" class="error-msg">{{ form.errors.features }}</span>
          </div>

          <div class="toggle-row">
            <label class="switch">
              <input type="checkbox" v-model="form.is_active" />
              <span class="slider"></span>
            </label>
            <span>Aktifkan paket (tampil di halaman member)</span>
          </div>
          <div class="toggle-row">
            <label class="switch">
              <input type="checkbox" v-model="form.is_recommended" />
              <span class="slider"></span>
            </label>
            <span>Tandai sebagai paket "Direkomendasikan"</span>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn-secondary" @click="closeModal">Batal</button>
            <button type="submit" class="btn-primary" :disabled="form.processing">
              {{ form.processing ? 'Menyimpan...' : (editing ? 'Simpan Perubahan' : 'Buat Paket') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ════ DELETE CONFIRMATION ════ -->
    <div v-if="showDeleteModal" class="modal-overlay" @click.self="showDeleteModal = false">
      <div class="modal-box modal-sm">
        <div class="modal-header">
          <h2>Hapus Paket</h2>
        </div>
        <div class="modal-body">
          <p class="confirm-text">
            Yakin ingin menghapus paket <strong>{{ planToDelete?.name }}</strong>?
            Tindakan ini tidak dapat dibatalkan.
          </p>
          <div class="modal-footer">
            <button type="button" class="btn-secondary" @click="showDeleteModal = false">Batal</button>
            <button type="button" class="btn-danger-solid" :disabled="deleteForm.processing" @click="submitDelete">
              {{ deleteForm.processing ? 'Menghapus...' : 'Hapus' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ plans: Array, availableBenefits: Array });

const showModal = ref(false);
const editing = ref(null);
const showDeleteModal = ref(false);
const planToDelete = ref(null);

const blankForm = {
  name: '',
  description: '',
  price: '',
  duration: 1,
  duration_unit: 'month',
  is_lifetime: false,
  features: [],
  is_recommended: false,
  is_active: true,
  sort_order: 0,
};

const form = useForm({ ...blankForm });
const deleteForm = useForm({});

function formatCurrency(val) {
  return new Intl.NumberFormat('id-ID').format(val || 0);
}

function openCreate() {
  editing.value = null;
  form.reset();
  Object.assign(form, {
    ...blankForm,
    features: [],
  });
  form.clearErrors();
  showModal.value = true;
}

function openEdit(plan) {
  editing.value = plan;
  form.reset();
  form.name = plan.name;
  form.description = plan.description ?? '';
  form.price = plan.price;
  form.duration = plan.duration;
  form.duration_unit = plan.duration_unit;
  form.is_lifetime = plan.is_lifetime;
  form.features = plan.features && plan.features.length ? [...plan.features] : [];
  form.is_recommended = plan.is_recommended;
  form.is_active = plan.is_active;
  form.sort_order = plan.sort_order;
  form.clearErrors();
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  editing.value = null;
}

// function addFeature() removed
// function removeFeature(i) removed

function submitForm() {
  const payload = {
    name: form.name,
    description: form.description || null,
    price: form.price,
    duration: form.duration,
    duration_unit: form.duration_unit,
    is_lifetime: form.is_lifetime,
    features: form.features.filter((f) => f && f.trim() !== ''),
    is_recommended: form.is_recommended,
    is_active: form.is_active,
    sort_order: form.sort_order ?? 0,
  };

  if (editing.value) {
    if (!window.confirm("Apakah Anda yakin ingin menyimpan perubahan pada paket ini?")) {
      return;
    }
    form.transform(() => payload).patch(
      route('superadmin.paket-premium.update', { plan: editing.value.id }),
      { onSuccess: () => { showModal.value = false; } }
    );
  } else {
    form.transform(() => payload).post(
      route('superadmin.paket-premium.store'),
      { onSuccess: () => { showModal.value = false; } }
    );
  }
}

function toggleStatus(plan) {
  router.patch(route('superadmin.paket-premium.toggle-status', { plan: plan.id }), {}, {
    preserveScroll: true,
  });
}

function toggleRecommended(plan) {
  router.patch(route('superadmin.paket-premium.toggle-recommended', { plan: plan.id }), {}, {
    preserveScroll: true,
  });
}

function confirmDelete(plan) {
  planToDelete.value = plan;
  showDeleteModal.value = true;
}

function submitDelete() {
  deleteForm.delete(route('superadmin.paket-premium.destroy', { plan: planToDelete.value.id }), {
    onSuccess: () => {
      showDeleteModal.value = false;
      planToDelete.value = null;
    },
  });
}
</script>

<style scoped>
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 32px;
}
.page-title { font-size: 20px; font-weight: 700; color: #111; }
.divider { height: 1px; background: #e5e7eb; }

.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  border: 1px solid transparent;
  background: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
  transition: filter 0.2s;
}
.btn-primary:hover:not(:disabled) { filter: brightness(0.9); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-primary svg { width: 15px; height: 15px; }

.flash {
  margin: 12px 32px 0;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 13.5px;
}
.flash-success { background: #d1fae5; color: #065f46; }
.flash-error { background: #fee2e2; color: #991b1b; }

.content-area {
  padding: 24px 32px;
  background: #f9fafb;
  min-height: calc(100vh - 76px);
  box-sizing: border-box;
}
.page-desc { font-size: 13.5px; color: #6b7280; margin: 0 0 24px; max-width: 700px; }

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #9ca3af;
}
.empty-state svg { width: 48px; height: 48px; margin-bottom: 12px; }

/* Plans grid */
.plans-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.plan-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  transition: box-shadow 0.2s, transform 0.2s;
  position: relative;
}
.plan-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
.plan-card.recommended { border-color: var(--primary-color); box-shadow: 0 6px 18px rgba(0,123,255,0.10); }
.plan-card.inactive { opacity: 0.65; }

.card-header { margin-bottom: 14px; }
.card-titles { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.card-name { font-size: 17px; font-weight: 700; color: #111; margin: 0; }

.badge-recommended {
  background: #fef3c7; color: #92400e;
  font-size: 10px; font-weight: 700;
  padding: 3px 8px; border-radius: 10px;
  text-transform: uppercase;
}
.badge-inactive {
  background: #f3f4f6; color: #6b7280;
  font-size: 10px; font-weight: 700;
  padding: 3px 8px; border-radius: 10px;
  text-transform: uppercase;
}

.card-price { display: flex; align-items: baseline; gap: 2px; }
.card-price .currency { font-size: 14px; font-weight: 600; color: #111; }
.card-price .amount { font-size: 26px; font-weight: 800; color: #111; letter-spacing: -0.5px; }
.card-period { font-size: 12.5px; color: var(--primary-color); font-weight: 600; margin-top: 2px; margin-bottom: 12px; }

.card-desc { font-size: 12.5px; color: #6b7280; line-height: 1.5; margin: 0 0 16px; }

.card-features {
  list-style: none; padding: 0; margin: 0 0 20px;
  display: flex; flex-direction: column; gap: 8px;
  flex: 1;
}
.card-features li {
  display: flex; align-items: center; gap: 8px;
  font-size: 12.5px; color: #374151;
}
.card-features svg { width: 14px; height: 14px; color: #28a745; flex-shrink: 0; }

.card-actions {
  display: flex; flex-wrap: wrap; gap: 8px;
  border-top: 1px solid #f3f4f6; padding-top: 14px;
}
.btn-action {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 10px; font-size: 12px; font-weight: 500;
  background: #f9fafb; color: #374151;
  border: 1px solid #e5e7eb; border-radius: 6px;
  cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.btn-action:hover { background: #f3f4f6; }
.btn-action svg { width: 14px; height: 14px; }
.btn-activate { color: #047857; border-color: #a7f3d0; }
.btn-feature { color: #b45309; border-color: #fcd34d; }
.btn-unfeature { color: #92400e; background: #fef3c7; border-color: #fcd34d; }
.btn-danger { color: #dc2626; border-color: #fca5a5; }
.btn-danger:hover { background: #fef2f2; }

/* ════ MODAL ════ */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(17, 24, 39, 0.55);
  display: flex; align-items: center; justify-content: center;
  z-index: 200; padding: 20px;
}
.modal-box {
  background: #fff; border-radius: 12px;
  width: 100%; max-width: 560px; max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}
.modal-sm { max-width: 420px; }
.modal-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 24px; border-bottom: 1px solid #f3f4f6;
}
.modal-header h2 { font-size: 16px; font-weight: 700; color: #111; margin: 0; }
.modal-close {
  background: none; border: none; cursor: pointer; padding: 4px;
  color: #6b7280; display: flex;
}
.modal-close svg { width: 20px; height: 20px; }

.modal-body { padding: 20px 24px; }
.confirm-text { font-size: 14px; color: #374151; line-height: 1.5; }

.form-group { margin-bottom: 14px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-label { display: block; font-size: 13px; font-weight: 500; color: #111; margin-bottom: 6px; }
.req { color: #ef4444; }
.form-input, .form-textarea {
  width: 100%; border: 1px solid #d1d5db; border-radius: 7px;
  padding: 9px 12px; font-size: 13.5px; color: #111;
  outline: none; box-sizing: border-box; font-family: inherit;
  transition: border 0.2s; background: #fff;
}
.form-input:focus, .form-textarea:focus { border-color: var(--primary-color); }
.form-textarea { resize: vertical; }
.error-msg { font-size: 12px; color: #ef4444; margin-top: 4px; display: block; }
.field-hint { font-size: 11.5px; color: #9ca3af; margin: 0 0 8px; }

.lifetime-row, .toggle-row {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 14px; font-size: 13px; color: #374151;
}

.feature-row {
  display: flex; gap: 8px; align-items: center; margin-bottom: 8px;
}
.btn-remove {
  flex-shrink: 0; background: #fff; border: 1px solid #e5e7eb;
  border-radius: 6px; padding: 8px; cursor: pointer; color: #dc2626;
  display: flex;
}
.btn-remove svg { width: 16px; height: 16px; }
.btn-add-feature {
  display: inline-flex; align-items: center; gap: 6px;
  background: none; border: none; cursor: pointer;
  font-size: 12.5px; font-weight: 500; color: var(--primary-color);
  padding: 4px 0; font-family: inherit;
}
.btn-add-feature svg { width: 14px; height: 14px; }

/* Switch */
.switch {
  position: relative; display: inline-block;
  width: 38px; height: 22px; flex-shrink: 0;
}
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
  position: absolute; cursor: pointer; inset: 0;
  background: #d1d5db; border-radius: 22px; transition: 0.2s;
}
.slider::before {
  content: ''; position: absolute; height: 16px; width: 16px;
  left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.2s;
}
.switch input:checked + .slider { background: var(--primary-color); }
.switch input:checked + .slider::before { transform: translateX(16px); }

.modal-footer {
  display: flex; justify-content: flex-end; gap: 10px;
  margin-top: 20px; padding-top: 16px; border-top: 1px solid #f3f4f6;
}
.btn-secondary {
  padding: 9px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 500;
  background: #fff; color: #374151; border: 1px solid #d1d5db; cursor: pointer;
  font-family: inherit;
}
.btn-secondary:hover { background: #f9fafb; }
.btn-danger-solid {
  padding: 9px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 500;
  background: #dc2626; color: #fff; border: 1px solid #dc2626; cursor: pointer;
  font-family: inherit;
}
.btn-danger-solid:hover:not(:disabled) { filter: brightness(0.92); }
.btn-danger-solid:disabled { opacity: 0.6; cursor: not-allowed; }

@media (max-width: 768px) {
  .form-row { grid-template-columns: 1fr; }
}
</style>
