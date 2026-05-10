<template>
  <PetugasLayout>
    <Head title="Tulis Blog Baru - AMK" />

    <div class="top-bar">
      <div class="page-header">
        <Link :href="route('petugas.blog.index')" class="back-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </Link>
        <h1 class="page-title">Tulis Blog Baru</h1>
      </div>
      
      <div class="header-actions">
        <Link :href="route('petugas.blog.index')" class="btn-outline">Batal</Link>
        <button type="submit" form="blog-form" class="btn-primary" :disabled="form.processing">
          Buat blog
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
          <input 
            type="text" 
            v-model="form.category" 
            class="form-input"
            placeholder="Tulis kategori blog, pisahkan dengan tanda koma (,)"
          />
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
            placeholder="Tulis isi blog di sini..."
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

const form = useForm({
  title: '',
  category: '',
  excerpt: '',
  content: '',
});

function submit() {
  form.post(route('petugas.blog.store'));
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

.btn-primary {
  background: #007bff;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s;
}
.btn-primary:hover { opacity: 0.9; }

.btn-outline {
  background: #fff;
  color: #007bff;
  border: 1px solid #007bff;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.2s;
}
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
  border-color: #007bff;
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
