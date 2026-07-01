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
        <Link 
          v-for="item in paginatedContents" 
          :key="item.id" 
          :href="route('petugas.konten.edit', item.id)"
          class="content-card"
        >
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
          </div>
        </Link>
      </div>
      
      <!-- Pagination -->
      <div class="pagination" v-if="totalPages > 1">
        <button 
          class="page-nav-btn"
          :disabled="currentPage === 1"
          @click="currentPage--"
        >
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
          Sebelumnya
        </button>
        
        <div class="page-numbers">
          <button 
            v-for="page in totalPages" 
            :key="page"
            :class="['page-num-btn', currentPage === page ? 'active' : '']"
            @click="currentPage = page"
          >
            {{ page }}
          </button>
        </div>

        <button 
          class="page-nav-btn"
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
import { ref, computed, watch, onMounted, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

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

// Animations
onMounted(() => {
  // Top bar animation
  const topBar = document.querySelector('.top-bar');
  if (topBar) {
    topBar.style.opacity = '0';
    topBar.style.transform = 'translateY(-20px)';
    setTimeout(() => {
      topBar.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
      topBar.style.opacity = '1';
      topBar.style.transform = 'translateY(0)';
    }, 100);
  }

  // Tabs animation
  const tabs = document.querySelectorAll('.tab-btn');
  tabs.forEach((tab, index) => {
    tab.style.opacity = '0';
    tab.style.transform = 'scale(0.8)';
    setTimeout(() => {
      tab.style.transition = 'all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
      tab.style.opacity = '1';
      tab.style.transform = 'scale(1)';
    }, 200 + index * 80);
  });

  animateCards();
});

// Animate cards when they appear
watch([activeTab, currentPage], () => {
  nextTick(() => {
    animateCards();
  });
});

function animateCards() {
  const cards = document.querySelectorAll('.content-card');
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px) scale(0.95)';
    setTimeout(() => {
      card.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0) scale(1)';
    }, index * 100);
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
.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}
.btn-primary {
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
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  border: 1px solid transparent;
  box-sizing: border-box;
  background: var(--primary-color, #007bff);
  color: #fff;
  border-color: var(--primary-color, #007bff);
}
.btn-primary:hover { 
  filter: brightness(0.9);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
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
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.tab-btn:hover:not(.active) {
  background: #dbeafe;
  transform: scale(1.05);
}

.tab-btn.active {
  background: var(--primary-color, #007bff);
  color: #fff;
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
  transform: scale(1.05);
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
  transition: all 0.3s ease;
  cursor: pointer;
  text-decoration: none;
  color: inherit;
  border-radius: 8px;
  overflow: hidden;
}

.content-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.card-thumbnail {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 12px;
  position: relative;
}

.card-thumbnail::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.3));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.content-card:hover .card-thumbnail::after {
  opacity: 1;
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
  transition: transform 0.4s ease;
}

.content-card:hover .card-thumbnail img {
  transform: scale(1.1);
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

/* Removed card-action-overlay since whole card is now clickable */

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: auto;
  padding-top: 40px;
  width: 100%;
}

.page-numbers {
  display: flex;
  align-items: center;
  gap: 8px;
}

.page-nav-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: transparent;
  border: none;
  font-size: 14px;
  font-weight: 500;
  color: var(--primary-color);
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.page-nav-btn:hover:not(:disabled) {
  background: #eff6ff;
}

.page-nav-btn:disabled {
  color: #9ca3af;
  cursor: not-allowed;
}

.page-num-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: transparent;
  border: 1px solid transparent;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s ease;
}

.page-num-btn:hover:not(.active) {
  background: #f3f4f6;
  color: #111;
}

.page-num-btn.active {
  background: var(--primary-color);
  color: #fff;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.page-num-btn.active:hover {
  filter: brightness(1.1);
}
</style>