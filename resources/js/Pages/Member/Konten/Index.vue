<template>
  <MemberLayout>
    <Head title="Konten - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Konten</h1>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Non-Premium Alert (Image 1) -->
      <div v-if="!$page.props.auth.user.is_premium" class="non-premium-alert-container">
        <div v-if="showAlert" class="non-premium-alert">
          <span class="alert-message">Anda perlu menjadi member untuk mengakses fitur ini.</span>
          <button class="alert-close-btn" @click="showAlert = false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="close-icon-svg">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      </div>

      <!-- Premium Content Interface (Image 2 & 3) -->
      <div v-else class="premium-interface">
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
          <div v-for="item in paginatedContents" :key="item.id" class="content-card" @click="openViewer(item)">
            <!-- Thumbnail (Fully plain empty solid grey exactly like mockups!) -->
            <div :class="['card-thumbnail', activeTab === 'video' ? 'video-thumb' : 'ebook-thumb']">
            </div>
            
            <!-- Info -->
            <div class="card-info">
              <h3 class="card-title">{{ item.title }}</h3>
              <div class="card-meta">
                {{ formatDate(item.created_at) }}
              </div>
            </div>
          </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination" v-if="totalPages > 1">
          <button 
            class="page-link"
            :disabled="currentPage === 1"
            @click="currentPage--"
          >
            &lt; Sebelumnya
          </button>
          
          <div class="page-numbers">
            <button 
              v-for="page in totalPages" 
              :key="page"
              :class="['page-num', currentPage === page ? 'active' : '']"
              @click="currentPage = page"
            >
              {{ page }}
            </button>
          </div>

          <button 
            class="page-link"
            :disabled="currentPage === totalPages"
            @click="currentPage++"
          >
            Berikutnya &gt;
          </button>
        </div>
      </div>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  contents: {
    type: Array,
    default: () => [],
  },
});

const showAlert = ref(true);
const activeTab = ref('video');
const currentPage = ref(1);

const itemsPerPage = computed(() => activeTab.value === 'video' ? 6 : 5);

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

function openViewer(item) {
  if (item && item.file_url) {
    const url = item.file_url.startsWith('http') ? item.file_url : `/storage/${item.file_url}`;
    window.open(url, '_blank');
  }
}

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
/* ── Top bar ── */
.top-bar {
  display: flex;
  align-items: center;
  padding: 16px 32px;
  background: #fff;
}
.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* ── Content Area ── */
.content-area {
  padding: 24px 32px;
  background: #fff;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
}

/* ── Non-Premium Alert (Image 1) ── */
.non-premium-alert {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fef2f2;
  border: 1px solid #fee2e2;
  border-radius: 8px;
  padding: 12px 20px;
  color: #ef4444;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 24px;
}

.alert-close-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: #ef4444;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background 0.15s;
}

.alert-close-btn:hover {
  background: rgba(239, 68, 68, 0.1);
}

.close-icon-svg {
  width: 14px;
  height: 14px;
}

/* ── Premium Interface (Image 2 & 3) ── */
.tabs-container {
  display: flex;
  gap: 10px;
  margin-bottom: 32px;
}

.tab-btn {
  padding: 8px 20px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  background: #e3f2fd;
  color: #007bff;
  transition: all 0.15s ease;
}

.tab-btn.active {
  background: #007bff;
  color: #fff;
}

.empty-state {
  padding: 48px;
  text-align: center;
  color: #6b7280;
  font-size: 14px;
  background: #f9fafb;
  border-radius: 8px;
}

/* ── Grid ── */
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

@media (max-width: 1100px) {
  .video-grid { grid-template-columns: repeat(2, 1fr); }
  .ebook-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 750px) {
  .video-grid { grid-template-columns: 1fr; }
  .ebook-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ── Content Card ── */
.content-card {
  display: flex;
  flex-direction: column;
  cursor: pointer;
  background: #fff;
}

.card-thumbnail {
  width: 100%;
  border-radius: 8px;
  background: #f2f2f2;
  transition: opacity 0.15s ease;
}

.content-card:hover .card-thumbnail {
  opacity: 0.9;
}

.video-thumb {
  aspect-ratio: 16 / 9;
}

.ebook-thumb {
  aspect-ratio: 3 / 4;
}

.card-info {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 12px;
}

.card-title {
  font-size: 13.5px;
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
  font-size: 11px;
  color: #888;
}

/* ── Pagination (Image 2 & 3) ── */
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
  margin-top: 24px;
}

.page-link {
  background: none;
  border: none;
  font-size: 12px;
  font-weight: 500;
  color: #007bff;
  cursor: pointer;
  padding: 6px 12px;
  display: flex;
  align-items: center;
  transition: color 0.15s;
}

.page-link:hover:not(:disabled) {
  color: #0056b3;
}

.page-link:disabled {
  color: #9ca3af;
  cursor: not-allowed;
}

.page-numbers {
  display: flex;
  align-items: center;
  gap: 6px;
}

.page-num {
  background: none;
  border: none;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 500;
  color: #555;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.15s;
}

.page-num:hover:not(.active) {
  background: #f3f4f6;
  color: #111;
}

.page-num.active {
  background: #007bff;
  color: #fff;
  font-weight: 600;
}

/* ── Media Viewer Modal ── */
.viewer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  backdrop-filter: blur(4px);
}

.viewer-content {
  background: #fff;
  width: 90%;
  max-width: 800px;
  border-radius: 16px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.3);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.viewer-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.viewer-header h3 {
  font-size: 17px;
  font-weight: 600;
  color: #111;
  margin: 0;
}

.btn-close {
  background: none;
  border: none;
  cursor: pointer;
  color: #9ca3af;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px;
  border-radius: 50%;
  transition: background 0.15s;
}

.btn-close:hover {
  background: #f3f4f6;
  color: #111;
}

.btn-close svg {
  width: 18px;
  height: 18px;
}

.viewer-body {
  padding: 24px;
  background: #f9fafb;
}

.video-container {
  width: 100%;
  aspect-ratio: 16 / 9;
  background: #000;
  border-radius: 8px;
  overflow: hidden;
}

.video-player {
  width: 100%;
  height: 100%;
}

.ebook-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px 20px;
  text-align: center;
  background: #fff;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.ebook-icon-wrapper {
  width: 80px;
  height: 80px;
  background: #eff6ff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}

.ebook-large-icon {
  width: 40px;
  height: 40px;
  color: #007bff;
}

.ebook-container p {
  font-size: 14.5px;
  color: #4b5563;
  margin-bottom: 28px;
}

.btn-download {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #007bff;
  color: #fff;
  padding: 10px 24px;
  border-radius: 6px;
  font-weight: 500;
  text-decoration: none;
  font-size: 14px;
  transition: filter 0.15s;
}

.btn-download:hover {
  filter: brightness(0.9);
}
</style>
