<template>
  <AdminLayout>
    <Head title="Buat Akun Baru - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <Link :href="route('superadmin.kelol-akun.index')" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        <h1 class="page-title">Buat Akun Baru</h1>
      </Link>
      <button type="submit" form="create-form" class="btn-primary" :disabled="form.processing">
        Buat akun baru
      </button>
    </div>
    <div class="divider" />

    <!-- Form -->
    <div class="content-area">
      <form id="create-form" @submit.prevent="submit">
        <!-- Nama Lengkap -->
        <div class="field-group">
          <label class="field-label">Nama Lengkap</label>
          <input
            v-model="form.name"
            type="text"
            placeholder="Nama lengkap"
            class="field-input"
            :class="{ 'error': form.errors.name }"
          />
          <span v-if="form.errors.name" class="error-msg">{{ form.errors.name }}</span>
        </div>

        <!-- Email -->
        <div class="field-group">
          <label class="field-label">Email</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="example@mail.com"
            class="field-input"
            :class="{ 'error': form.errors.email }"
          />
          <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
        </div>

        <!-- Nomor Telepon -->
        <div class="field-group">
          <label class="field-label">Nomor Telepon</label>
          <input
            v-model="form.telephone"
            type="text"
            placeholder="Nomor telepon"
            class="field-input"
            :class="{ 'error': form.errors.telephone }"
          />
          <span v-if="form.errors.telephone" class="error-msg">{{ form.errors.telephone }}</span>
        </div>

        <!-- Role -->
        <div class="field-group">
          <label class="field-label">Role</label>
          <div class="radio-group">
            <label class="radio-item">
              <input type="radio" v-model="form.role" value="staff" />
              <span class="radio-custom" :class="{ 'checked': form.role === 'staff' }" />
              Petugas
            </label>
            <label class="radio-item">
              <input type="radio" v-model="form.role" value="finance" />
              <span class="radio-custom" :class="{ 'checked': form.role === 'finance' }" />
              Keuangan
            </label>
            <label class="radio-item">
              <input type="radio" v-model="form.role" value="leader" />
              <span class="radio-custom" :class="{ 'checked': form.role === 'leader' }" />
              Ketua
            </label>
          </div>
          <span v-if="form.errors.role" class="error-msg">{{ form.errors.role }}</span>
        </div>

        <!-- Password -->
        <div class="field-group">
          <label class="field-label">Password</label>
          <div class="input-pass-wrap">
            <input
              v-model="form.password"
              :type="showPass ? 'text' : 'password'"
              placeholder="Password"
              class="field-input"
              :class="{ 'error': form.errors.password }"
            />
            <button type="button" class="eye-btn" @click="showPass = !showPass">
              <svg v-if="!showPass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
        </div>

        <!-- Konfirmasi Password -->
        <div class="field-group">
          <label class="field-label">Konfirmasi Password</label>
          <div class="input-pass-wrap">
            <input
              v-model="form.password_confirmation"
              :type="showConfirm ? 'text' : 'password'"
              placeholder="Konfirmasi password"
              class="field-input"
            />
            <button type="button" class="eye-btn" @click="showConfirm = !showConfirm">
              <svg v-if="!showConfirm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Password hint -->
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
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const showPass    = ref(false);
const showConfirm = ref(false);

const form = useForm({
  name:                  '',
  email:                 '',
  telephone:             '',
  role:                  'staff',
  password:              '',
  password_confirmation: '',
});

function submit() {
  form.post(route('superadmin.kelol-akun.store'));
}
</script>

<style scoped>
/* Top bar */
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

.btn-primary {
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 9px 20px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-primary:hover:not(:disabled) { filter: brightness(0.9); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.divider { height: 1px; background: #e5e7eb; }
.content-area { padding: 28px 32px; max-width: 560px; }

/* Fields */
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
.field-input:focus { border-color: var(--primary-color); }
.field-input.error { border-color: #ef4444; }

.error-msg { font-size: 12px; color: #ef4444; margin-top: 4px; display: block; }

/* Radio */
.radio-group { display: flex; flex-direction: column; gap: 10px; }
.radio-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13.5px;
  color: #111;
  cursor: pointer;
}
.radio-item input[type="radio"] { display: none; }

.radio-custom {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: border 0.2s;
}
.radio-custom.checked {
  border-color: var(--primary-color);
  background: #fff;
  box-shadow: inset 0 0 0 4px var(--primary-color);
}

/* Password input */
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
  transition: color 0.2s ease;
}
.eye-btn:hover { color: var(--primary-color); }
.eye-btn svg { width: 18px; height: 18px; }

/* Hint */
.hint-row {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #9ca3af;
}
.hint-row svg { width: 14px; height: 14px; flex-shrink: 0; }
</style>



