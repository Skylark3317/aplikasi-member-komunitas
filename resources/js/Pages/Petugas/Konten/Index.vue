<template>
  <PetugasLayout>
    <Head title="Kelola Konten - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Konten</h1>
      <Link :href="route('petugas.konten.create')" class="btn-primary">
        Tambah konten baru
      </Link>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Tabs -->
      <div class="tabs-container">
        <button 
          :class="['tab-btn', activeTab === 'video' ? 'active' : '']"
          @click="activeTab = 'video'"
        >
          Video
        </button>
        <button 
          :class="['tab-btn', activeTab === 'ebook' ? 'active' : '']"
          @click="activeTab = 'ebook'"
        >
          Ebook
        </button>
      </div>

      <!-- Grid -->
      <div v-if="paginatedContents.length === 0" class="empty-state">
        Belum ada konten untuk tipe ini.
      </div>
      <div v-else :class="['content-grid', activeTab === 'video' ? 'video-grid' : 'ebook-grid']">
        <div v-for="item in paginatedContents" :key="item.id" class="content-card">
          <!-- Thumbnail -->
          <div :class="['card-thumbnail', activeTab === 'video' ? 'video-thumb' : 'ebook-thumb']">
            <img v-if="item.thumbnail_url" :src="`/storage/${item.thumbnail_url}`" alt="Thumbnail" />
            <div v-else class="placeholder-thumb"></div>
          </div>
          
          <!-- Info -->
          <div class="card-info">
            <h3 class="card-title">{{ item.title }}</h3>
            <div class="card-meta">
              {{ formatDate(item.created_at) }}
            </div>
            
            <Link :href="route('petugas.konten.edit', item.id)" class="card-action-overlay">
              Edit Konten
            </Link>
          </div>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="pagination" v-if="totalPages > 1">
        <button 
          class="page-btn text-muted" 
          :disabled="currentPage === 1"
          @click="currentPage--"
        >
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
          Sebelumnya
        </button>
        
        <button 
          v-for="page in totalPages" 
          :key="page"
          :class="['page-btn', currentPage === page ? 'active' : '']"
          @click="currentPage = page"
        >
          {{ page }}
        </button>

        <button 
          class="page-btn text-primary"
          :disabled="currentPage === totalPages"
          @click="currentPage++"
        >
          Berikutnya
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const props = defineProps({
  contents: Array,
});

const activeTab = ref('video');
const currentPage = ref(1);

// Kebutuhan: video 6 item per page, ebook 5 item per page
const itemsPerPage = computed(() => activeTab.value === 'video' ? 6 : 5);

// Reset halaman ketika tab berubah
watch(activeTab, () => {
  currentPage.value = 1;
});

const filteredContents = computed(() => {
  return props.contents.filter(item => {
    if (activeTab.value === 'video') return item.type === 'video';
    return item.type === 'ebook';
  });
});

const totalPages = computed(() => Math.ceil(filteredContents.value.length / itemsPerPage.value));

const paginatedContents = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredContents.value.slice(start, end);
});

function formatDate(dateStr) {
  const date = new Date(dateStr);
  const options = { day: 'numeric', month: 'long', year: 'numeric' };
  const formattedDate = date.toLocaleDateString('id-ID', options);
  
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  
  return `${formattedDate} • ${hours}:${minutes}`;
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
.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}
.btn-primary {
  background: var(--primary-color, #007bff);
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content */
.content-area { 
  padding: 24px 32px; 
  background: #fff;
  min-height: calc(100vh - 65px);
  display: flex;
  flex-direction: column;
}

/* Tabs */
.tabs-container {
  display: flex;
  gap: 12px;
  margin-bottom: 24px;
}

.tab-btn {
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  background: #eff6ff;
  color: var(--primary-color, #007bff);
}

.tab-btn.active {
  background: var(--primary-color, #007bff);
  color: #fff;
}

/* Empty State */
.empty-state {
  padding: 48px;
  text-align: center;
  color: #6b7280;
  font-size: 14px;
  background: #f9fafb;
  border-radius: 8px;
}

/* Grid */
.content-grid {
  display: grid;
  gap: 24px;
  margin-bottom: 48px;
}

.video-grid {
  grid-template-columns: repeat(3, 1fr);
}

.ebook-grid {
  grid-template-columns: repeat(5, 1fr);
}

@media (max-width: 1200px) {
  .video-grid { grid-template-columns: repeat(2, 1fr); }
  .ebook-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 900px) {
  .video-grid { grid-template-columns: 1fr; }
  .ebook-grid { grid-template-columns: repeat(2, 1fr); }
}

.content-card {
  display: flex;
  flex-direction: column;
  position: relative;
  background: #fff;
}

.card-thumbnail {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 12px;
}

.video-thumb {
  aspect-ratio: 16 / 10;
}

.ebook-thumb {
  aspect-ratio: 3 / 4;
}

.placeholder-thumb {
  width: 100%;
  height: 100%;
  background: #f3f4f6;
}

.card-thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.card-title {
  font-size: 16px;
  font-weight: 600;
  color: #111;
  margin: 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.card-meta {
  font-size: 13px;
  color: #9ca3af;
}

.card-action-overlay {
  position: absolute;
  inset: 0;
  color: transparent;
  z-index: 10;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
  margin-top: auto;
  padding-top: 32px;
}

.page-btn {
  background: transparent;
  border: none;
  font-size: 14px;
  cursor: pointer;
  padding: 4px;
  font-weight: 500;
  color: #111;
  display: flex;
  align-items: center;
  gap: 4px;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-btn.active {
  background: var(--primary-color, #007bff);
  color: #fff;
  border-radius: 4px;
  width: 28px;
  height: 28px;
  justify-content: center;
}

.text-muted { color: #9ca3af; }
.text-primary { color: var(--primary-color, #007bff); }
</style>
