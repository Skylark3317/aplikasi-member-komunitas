<template>
  <AppLayout>
    <Head title="Daftar Membership - AMK" />

    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">Daftar Membership</h1>
      </div>
    </div>

    <div class="auth-wrapper">
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label for="name">Nama Lengkap <span class="required">*</span></label>
          <input id="name" v-model="form.name" type="text" placeholder="Nama lengkap" required />
          <span v-if="form.errors.name" class="field-error">{{ form.errors.name }}</span>
        </div>

        <div class="form-group">
          <label for="email">Email <span class="required">*</span></label>
          <input id="email" v-model="form.email" type="email" placeholder="example@mail.com" required />
          <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
        </div>

        <div class="form-group">
          <label for="phone">Nomor Telepon</label>
          <input id="phone" v-model="form.phone" type="tel" placeholder="Nomor telepon" />
        </div>

        <div class="form-group">
          <label for="institution">Institusi</label>
          <input id="institution" v-model="form.institution" type="text" placeholder="Institusi" />
        </div>

        <div class="form-group">
          <label for="department">Departemen</label>
          <input id="department" v-model="form.department" type="text" placeholder="Departemen" />
        </div>

        <div class="form-group">
          <label for="address">Alamat</label>
          <textarea id="address" v-model="form.address" placeholder="Alamat" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label for="password">Password <span class="required">*</span></label>
          <div class="input-icon-wrap">
            <input
              id="password"
              v-model="form.password"
              :type="showPw ? 'text' : 'password'"
              placeholder="Password"
              required
            />
            <button type="button" class="toggle-pw" @click="showPw = !showPw">{{ showPw ? '🙈' : '👁' }}</button>
          </div>
          <span v-if="form.errors.password" class="field-error">{{ form.errors.password }}</span>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
          <div class="input-icon-wrap">
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              :type="showPwC ? 'text' : 'password'"
              placeholder="Konfirmasi password"
              required
            />
            <button type="button" class="toggle-pw" @click="showPwC = !showPwC">{{ showPwC ? '🙈' : '👁' }}</button>
          </div>
        </div>

        <div class="form-hints">
          <p>ⓘ Password minimal 8 karakter dengan kombinasi huruf dan angka</p>
          <p>* Wajib diisi</p>
        </div>

        <button type="submit" class="btn-submit" :disabled="form.processing">Daftar</button>

        <p class="auth-alt">
          Sudah memiliki akun?
          <Link :href="route('login')" class="link-blue">Login</Link>
        </p>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const showPw = ref(false);
const showPwC = ref(false);

const form = useForm({
  name: '',
  email: '',
  phone: '',
  institution: '',
  department: '',
  address: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') });
};
</script>

<style scoped>
.page-header { background: var(--primary-color); padding: 110px 0 32px; }
.page-header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.page-title { color: #fff; font-size: 22px; font-weight: 700; }

.auth-wrapper {
  display: flex;
  justify-content: center;
  padding: 48px 24px 80px;
  background: #f9fafb;
}
.auth-form { width: 100%; max-width: 480px; }
.form-group { margin-bottom: 18px; }
.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  color: #111;
  margin-bottom: 6px;
}
.required { color: #dc2626; }
.form-group input, .form-group textarea {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  padding: 10px 14px;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s;
  font-family: inherit;
}
.form-group input:focus, .form-group textarea:focus { border-color: var(--primary-color); }
.input-icon-wrap { position: relative; }
.input-icon-wrap input { padding-right: 42px; }
.toggle-pw {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; font-size: 16px;
}
.field-error { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }
.form-hints { margin-bottom: 16px; }
.form-hints p { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
.btn-submit {
  width: 100%; background: var(--primary-color); color: #fff; border: none;
  padding: 12px; border-radius: 4px; font-size: 15px; font-weight: 600;
  cursor: pointer; transition: background 0.2s;
}
.btn-submit:hover:not(:disabled) { background: var(--primary-color); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
.auth-alt { text-align: center; margin-top: 16px; font-size: 14px; color: #555; }
.link-blue { color: var(--primary-color); text-decoration: none; }
.link-blue:hover { text-decoration: underline; }
</style>



