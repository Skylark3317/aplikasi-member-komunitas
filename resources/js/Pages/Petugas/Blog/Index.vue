<template>
  <PetugasLayout>
    <Head title="Kelola Blog - AMK" />

    <!-- Top Bar -->
    <div class="top-bar animate-fade-in">
      <h1 class="page-title">Blog</h1>
      <Link :href="route('petugas.blog.create')" class="btn-primary hover-scale">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Tulis blog baru
      </Link>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      
      <div v-if="paginatedPosts.length === 0" class="empty-state">
        Belum ada blog.
      </div>
      
      <div v-else class="blog-list">
        <div v-for="post in paginatedPosts" :key="post.id" class="blog-card">
          <div class="blog-date-badge">
            {{ formatShortDate(post.created_at) }}
          </div>
          <h2 class="blog-title">{{ post.title }}</h2>
          <p class="blog-desc">{{ post.excerpt || truncateText(post.content, 180) }}</p>
          
          <Link :href="route('petugas.blog.edit', post.id)" class="card-action-overlay">
            Edit
          </Link>
        </div>
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
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const props = defineProps({
  posts: Array,
});

const currentPage = ref(1);
const itemsPerPage = 3;

const totalPages = computed(() => Math.ceil(props.posts.length / itemsPerPage));

const paginatedPosts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return props.posts.slice(start, end);
});

function formatShortDate(dateStr) {
  const date = new Date(dateStr);
  const d = String(date.getDate()).padStart(2, '0');
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const y = date.getFullYear();
  return `${d}/${m}/${y}`;
}

function truncateText(text, length) {
  if (!text) return '';
  const stripped = text.replace(/(<([^>]+)>)/gi, "");
  return stripped.length > length ? stripped.substring(0, length) + '...' : stripped;
}

// Add animations on mount
onMounted(() => {
  setTimeout(() => {
    const cards = document.querySelectorAll('.blog-card');
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 150}ms`;
      card.classList.add('animate-fade-in-up', 'animate-fill-both');
    });
  }, 100);
});
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
  transition: filter 0.2s;
  border: 1px solid transparent;
  box-sizing: border-box;
  background: var(--primary-color, #2563eb);
  color: #fff;
  border-color: var(--primary-color, #2563eb);
}
.btn-primary:hover { filter: brightness(0.9); }

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content */
.content-area { 
  padding: 40px 32px; 
  background: #fff;
  min-height: calc(100vh - 65px);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.empty-state {
  padding: 48px;
  text-align: center;
  color: #6b7280;
  font-size: 14px;
  background: #f9fafb;
  border-radius: 8px;
  width: 100%;
  max-width: 800px;
}

/* Blog List */
.blog-list {
  width: 100%;
  max-width: 800px;
  display: flex;
  flex-direction: column;
  gap: 24px;
  margin-bottom: 40px;
}

.blog-card {
  position: relative;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 32px;
  transition: all 0.2s;
}

.blog-card:hover {
  border-color: #d1d5db;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.blog-date-badge {
  display: inline-block;
  background: #eff6ff;
  color: var(--primary-color, #2563eb);
  font-size: 12px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
  margin-bottom: 20px;
}

.blog-title {
  font-size: 20px;
  font-weight: 700;
  color: #111;
  margin: 0 0 16px;
  line-height: 1.4;
}

.blog-desc {
  font-size: 14.5px;
  color: #4b5563;
  margin: 0;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
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