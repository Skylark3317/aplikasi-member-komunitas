<template>
  <MemberLayout>
    <Head title="Edit Profil - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <div class="top-left">
        <Link :href="route('member.profil.show')" class="back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </Link>
        <h1 class="page-title">Edit Profil</h1>
      </div>

      <div class="top-right-actions">
        <Link :href="route('member.profil.show')" class="btn-cancel-top">Batal</Link>
        <button @click="submit" type="button" class="btn-save-top" :disabled="form.processing">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="save-icon-sm">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
          </svg>
          <span>Simpan perubahan</span>
        </button>
      </div>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <form class="edit-form" @submit.prevent="submit" enctype="multipart/form-data">
        
        <!-- Foto Profil Section -->
        <div class="form-section">
          <h3 class="section-title">Foto Profil</h3>
          
          <div class="avatar-upload-block">
            <div class="avatar-preview-circle">
              <!-- If we have a newly selected local photo -->
              <img v-if="avatarPreviewUrl" :src="avatarPreviewUrl" alt="Avatar Preview" class="edit-avatar-img" />
              <!-- If we have an existing stored avatar -->
              <img v-else-if="user.avatar_url && !form.delete_avatar" :src="user.avatar_url" alt="Current Avatar" class="edit-avatar-img" />
              <!-- Fallback to Name Initials -->
              <div v-else class="edit-avatar-initial">
                {{ user.name.charAt(0).toUpperCase() }}
              </div>
            </div>

            <div class="avatar-controls">
              <span class="avatar-help-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="help-icon">
                  <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                Format JPG atau PNG, ukuran maksimal 1MB
              </span>
              
              <div class="avatar-btn-row">
                <!-- Trigger hidden input -->
                <button type="button" @click="triggerFileInput" class="btn-avatar-action btn-upload">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="action-icon">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                  </svg>
                  <span>Unggah foto profil</span>
                </button>

                <!-- Hidden file input -->
                <input 
                  type="file" 
                  ref="fileInput" 
                  @change="handleFileChange" 
                  accept="image/png, image/jpeg, image/jpg" 
                  class="hidden-file-input" 
                />

                <!-- Delete Avatar -->
                <button 
                  type="button" 
                  @click="deleteAvatarPhoto" 
                  :disabled="!user.avatar_url && !avatarPreviewUrl"
                  class="btn-avatar-action btn-delete-avatar"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="action-icon">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                  </svg>
                  <span>Hapus foto profil</span>
                </button>
              </div>
              <span v-if="form.errors.avatar" class="error-msg">{{ form.errors.avatar }}</span>
            </div>
          </div>
        </div>

        <!-- Nomor Telepon, Institusi, Departemen, Alamat Section -->
        <div class="form-section">
          <!-- Nomor Telepon -->
          <div class="form-group">
            <label for="telephone">Nomor Telepon</label>
            <input 
              id="telephone" 
              type="text" 
              v-model="form.telephone" 
              placeholder="Masukkan nomor telepon aktif..."
              :class="{ 'has-error': form.errors.telephone }"
            />
            <span v-if="form.errors.telephone" class="error-msg">{{ form.errors.telephone }}</span>
          </div>

          <!-- Institusi -->
          <div class="form-group">
            <label for="institution">Institusi</label>
            <input 
              id="institution" 
              type="text" 
              v-model="form.institution" 
              placeholder="Masukkan nama institusi Anda..."
              :class="{ 'has-error': form.errors.institution }"
            />
            <span v-if="form.errors.institution" class="error-msg">{{ form.errors.institution }}</span>
          </div>

          <!-- Departemen -->
          <div class="form-group">
            <label for="department">Departemen</label>
            <input 
              id="department" 
              type="text" 
              v-model="form.department" 
              placeholder="Masukkan nama departemen / divisi..."
              :class="{ 'has-error': form.errors.department }"
            />
            <span v-if="form.errors.department" class="error-msg">{{ form.errors.department }}</span>
          </div>

          <!-- Alamat -->
          <div class="form-group">
            <label for="address">Alamat</label>
            <textarea 
              id="address" 
              v-model="form.address" 
              rows="4"
              placeholder="Masukkan alamat lengkap tempat tinggal..."
              :class="{ 'has-error': form.errors.address }"
            ></textarea>
            <span v-if="form.errors.address" class="error-msg">{{ form.errors.address }}</span>
          </div>
        </div>

        <!-- Ubah Password Section -->
        <div class="form-section">
          <h3 class="section-title">Ubah Password</h3>

          <!-- Password Lama -->
          <div class="form-group">
            <label for="old_password">Password Lama</label>
            <div class="password-input-wrapper">
              <input 
                id="old_password" 
                :type="showOldPassword ? 'text' : 'password'" 
                v-model="form.old_password" 
                placeholder="Masukkan password lama..."
                :class="{ 'has-error': form.errors.old_password }"
              />
              <button type="button" class="eye-toggle" @click="showOldPassword = !showOldPassword">
                <svg v-if="showOldPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-icon">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-icon">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <span v-if="form.errors.old_password" class="error-msg">{{ form.errors.old_password }}</span>
          </div>

          <!-- Password Baru -->
          <div class="form-group">
            <label for="password">Password Baru</label>
            <div class="password-input-wrapper">
              <input 
                id="password" 
                :type="showNewPassword ? 'text' : 'password'" 
                v-model="form.password" 
                placeholder="Masukkan password baru..."
                :class="{ 'has-error': form.errors.password }"
              />
              <button type="button" class="eye-toggle" @click="showNewPassword = !showNewPassword">
                <svg v-if="showNewPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-icon">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-icon">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
          </div>

          <!-- Konfirmasi Password Baru -->
          <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <div class="password-input-wrapper">
              <input 
                id="password_confirmation" 
                :type="showConfirmPassword ? 'text' : 'password'" 
                v-model="form.password_confirmation" 
                placeholder="Ulangi password baru..."
                :class="{ 'has-error': form.errors.password_confirmation }"
              />
              <button type="button" class="eye-toggle" @click="showConfirmPassword = !showConfirmPassword">
                <svg v-if="showConfirmPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-icon">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-icon">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <span v-if="form.errors.password_confirmation" class="error-msg">{{ form.errors.password_confirmation }}</span>
          </div>

          <span class="password-help-text">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="help-icon">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            Password minimal 8 karakter dengan kombinasi huruf dan angka.
          </span>
        </div>
      </form>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  user: Object,
});

