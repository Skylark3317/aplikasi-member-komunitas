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

        <!-- Nomor Telepon, Institusi, Jurusan, Alamat Rumah Section -->
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
              autocomplete="off"
              :class="{ 'has-error': form.errors.institution }"
            />
            <span v-if="form.errors.institution" class="error-msg">{{ form.errors.institution }}</span>
          </div>

          <!-- Jurusan -->
          <div class="form-group">
            <label for="department">Jurusan</label>
            <input 
              id="department" 
              type="text" 
              v-model="form.department" 
              placeholder="Masukkan nama jurusan..."
              autocomplete="off"
              :class="{ 'has-error': form.errors.department }"
            />
            <span v-if="form.errors.department" class="error-msg">{{ form.errors.department }}</span>
          </div>

          <!-- Jenis Kelamin -->
          <div class="form-group">
            <label>Jenis Kelamin</label>
            <div class="radio-group">
              <label class="radio-label">
                <input type="radio" v-model="form.gender" value="Laki-laki" />
                Laki-laki
              </label>
              <label class="radio-label">
                <input type="radio" v-model="form.gender" value="Perempuan" />
                Perempuan
              </label>
            </div>
            <span v-if="form.errors.gender" class="error-msg">{{ form.errors.gender }}</span>
          </div>

          <!-- Golongan Darah -->
          <div class="form-group">
            <label>Golongan Darah</label>
            <div class="radio-group">
              <label class="radio-label">
                <input type="radio" v-model="form.blood_type" value="A" /> A
              </label>
              <label class="radio-label">
                <input type="radio" v-model="form.blood_type" value="B" /> B
              </label>
              <label class="radio-label">
                <input type="radio" v-model="form.blood_type" value="AB" /> AB
              </label>
              <label class="radio-label">
                <input type="radio" v-model="form.blood_type" value="O" /> O
              </label>
            </div>
            <span v-if="form.errors.blood_type" class="error-msg">{{ form.errors.blood_type }}</span>
          </div>

          <!-- Pendidikan Terakhir -->
          <div class="form-group">
            <label for="last_education">Pendidikan Terakhir</label>
            <input 
              id="last_education" 
              type="text" 
              v-model="form.last_education" 
              placeholder="Masukkan pendidikan terakhir..."
              autocomplete="off"
              :class="{ 'has-error': form.errors.last_education }"
            />
            <span v-if="form.errors.last_education" class="error-msg">{{ form.errors.last_education }}</span>
          </div>

          <!-- Alamat -->
          <div class="form-group">
            <label for="address">Alamat Rumah</label>
            <textarea 
              id="address" 
              v-model="form.address" 
              rows="4"
              placeholder="Masukkan alamat rumah lengkap tempat tinggal..."
              :class="{ 'has-error': form.errors.address }"
            ></textarea>
            <span v-if="form.errors.address" class="error-msg">{{ form.errors.address }}</span>
          </div>

          <!-- Kepakaran -->
          <div class="form-group">
            <label>Kepakaran (Maksimal 3)</label>
            <div v-for="(exp, index) in form.expertise" :key="index" style="display:flex; gap:8px; margin-bottom:8px; flex-wrap: wrap; position: relative;">
              <div style="flex: 1; min-width: 0; position: relative;">
                <input 
                  type="text" 
                  :value="form.expertise[index]"
                  @input="e => handleExpertiseSearch(index, e.target.value)"
                  @focus="activeDropdown = index"
                  @blur="hideDropdown"
                  placeholder="Cari atau pilih keahlian..."
                  autocomplete="off"
                  :class="{ 'has-error': form.errors[`expertise.${index}`] }"
                  style="width: 100%;"
                />
                <!-- Dropdown -->
                <div v-if="activeDropdown === index" class="custom-dropdown">
                  <div 
                    v-for="option in getFilteredExpertises(index)" 
                    :key="option" 
                    @mousedown.prevent="selectExpertise(index, option)"
                    class="dropdown-item"
                  >
                    {{ option }}
                  </div>
                  <div v-if="getFilteredExpertises(index).length === 0" class="dropdown-item empty">
                    Opsi tidak ditemukan
                  </div>
                </div>
              </div>
              <button type="button" @click="removeExpertise(index)" class="btn-remove-exp">✕</button>
              <div v-if="form.errors[`expertise.${index}`]" class="error-msg" style="width: 100%;">{{ form.errors[`expertise.${index}`] }}</div>
            </div>
            <button type="button" v-if="form.expertise.length < 3" @click="addExpertise" class="btn-add-exp">+ Tambah Kepakaran</button>
            <span v-if="form.errors.expertise" class="error-msg">{{ form.errors.expertise }}</span>
          </div>

          <!-- Bukti Kepakaran -->
          <div class="form-group">
            <label>Bukti Kepakaran (Maksimal 10 File)</label>
            <input 
              type="file" 
              multiple
              @change="handleExpertiseProofs"
              accept=".pdf,image/png,image/jpeg,image/jpg"
            />
            <span v-if="form.errors.expertise_proofs" class="error-msg">{{ form.errors.expertise_proofs }}</span>
            <p class="field-hint" style="font-size:11.5px; color:#9ca3af; margin:4px 0 8px;">Format PDF, JPG, PNG maksimal 2MB per file.</p>
            
            <div class="proof-preview-container" v-if="allProofs.length > 0">
              <div v-for="(proof, index) in allProofs" :key="index" class="proof-preview-box">
                <img v-if="proof.isImage" :src="proof.url" class="proof-thumb" @click.prevent="viewProofLarge(proof.url)"/>
                <div v-else class="proof-doc" @click.prevent="viewProofLarge(proof.url)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                  </svg>
                  <span>PDF</span>
                </div>
                <button type="button" class="btn-remove-proof" @click.prevent="removeProof(index)">×</button>
              </div>
            </div>
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
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  user: Object,
  expertises: Array,
});

