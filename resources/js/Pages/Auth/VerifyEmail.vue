<template>
  <AppLayout>
    <Head title="Verifikasi Email - AMK" />

    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">Daftar Membership</h1>
      </div>
    </div>

    <div class="auth-wrapper">
      <div class="verify-box">
        <p class="verify-desc">
          Terima kasih sudah mendaftar! Sebelum memulai, dapatkah Anda melakukan verifikasi email dengan klik tautan yang baru saja kami kirim ke email Anda? Jika Anda tidak menerima email, kami akan mengirimnya lagi.
        </p>

        <div v-if="status === 'verification-link-sent'" class="alert-success">
          Tautan verifikasi baru telah dikirim ke email Anda yang Anda tulis ketika registrasi.
        </div>

        <div class="verify-actions">
          <form @submit.prevent="resend">
            <button type="submit" class="btn-resend" :disabled="form.processing">
              Kirim kembali email verifikasi
            </button>
          </form>
          <Link method="post" :href="route('logout')" as="button" class="btn-logout">Log Out</Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ status: String });

const form = useForm({});
const resend = () => form.post(route('verification.send'));
</script>

<style scoped>
.page-header { background: var(--primary-color); padding: 110px 0 32px; }
.page-header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.page-title { color: #fff; font-size: 22px; font-weight: 700; }

.auth-wrapper {
  display: flex; justify-content: center;
  padding: 56px 24px 80px; background: #f9fafb; min-height: 300px;
}
.verify-box { max-width: 520px; width: 100%; }
.verify-desc { font-size: 14px; color: #444; line-height: 1.7; margin-bottom: 16px; }
.alert-success {
  background: #f0fdf4; border: 1px solid #bbf7d0;
  color: #15803d; font-size: 13px; padding: 12px 16px;
  border-radius: 4px; margin-bottom: 20px; line-height: 1.6;
}
.verify-actions { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
.btn-resend {
  background: var(--primary-color); color: #fff; border: none;
  padding: 10px 20px; border-radius: 4px; font-size: 14px;
  font-weight: 600; cursor: pointer; transition: background 0.2s;
}
.btn-resend:hover:not(:disabled) { background: var(--primary-color); }
.btn-resend:disabled { opacity: 0.7; cursor: not-allowed; }
.btn-logout {
  background: none; border: none; color: #dc2626;
  font-size: 14px; font-weight: 600; cursor: pointer;
  text-decoration: none; padding: 0;
}
.btn-logout:hover { text-decoration: underline; }
</style>



