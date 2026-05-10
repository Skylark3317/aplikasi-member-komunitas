<template>
  <AppLayout>
    <Head title="Lupa Password - AMK" />

    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">Lupa Password</h1>
      </div>
    </div>

    <div class="auth-wrapper">
      <div class="forgot-box">
        <p class="forgot-desc">
          Lupa password Anda? Jangan khawatir. Tuliskan saja email Anda dan kami akan mengirim tautan untuk mereset password Anda.
        </p>

        <div v-if="status" class="alert-success">{{ status }}</div>

        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="email">Email</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="example@mail.com"
              required
            />
            <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-send" :disabled="form.processing">
              Kirim kembali tautan reset password
            </button>
            <Link method="post" :href="route('logout')" as="button" class="btn-logout">Log Out</Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ status: String });

const form = useForm({ email: '' });
const submit = () => form.post(route('password.email'));
</script>

<style scoped>
.page-header { background: var(--primary-color); padding: 110px 0 32px; }
.page-header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.page-title { color: #fff; font-size: 22px; font-weight: 700; }

.auth-wrapper {
  display: flex; justify-content: center;
  padding: 56px 24px 80px; background: #f9fafb; min-height: 300px;
}
.forgot-box { max-width: 520px; width: 100%; }
.forgot-desc { font-size: 14px; color: #444; line-height: 1.7; margin-bottom: 16px; }
.alert-success {
  color: #15803d; font-size: 13px; margin-bottom: 16px;
}
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 14px; font-weight: 500; color: #111; margin-bottom: 6px; }
.form-group input {
  width: 100%; border: 1px solid #d1d5db; border-radius: 4px;
  padding: 10px 14px; font-size: 14px; outline: none; box-sizing: border-box;
}
.form-group input:focus { border-color: var(--primary-color); }
.field-error { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }
.form-actions { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
.btn-send {
  background: var(--primary-color); color: #fff; border: none;
  padding: 10px 20px; border-radius: 4px; font-size: 14px;
  font-weight: 600; cursor: pointer; transition: background 0.2s;
}
.btn-send:hover:not(:disabled) { background: var(--primary-color); }
.btn-send:disabled { opacity: 0.7; cursor: not-allowed; }
.btn-logout {
  background: none; border: none; color: #dc2626;
  font-size: 14px; font-weight: 600; cursor: pointer;
  text-decoration: none; padding: 0;
}
.btn-logout:hover { text-decoration: underline; }
</style>



