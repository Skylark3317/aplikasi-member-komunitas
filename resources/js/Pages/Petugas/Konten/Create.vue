<template>
  <PetugasLayout>
    <Head title="Tambah Konten - AMK" />

    <div class="top-bar">
      <div class="page-header">
        <Link :href="route('petugas.konten.index')" class="back-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </Link>
        <h1 class="page-title">Tambah Konten Baru</h1>
      </div>
      
      <div class="header-actions">
        <Link :href="route('petugas.konten.index')" class="btn-outline">Batal</Link>
        <button type="button" @click="submit" class="btn-primary" :disabled="form.processing">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
          Simpan konten
        </button>
      </div>
    </div>
    <div class="divider" />

    <div class="content-area">
      <form @submit.prevent="submit" class="form-container">
        <!-- Judul -->
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input 
            type="text" 
            v-model="form.title" 
            class="form-input"
            placeholder="Masukkan judul konten"
            required
          />
          <div v-if="form.errors.title" class="error-text">{{ form.errors.title }}</div>
        </div>

        <!-- Tipe -->
        <div class="form-group">
          <label class="form-label">Tipe</label>
          <div class="radio-group">
            <label class="radio-label">
              <input type="radio" v-model="form.type" value="video" />
              <span>Video</span>
            </label>
            <label class="radio-label">
              <input type="radio" v-model="form.type" value="ebook" />
              <span>Ebook</span>
            </label>
          </div>
          <div v-if="form.errors.type" class="error-text">{{ form.errors.type }}</div>
        </div>

        <!-- Thumbnail -->
        <div class="form-group">
          <label class="form-label">Thumbnail</label>
          
          <div :class="['thumbnail-preview-box', form.type === 'video' ? 'preview-video' : 'preview-ebook']">
            <img v-if="thumbnailPreview" :src="thumbnailPreview" alt="Thumbnail Preview" />
            <div v-else class="thumbnail-placeholder"></div>
          </div>
          
          <ul class="thumbnail-hints">
            <li>Format JPG atau PNG, ukuran maksimal 1MB</li>
            <li>Rasio thumbnail video disarankan 16:9</li>
            <li>Rasio thumbnail ebook disarankan 10:16</li>
          </ul>
          
          <div class="thumbnail-actions">
            <label class="btn-upload">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
              Unggah thumbnail
              <input 
                type="file" 
                class="hidden-input" 
                accept="image/jpeg,image/png"
                @change="handleThumbnailUpload"
              />
            </label>
            
            <button type="button" @click="removeThumbnail" class="btn-delete" v-if="thumbnailPreview || form.thumbnail">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              Hapus thumbnail
            </button>
          </div>
          <div v-if="form.errors.thumbnail" class="error-text">{{ form.errors.thumbnail }}</div>
        </div>

        <!-- Link Google Drive -->
        <div class="form-group">
          <label class="form-label">Link Google Drive</label>
          <input 
            type="text" 
            v-model="form.file_url" 
            class="form-input"
            placeholder="Masukkan link konten (misal: Google Drive)"
            required
          />
          <div v-if="form.errors.file_url" class="error-text">{{ form.errors.file_url }}</div>
        </div>
      </form>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const form = useForm({
  title: '',
  type: 'video',
  file_url: '',
  thumbnail: null,
});

const thumbnailPreview = ref(null);

function handleThumbnailUpload(e) {
  const file = e.target.files[0];
  if (!file) return;
  
  form.thumbnail = file;
  
  const reader = new FileReader();
  reader.onload = (e) => {
    thumbnailPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
}

function removeThumbnail() {
  form.thumbnail = null;
  thumbnailPreview.value = null;
}

function submit() {
  form.post(route('petugas.konten.store'), {
    forceFormData: true,
  });
}
</script>

<style scoped>
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}

.page-header {
  display: flex;
  align-items: center;
  gap: 12px;
}

.back-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #111;
  text-decoration: none;
  width: 32px;
  height: 32px;
  border-radius: 4px;
  transition: background 0.2s;
}
.back-btn:hover { background: #f3f4f6; }

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
  margin: 0;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-primary, .btn-outline {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  line-height: 1;
  height: 38px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: filter 0.2s;
  border: 1px solid transparent;
  box-sizing: border-box;
}

.btn-primary { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
.btn-outline { background: #fff; color: var(--primary-color); border-color: var(--primary-color); }

.btn-primary:hover { filter: brightness(0.9); }
.btn-outline:hover { background: #eff6ff; }

.divider { height: 1px; background: #e5e7eb; margin: 0; }

.content-area {
  padding: 32px;
  background: #fff;
  min-height: calc(100vh - 65px);
  display: flex;
  justify-content: center;
}

.form-container {
  width: 100%;
  max-width: 600px;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-size: 14px;
  font-weight: 600;
  color: #374151;
}

.form-input {
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s;
}
.form-input:focus {
  border-color: var(--primary-color);
}

.radio-group {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 4px;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: #374151;
  cursor: pointer;
}
.radio-label input[type="radio"] {
  width: 16px;
  height: 16px;
  accent-color: var(--primary-color);
}

.thumbnail-preview-box {
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  overflow: hidden;
  background: #f9fafb;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}
.preview-video {
  width: 256px;
  height: 144px; /* 16:9 */
}
.preview-ebook {
  width: 125px;
  height: 200px; /* 10:16 */
}
.thumbnail-preview-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.thumbnail-placeholder {
  width: 100%;
  height: 100%;
  background: #f3f4f6;
  position: relative;
}
.thumbnail-placeholder::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: #ff007f;
}

.thumbnail-hints {
  margin: 0;
  padding: 0 0 0 16px;
  color: #9ca3af;
  font-size: 12px;
  line-height: 1.6;
}

.thumbnail-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 8px;
}

.btn-upload, .btn-delete {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  line-height: 1;
  height: 38px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  border: 1px solid transparent;
  box-sizing: border-box;
}
.btn-upload { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
.btn-delete { background: #fff; color: #ef4444; border-color: #fca5a5; }

.btn-upload:hover { filter: brightness(0.9); }
.btn-delete:hover { background: #fef2f2; border-color: #ef4444; }

.hidden-input { display: none; }
.error-text { font-size: 13px; color: #ef4444; }

/* ── Responsive ── */
@media (max-width: 1024px) {
  .top-bar { padding: 12px 16px; gap: 8px; flex-wrap: wrap; }
  .page-title { font-size: 16px; }
  .btn-outline, .btn-primary { font-size: 12px; padding: 7px 12px; height: auto; }
  .content-area { padding: 16px; }
  .form-container { padding: 0; }
}
</style>
