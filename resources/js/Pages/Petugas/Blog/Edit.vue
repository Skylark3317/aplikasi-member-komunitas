<template>
  <PetugasLayout>
    <Head title="Edit Blog - AMK" />

    <div class="top-bar">
      <div class="page-header">
        <Link :href="route('petugas.blog.index')" class="back-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </Link>
        <h1 class="page-title">Edit Blog</h1>
      </div>
      
      <div class="header-actions">
        <Link :href="route('petugas.blog.index')" class="btn-outline">Batal</Link>
        <button type="button" @click="deletePost" class="btn-danger">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
          </svg>
          Hapus blog
        </button>
        <button type="submit" form="blog-form" class="btn-primary" :disabled="form.processing">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
            <polyline points="17 21 17 13 7 13 7 21"></polyline>
            <polyline points="7 3 7 8 15 8"></polyline>
          </svg>
          Simpan perubahan
        </button>
      </div>
    </div>
    <div class="divider" />

    <div class="content-area">
      <form id="blog-form" @submit.prevent="submit" class="form-container">
        <!-- Judul -->
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input 
            type="text" 
            v-model="form.title" 
            class="form-input"
            placeholder="Judul"
            required
          />
          <div v-if="form.errors.title" class="error-text">{{ form.errors.title }}</div>
        </div>

        <!-- Kategori -->
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select 
            v-model="form.category" 
            class="form-input"
            required
          >
            <option value="" disabled>Pilih Kategori</option>
            <option value="Berita">Berita</option>
            <option value="Acara">Acara</option>
          </select>
          <div v-if="form.errors.category" class="error-text">{{ form.errors.category }}</div>
        </div>

        <!-- Ringkasan -->
        <div class="form-group">
          <label class="form-label">Ringkasan</label>
          <textarea 
            v-model="form.excerpt" 
            class="form-input" 
            rows="4" 
            placeholder="Ringkasan"
          ></textarea>
          <div v-if="form.errors.excerpt" class="error-text">{{ form.errors.excerpt }}</div>
        </div>

        <!-- Konten -->
        <div class="form-group">
          <label class="form-label">Konten</label>
          <RichTextEditor 
            v-model="form.content" 
            placeholder="Konten"
          />
          <div v-if="form.errors.content" class="error-text">{{ form.errors.content }}</div>
        </div>
      </form>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';
import RichTextEditor from '@/Components/RichTextEditor.vue';

const props = defineProps({
  post: Object,
});

const form = useForm({
  title: props.post.title,
  category: props.post.category ? props.post.category.name : '',
  excerpt: props.post.excerpt,
  content: props.post.content,
});

function submit() {
  form.patch(route('petugas.blog.update', props.post.id));
}

function deletePost() {
  if (confirm('Apakah Anda yakin ingin menghapus blog ini?')) {
    form.delete(route('petugas.blog.destroy', props.post.id));
  }
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

.btn-primary, .btn-danger, .btn-outline {
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

.btn-primary { background: var(--primary-color, #2563eb); color: #fff; border-color: var(--primary-color, #2563eb); }
.btn-danger { background: #ef4444; color: #fff; border-color: #ef4444; }
.btn-outline { background: #fff; color: var(--primary-color, #007bff); border-color: var(--primary-color, #007bff); }

.btn-primary:hover, .btn-danger:hover { filter: brightness(0.9); }
.btn-outline:hover { background: #f0f4ff; }

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
  max-width: 800px;
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
  padding: 12px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s;
  background: #fff;
}
.form-input:focus {
  border-color: var(--primary-color);
}

select.form-input {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 14px;
  padding-right: 32px;
  width: fit-content;
}

.error-text {
  font-size: 13px;
  color: #ef4444;
}

/* Editor Mockup Styles */
.editor-wrapper {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.editor-toolbar {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 8px 12px;
  background: #fff;
  border-bottom: 1px solid #d1d5db;
}

.tool-btn {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  color: #4b5563;
  transition: background 0.2s;
}
.tool-btn:hover { background: #f3f4f6; }

.tool-sep {
  width: 1px;
  height: 16px;
  background: #d1d5db;
  margin: 0 4px;
}

.editor-textarea {
  padding: 16px;
  border: none;
  font-size: 14.5px;
  min-height: 400px;
  outline: none;
  resize: vertical;
  line-height: 1.6;
  font-family: inherit;
  color: #111;
}
</style>