const fileInput = ref(null);
const avatarPreviewUrl = ref(null);

const showOldPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const activeDropdown = ref(null);

function handleExpertiseSearch(index, val) {
  form.expertise[index] = val;
  activeDropdown.value = index;
}

function getFilteredExpertises(index) {
  const query = form.expertise[index]?.toLowerCase() || '';
  return props.expertises.filter(exp => exp.toLowerCase().includes(query));
}

function selectExpertise(index, option) {
  form.expertise[index] = option;
  activeDropdown.value = null;
}

function hideDropdown() {
  setTimeout(() => {
    activeDropdown.value = null;
  }, 150);
}

const form = useForm({
  _method: 'PATCH',
  name: props.user.name,
  telephone: !props.user.telephone || props.user.telephone === '-' ? '' : props.user.telephone,
  gender: !props.user.member_profile?.gender || props.user.member_profile.gender === '-' ? '' : props.user.member_profile.gender,
  blood_type: !props.user.member_profile?.blood_type || props.user.member_profile.blood_type === '-' ? '' : props.user.member_profile.blood_type,
  last_education: !props.user.member_profile?.last_education || props.user.member_profile.last_education === '-' ? '' : props.user.member_profile.last_education,
  institution: !props.user.member_profile?.institution || props.user.member_profile.institution === '-' ? '' : props.user.member_profile.institution,
  department: !props.user.member_profile?.department || props.user.member_profile.department === '-' ? '' : props.user.member_profile.department,
  address: !props.user.member_profile?.address || props.user.member_profile.address === '-' ? '' : props.user.member_profile.address,
  expertise: Array.isArray(props.user.member_profile?.expertise) ? props.user.member_profile.expertise : [],
  expertise_proofs: [],
  existing_proofs: Array.isArray(props.user.member_profile?.expertise_proof) ? props.user.member_profile.expertise_proof : [],
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

// Expertise handling
function addExpertise() {
  if (form.expertise.length < 3) {
    form.expertise.push('');
  }
}

function removeExpertise(index) {
  form.expertise.splice(index, 1);
}

// Expertise proofs handling
const newProofPreviews = ref([]);

const allProofs = computed(() => {
  const existing = form.existing_proofs.map(url => ({
    url,
    isImage: !!url.match(/\.(jpeg|jpg|gif|png|webp)$/i),
    isNew: false
  }));
  const newOnes = newProofPreviews.value.map(p => ({
    url: p.url,
    isImage: !!p.file.name.match(/\.(jpeg|jpg|gif|png|webp)$/i),
    isNew: true,
    file: p.file
  }));
  return [...existing, ...newOnes];
});

function handleExpertiseProofs(event) {
  const files = Array.from(event.target.files);
  const totalLimit = 10;
  
  for (const file of files) {
    if (file.size > 2 * 1024 * 1024) {
      alert(`Ukuran file ${file.name} maksimal adalah 2MB.`);
      continue;
    }
    if (form.existing_proofs.length + form.expertise_proofs.length < totalLimit) {
      form.expertise_proofs.push(file);
      newProofPreviews.value.push({
        file,
        url: URL.createObjectURL(file)
      });
    } else {
      alert('Maksimal 10 file bukti kepakaran.');
      break;
    }
  }
  // Reset input so same file can be selected again
  event.target.value = '';
}

function removeProof(index) {
  const proof = allProofs.value[index];
  if (proof.isNew) {
    const idx = form.expertise_proofs.findIndex(f => f === proof.file);
    if (idx !== -1) {
      form.expertise_proofs.splice(idx, 1);
      newProofPreviews.value.splice(idx, 1);
    }
  } else {
    const idx = form.existing_proofs.findIndex(u => u === proof.url);
    if (idx !== -1) {
      form.existing_proofs.splice(idx, 1);
    }
  }
}

function viewProofLarge(url) {
  window.open(url, '_blank');
}

function submit() {
  form.transform((data) => {
    return {
      ...data,
      expertise: [...data.expertise],
      expertise_proofs: [...data.expertise_proofs],
      existing_proofs: [...data.existing_proofs],
    };
  }).post(route('member.profil.update'), {
    forceFormData: true,
    preserveScroll: true,
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
  border: 1px solid #d1d5db;
  color: #374151;
  padding: 8px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.15s ease;
}

.btn-cancel-top:hover { background: #f3f4f6; color: #374151; border-color: #d1d5db; }

.btn-save-top {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 8px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.2s;
}

.btn-save-top:hover { filter: brightness(0.9); }
.btn-save-top svg { width: 16px; height: 16px; }


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
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
  margin-bottom: 24px;
  min-width: 0;
}

.section-title {
  font-size: 17px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 24px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f3f4f6;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
  min-width: 0;
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
  background: var(--primary-color);;
  color: #fff;
  border: none;
}

.btn-upload:hover{ filter: brightness(0.9); }
.btn-upload svg { width: 16px; height: 16px; }

.btn-delete-avatar {
  background: #fff;
  border: 1px solid var(--primary-color);
  color: var(--primary-color);
}

.btn-delete-avatar:hover { filter: brightness(0.95); }

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

.radio-group {
  display: flex;
  gap: 16px;
  margin-top: 4px;
}
.radio-label {
  display: flex !important;
  align-items: center;
  gap: 6px;
  font-weight: 500 !important;
  font-size: 13.5px;
  margin-bottom: 0 !important;
  cursor: pointer;
}
.radio-label input[type="radio"] {
  width: auto;
  margin: 0;
  cursor: pointer;
}

.error-msg {
  font-size: 12px;
  color: #ef4444;
  font-weight: 600;
}

/* Kepakaran specific */
.btn-remove-exp {
  background: none;
  border: none;
  color: #ef4444;
  font-size: 16px;
  cursor: pointer;
  padding: 0 8px;
  transition: opacity 0.2s;
}
.btn-remove-exp:hover {
  opacity: 0.7;
}

.btn-add-exp {
  background: none;
  border: 1px dashed #d1d5db;
  color: #4b5563;
  padding: 8px 12px;
  font-size: 13px;
  font-weight: 600;
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s;
}
.btn-add-exp:hover {
  background: #f9fafb;
  border-color: #9ca3af;
  color: #111827;
}

.custom-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 1px solid #d1d5db;
  z-index: 10;
  max-height: 200px;
  overflow-y: auto;
  border-radius: 6px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  margin-top: 4px;
}
.dropdown-item {
  padding: 8px 12px;
  cursor: pointer;
  font-size: 13.5px;
  color: #111827;
}
.dropdown-item:hover {
  background: #f3f4f6;
}
.dropdown-item.empty {
  color: #6b7280;
  cursor: default;
}
.dropdown-item.empty:hover {
  background: white;
}

.proof-preview-container {
  display: flex;
  overflow-x: auto;
  gap: 12px;
  margin-top: 12px;
  padding-bottom: 8px;
}

.proof-preview-box {
  position: relative;
  width: 80px;
  height: 80px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  background: #f9fafb;
  overflow: visible;
  flex-shrink: 0;
}

.proof-thumb {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
  cursor: pointer;
  transition: opacity 0.2s;
}
.proof-thumb:hover {
  opacity: 0.8;
}

.proof-doc {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #ef4444;
  cursor: pointer;
  transition: background 0.2s;
  border-radius: 8px;
}
.proof-doc:hover {
  background: #fef2f2;
}
.proof-doc svg {
  width: 24px;
  height: 24px;
  margin-bottom: 4px;
}
.proof-doc span {
  font-size: 11px;
  font-weight: 700;
}

.btn-remove-proof {
  position: absolute;
  top: -6px;
  right: -6px;
  width: 20px;
  height: 20px;
  background: #ef4444;
  color: #fff;
  border: none;
  border-radius: 50%;
  font-size: 12px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 0;
}
.btn-remove-proof:hover {
  background: #dc2626;
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