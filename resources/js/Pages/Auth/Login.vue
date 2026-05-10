<template>
  <AppLayout>
    <Head title="Login - AMK" />

    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">Login</h1>
      </div>
    </div>

    <div class="auth-wrapper">
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="example@mail.com"
            autocomplete="username"
            required
          />
          <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-icon-wrap">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Password"
              autocomplete="current-password"
              required
            />
            <button type="button" class="toggle-pw" @click="showPassword = !showPassword">
              {{ showPassword ? '🙈' : '👁' }}
            </button>
          </div>
          <span v-if="form.errors.password" class="field-error">{{ form.errors.password }}</span>
          <div class="forgot-link-row">
            <Link :href="route('password.request')" class="link-blue">Lupa password?</Link>
          </div>
        </div>

        <button type="submit" class="btn-submit" :disabled="form.processing">Login</button>

        <p class="auth-alt">
          Belum daftar membership?
          <Link :href="route('register')" class="link-blue">Daftar membership</Link>
        </p>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const showPassword = ref(false);
const form = useForm({ email: '', password: '', remember: false });

const submit = () => {
  form.post(route('login'), { onFinish: () => form.reset('password') });
};
</script>

<style scoped>
.page-header { background: var(--primary-color); padding: 110px 0 32px; }
.page-header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.page-title { color: #fff; font-size: 22px; font-weight: 700; }

.auth-wrapper {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 56px 24px 80px;
  background: #f9fafb;
  min-height: 400px;
}
.auth-form { width: 100%; max-width: 440px; }
.form-group { margin-bottom: 20px; }
.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  color: #111;
  margin-bottom: 6px;
}
.form-group input {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  padding: 10px 14px;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s;
}
.form-group input:focus { border-color: var(--primary-color); }
.input-icon-wrap { position: relative; }
.input-icon-wrap input { padding-right: 42px; }
.toggle-pw {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 16px;
  padding: 0;
}
.forgot-link-row { text-align: right; margin-top: 6px; }
.link-blue { color: var(--primary-color); text-decoration: none; font-size: 13px; }
.link-blue:hover { text-decoration: underline; }
.field-error { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }
.btn-submit {
  width: 100%;
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 12px;
  border-radius: 4px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
  margin-top: 8px;
}
.btn-submit:hover:not(:disabled) { background: var(--primary-color); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
.auth-alt { text-align: center; margin-top: 16px; font-size: 14px; color: #555; }
</style>



