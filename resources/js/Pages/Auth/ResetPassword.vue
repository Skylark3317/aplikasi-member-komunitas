<template>
  <AppLayout>
    <Head title="Reset Password - AMK" />

    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">Reset Password</h1>
      </div>
    </div>

    <div class="auth-wrapper">
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label for="password">Password Baru</label>
          <div class="input-icon-wrap">
            <input
              id="password"
              v-model="form.password"
              :type="showPw ? 'text' : 'password'"
              placeholder="Password baru"
              required
            />
            <button type="button" class="toggle-pw" @click="showPw = !showPw">{{ showPw ? '🙈' : '👁' }}</button>
          </div>
          <span v-if="form.errors.password" class="field-error">{{ form.errors.password }}</span>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password Baru</label>
          <div class="input-icon-wrap">
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              :type="showPwC ? 'text' : 'password'"
              placeholder="Konfirmasi password baru"
              required
            />
            <button type="button" class="toggle-pw" @click="showPwC = !showPwC">{{ showPwC ? '🙈' : '👁' }}</button>
          </div>
        </div>

        <button type="submit" class="btn-submit" :disabled="form.processing">Reset password</button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ token: String, email: String });
const showPw = ref(false);
const showPwC = ref(false);

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), { onFinish: () => form.reset('password', 'password_confirmation') });
};
</script>

<style scoped>
.page-header { background: var(--primary-color); padding: 20px 0; }
.page-header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.page-title { color: #fff; font-size: 22px; font-weight: 700; }

.auth-wrapper {
  display: flex; justify-content: center;
  padding: 56px 24px 80px; background: #f9fafb; min-height: 300px;
}
.auth-form { width: 100%; max-width: 440px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 14px; font-weight: 500; color: #111; margin-bottom: 6px; }
.form-group input {
  width: 100%; border: 1px solid #d1d5db; border-radius: 4px;
  padding: 10px 14px; font-size: 14px; outline: none;
  box-sizing: border-box; transition: border-color 0.2s;
}
.form-group input:focus { border-color: var(--primary-color); }
.input-icon-wrap { position: relative; }
.input-icon-wrap input { padding-right: 42px; }
.toggle-pw {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; font-size: 16px;
}
.field-error { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }
.btn-submit {
  width: 100%; background: var(--primary-color); color: #fff; border: none;
  padding: 12px; border-radius: 4px; font-size: 15px; font-weight: 600;
  cursor: pointer; transition: background 0.2s;
}
.btn-submit:hover:not(:disabled) { background: var(--primary-color); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
</style>