const fileInput = ref(null);
const avatarPreviewUrl = ref(null);

const showOldPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
  _method: 'PATCH',
  name: props.user.name,
  telephone: !props.user.telephone || props.user.telephone === '-' ? '' : props.user.telephone,
  institution: !props.user.member_profile?.institution || props.user.member_profile.institution === '-' ? '' : props.user.member_profile.institution,
  department: !props.user.member_profile?.department || props.user.member_profile.department === '-' ? '' : props.user.member_profile.department,
  address: !props.user.member_profile?.address || props.user.member_profile.address === '-' ? '' : props.user.member_profile.address,
  avatar: null,
  delete_avatar: false,
  old_password: '',
  password: '',
  password_confirmation: '',
});

function triggerFileInput() {
  fileInput.value.click();
}

function handleFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    // Check file size (1MB max)
    if (file.size > 1024 * 1024) {
      alert('Ukuran file maksimal adalah 1MB.');
      return;
    }
    form.avatar = file;
    form.delete_avatar = false;
    avatarPreviewUrl.value = URL.createObjectURL(file);
  }
}

function deleteAvatarPhoto() {
  form.avatar = null;
  form.delete_avatar = true;
  avatarPreviewUrl.value = null;
  if (fileInput.value) {
    fileInput.value.value = '';
  }
}

function submit() {
  form.post(route('member.profil.update'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset('old_password', 'password', 'password_confirmation');
      avatarPreviewUrl.value = null;
    }
  });
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}

.top-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.back-link {
  color: #111;
  display: flex;
  align-items: center;
  padding: 4px;
}

.back-link svg {
  width: 20px;
  height: 20px;
}

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}

.top-right-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-cancel-top {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  border: 1px solid #007bff;
  color: #007bff;
  padding: 8px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.15s ease;
}

.btn-cancel-top:hover {
  background: #eff6ff;
}

.btn-save-top {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 8px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s ease;
}

.btn-save-top:hover {
  background: #0056b3;
}

.save-icon-sm {
  width: 14px;
  height: 14px;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content Area */
.content-area {
  padding: 32px 40px;
  background: #fff;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
}

.edit-form {
  max-width: 600px;
  display: flex;
  flex-direction: column;
  gap: 36px;
}

.form-section {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.section-title {
  font-size: 15px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

/* Foto Profil Block */
.avatar-upload-block {
  display: flex;
  align-items: center;
  gap: 24px;
}

.avatar-preview-circle {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  overflow: hidden;
  background: #e5e7eb;
  border: 1px solid #d1d5db;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.edit-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.edit-avatar-initial {
  font-size: 40px;
  font-weight: 800;
  color: #4b5563;
}

.avatar-controls {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.avatar-help-text {
  font-size: 11.5px;
  color: #9ca3af;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.help-icon {
  width: 14px;
  height: 14px;
  color: #9ca3af;
}

.avatar-btn-row {
  display: flex;
  gap: 12px;
}

.btn-avatar-action {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  box-sizing: border-box;
}

.btn-upload {
  background: #007bff;
  color: #fff;
  border: none;
}

.btn-upload:hover {
  background: #0056b3;
}

.btn-delete-avatar {
  background: #fff;
  border: 1px solid #007bff;
  color: #007bff;
}

.btn-delete-avatar:hover:not(:disabled) {
  background: #eff6ff;
}

.btn-delete-avatar:disabled {
  border-color: #d1d5db;
  color: #9ca3af;
  cursor: not-allowed;
}

.hidden-file-input {
  display: none;
}

.action-icon {
  width: 14px;
  height: 14px;
}

/* Inputs Form Elements */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-size: 13.5px;
  font-weight: 600;
  color: #374151;
}

.form-group input, .form-group textarea {
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 13.5px;
  font-family: inherit;
  width: 100%;
  box-sizing: border-box;
  outline: none;
  background: #fff;
  color: #111827;
  transition: border-color 0.15s ease;
}

.form-group input:focus, .form-group textarea:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 1px rgba(0, 123, 255, 0.2);
}

.form-group input.has-error, .form-group textarea.has-error {
  border-color: #ef4444;
}

.error-msg {
  font-size: 12px;
  color: #ef4444;
  font-weight: 600;
}

/* Password Wrappers */
.password-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-input-wrapper input {
  padding-right: 44px;
}

.eye-toggle {
  position: absolute;
  right: 12px;
  background: transparent;
  border: none;
  padding: 4px;
  cursor: pointer;
  color: #9ca3af;
  display: flex;
  align-items: center;
}

.eye-icon {
  width: 18px;
  height: 18px;
}

.password-help-text {
  font-size: 11.5px;
  color: #9ca3af;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 4px;
}
</style>
