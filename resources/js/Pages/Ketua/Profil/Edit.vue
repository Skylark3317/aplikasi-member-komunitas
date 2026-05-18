<template>
  <KetuaLayout>
    <Head title="Edit Profil - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <Link :href="route('ketua.profil')" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        <h1 class="page-title">Edit Profil</h1>
      </Link>
      <div class="top-actions">
        <Link :href="route('ketua.profil')" class="btn-batal">Batal</Link>
        <button type="submit" form="edit-form" class="btn-primary" :disabled="form.processing">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
          </svg>
          Simpan perubahan
        </button>
      </div>
    </div>
    <div class="divider" />

    <!-- Form -->
    <div class="content-area">
      <form id="edit-form" @submit.prevent="submit">
        <!-- Nomor Telepon -->
        <div class="field-group">
          <label class="field-label">Nomor Telepon</label>
          <input
            v-model="form.telephone"
            type="text"
            class="field-input"
            :class="{ 'error': form.errors.telephone }"
          />
          <span v-if="form.errors.telephone" class="error-msg">{{ form.errors.telephone }}</span>
        </div>

        <!-- Ubah Password section -->
        <h3 class="section-title">Ubah Password</h3>

        <!-- Password Lama -->
        <div class="field-group">
          <label class="field-label">Password Lama</label>
          <div class="input-pass-wrap">
            <input
              v-model="form.old_password"
              :type="showOld ? 'text' : 'password'"
              placeholder="Password lama"
              class="field-input"
              :class="{ 'error': form.errors.old_password }"
            />
            <button type="button" class="eye-btn" @click="showOld = !showOld">
              <EyeIcon :open="showOld" />
            </button>
          </div>
          <span v-if="form.errors.old_password" class="error-msg">{{ form.errors.old_password }}</span>
        </div>

        <!-- Password Baru -->
        <div class="field-group">
          <label class="field-label">Password Baru</label>
          <div class="input-pass-wrap">
            <input
              v-model="form.password"
              :type="showNew ? 'text' : 'password'"
              placeholder="Password baru"
              class="field-input"
              :class="{ 'error': form.errors.password }"
            />
            <button type="button" class="eye-btn" @click="showNew = !showNew">
              <EyeIcon :open="showNew" />
            </button>
          </div>
          <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
        </div>

        <!-- Konfirmasi Password Baru -->
        <div class="field-group">
          <label class="field-label">Konfirmasi Password Baru</label>
          <div class="input-pass-wrap">
            <input
              v-model="form.password_confirmation"
              :type="showConfirm ? 'text' : 'password'"
              placeholder="Konfirmasi password baru"
              class="field-input"
            />
            <button type="button" class="eye-btn" @click="showConfirm = !showConfirm">
              <EyeIcon :open="showConfirm" />
            </button>
          </div>
        </div>

        <div class="hint-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          Password minimal 8 karakter dengan kombinasi huruf dan angka
        </div>
      </form>
    </div>
  </KetuaLayout>
</template>

<script setup>
import { ref, h } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import KetuaLayout from '@/Layouts/KetuaLayout.vue';

const props = defineProps({ user: Object });

const showOld     = ref(false);
const showNew     = ref(false);
const showConfirm = ref(false);

const form = useForm({
  telephone:             props.user.telephone ?? '',
  old_password:          '',
  password:              '',
  password_confirmation: '',
});

function submit() {
  form.patch(route('ketua.profil.update'));
}

// Inline EyeIcon component
const EyeIcon = (props) => {
  if (!props.open) {
    return h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8' }, [
      h('path', { d: 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z' }),
      h('circle', { cx: '12', cy: '12', r: '3' }),
    ]);
  }
  return h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8' }, [
    h('path', { d: 'M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94' }),
    h('path', { d: 'M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19' }),
    h('line', { x1: '1', y1: '1', x2: '23', y2: '23' }),
  ]);
};
</script>

<style scoped>
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 32px;
}
.back-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  text-decoration: none;
  color: #111;
}
.back-btn svg { width: 18px; height: 18px; color: #555; }
.page-title { font-size: 20px; font-weight: 700; color: #111; }

.top-actions { display: flex; align-items: center; gap: 10px; }

.btn-batal {
  border: 1px solid #d1d5db;
  background: #fff;
  color: #374151;
  padding: 9px 18px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.2s;
}
.btn-batal:hover { background: #f3f4f6; }

.btn-primary {
  display: flex;
  align-items: center;
  gap: 7px;
  background: #2563eb;
  color: #fff;
  border: none;
  padding: 9px 18px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.2s;
}
.btn-primary:hover:not(:disabled) { filter: brightness(0.9); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-primary svg { width: 15px; height: 15px; }

.divider { height: 1px; background: #e5e7eb; }
.content-area { padding: 28px 32px; max-width: 560px; }

.field-group { margin-bottom: 20px; }
.field-label {
  display: block;
  font-size: 13.5px;
  font-weight: 600;
  color: #111;
  margin-bottom: 8px;
}
.field-input {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 13.5px;
  color: #111;
  outline: none;
  transition: border 0.2s;
  box-sizing: border-box;
}
.field-input:focus { border-color: #2563eb; }
.field-input.error { border-color: #ef4444; }
.error-msg { font-size: 12px; color: #ef4444; margin-top: 4px; display: block; }

.section-title {
  font-size: 17px;
  font-weight: 700;
  color: #111;
  margin: 28px 0 18px;
}

.input-pass-wrap { position: relative; }
.input-pass-wrap .field-input { padding-right: 42px; }

.eye-btn {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: #9ca3af;
  padding: 0;
  display: flex;
}
.eye-btn:hover { color: #555; }
.eye-btn svg { width: 18px; height: 18px; }

.hint-row {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #9ca3af;
  margin-top: 8px;
}
.hint-row svg { width: 14px; height: 14px; flex-shrink: 0; }
</style>
