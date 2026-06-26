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

      <!-- Premium Content Interface -->
      <div v-else class="premium-interface">
        <!-- Always-visible tabs -->
        <div class="tabs-container">
          <button
            :class="['tab-btn', activeTab === 'video' ? 'active' : '', !canAccessVideo ? 'tab-locked' : '']"
            @click="activeTab = 'video'"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            Video Premium
            <span v-if="!canAccessVideo" class="tab-lock-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
          </button>
          <button
            :class="['tab-btn', activeTab === 'ebook' ? 'active' : '', !canAccessEbook ? 'tab-locked' : '']"
            @click="activeTab = 'ebook'"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Ebook Berkualitas
            <span v-if="!canAccessEbook" class="tab-lock-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
          </button>
        </div>

        <!-- Tab: Video -->
        <div v-if="activeTab === 'video'">
          <!-- Locked notice for video -->
          <div v-if="!canAccessVideo" class="tab-locked-notice">
            <div class="locked-icon-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h3 class="locked-notice-title">Akses Video Terkunci</h3>
            <p class="locked-notice-desc">Paket Anda saat ini tidak menyertakan benefit <strong>Akses Video Premium</strong>. Upgrade ke paket yang mencakup benefit ini untuk menonton video eksklusif kami.</p>
            <Link :href="route('member.premium.index')" class="btn-upgrade-sm">
              Lihat Paket Premium
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><polyline points="12 5 19 12 12 19"/></svg>
            </Link>
          </div>

          <!-- Video content grid -->
          <template v-else>
            <div v-if="paginatedContents.length === 0" class="empty-state">Belum ada konten video.</div>
            <div v-else class="content-grid video-grid">
              <div v-for="item in paginatedContents" :key="item.id" class="content-card" @click="openViewer(item)">
                <div class="card-thumbnail video-thumb">
                  <img v-if="item.thumbnail_url" :src="`/storage/${item.thumbnail_url}`" alt="Thumbnail" />
                  <div v-else class="placeholder-thumb"></div>
                </div>
                <div class="card-info">
                  <h3 class="card-title">{{ item.title }}</h3>
                  <div class="card-meta">{{ formatDate(item.created_at) }}</div>
                </div>
              </div>
            </div>
            <div class="pagination" v-if="totalPages > 1">
              <button class="page-nav-btn" :disabled="currentPage === 1" @click="currentPage--">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Sebelumnya
              </button>
              <div class="page-numbers">
                <button v-for="page in totalPages" :key="page"
                  :class="['page-num-btn', currentPage === page ? 'active' : '']"
                  @click="currentPage = page">{{ page }}</button>
              </div>
              <button class="page-nav-btn" :disabled="currentPage === totalPages" @click="currentPage++">
                Berikutnya
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
            </div>
          </template>
        </div>

        <!-- Tab: Ebook -->
        <div v-if="activeTab === 'ebook'">
          <!-- Locked notice for ebook -->
          <div v-if="!canAccessEbook" class="tab-locked-notice">
            <div class="locked-icon-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h3 class="locked-notice-title">Akses Ebook Terkunci</h3>
            <p class="locked-notice-desc">Paket Anda saat ini tidak menyertakan benefit <strong>Akses Ebook Berkualitas</strong>. Upgrade ke paket yang mencakup benefit ini untuk mengakses koleksi ebook kami.</p>
            <Link :href="route('member.premium.index')" class="btn-upgrade-sm">
              Lihat Paket Premium
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><polyline points="12 5 19 12 12 19"/></svg>
            </Link>
          </div>

          <!-- Ebook content grid -->
          <template v-else>
            <div v-if="paginatedContents.length === 0" class="empty-state">Belum ada konten ebook.</div>
            <div v-else class="content-grid ebook-grid">
              <div v-for="item in paginatedContents" :key="item.id" class="content-card" @click="openViewer(item)">
                <div class="card-thumbnail ebook-thumb">
                  <img v-if="item.thumbnail_url" :src="`/storage/${item.thumbnail_url}`" alt="Thumbnail" />
                  <div v-else class="placeholder-thumb"></div>
                </div>
                <div class="card-info">
                  <h3 class="card-title">{{ item.title }}</h3>
                  <div class="card-meta">{{ formatDate(item.created_at) }}</div>
                </div>
              </div>
            </div>
            <div class="pagination" v-if="totalPages > 1">
              <button class="page-nav-btn" :disabled="currentPage === 1" @click="currentPage--">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Sebelumnya
              </button>
              <div class="page-numbers">
                <button v-for="page in totalPages" :key="page"
                  :class="['page-num-btn', currentPage === page ? 'active' : '']"
                  @click="currentPage = page">{{ page }}</button>
              </div>
              <button class="page-nav-btn" :disabled="currentPage === totalPages" @click="currentPage++">
                Berikutnya
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
            </div>
          </template>
        </div>

      </div><!-- end premium-interface -->
    </div><!-- end content-area -->
  </MemberLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  contents:       { type: Array,   default: () => [] },
  canAccessEbook: { type: Boolean, default: false },
  canAccessVideo: { type: Boolean, default: false },
  activeBenefits: { type: Array,   default: () => [] },
});

const showAlert = ref(true);
// Always default to 'video' tab
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
  gap: 12px;
  margin-bottom: 24px;
}

.tab-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 20px;
  border-radius: 20px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  border: 1.5px solid transparent;
  background: #f3f4f6;
  color: #6b7280;
  transition: all 0.2s;
}
.tab-btn svg { width: 14px; height: 14px; }
.tab-btn.active {
  background: var(--primary-color, #007bff);
  color: #fff;
  border-color: var(--primary-color, #007bff);
  box-shadow: 0 4px 12px rgba(0,123,255,0.2);
}
.tab-btn:hover:not(.active) {
  background: #e5e7eb;
  color: #374151;
}
.tab-btn.tab-locked {
  opacity: 0.75;
}
.tab-lock-icon {
  display: inline-flex; align-items: center;
  margin-left: 2px;
}
.tab-lock-icon svg { width: 12px; height: 12px; }

/* Per-tab locked notice */
.tab-locked-notice {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 60px 40px;
  background: #f9fafb;
  border-radius: 16px;
  border: 1px dashed #d1d5db;
  max-width: 500px;
  margin: 8px auto 0;
}
.locked-icon-sm {
  width: 60px; height: 60px;
  background: #fef9c3;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 16px;
}
.locked-icon-sm svg { width: 26px; height: 26px; color: #ca8a04; }
.locked-notice-title {
  font-size: 17px; font-weight: 700; color: #111;
  margin: 0 0 8px;
}
.locked-notice-desc {
  font-size: 13.5px; color: #6b7280; line-height: 1.6;
  margin: 0 0 20px; max-width: 360px;
}
.btn-upgrade-sm {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--primary-color, #007bff);
  color: #fff; font-size: 13.5px; font-weight: 600;
  padding: 10px 20px; border-radius: 8px;
  text-decoration: none; transition: filter 0.2s;
}
.btn-upgrade-sm svg { width: 14px; height: 14px; }
.btn-upgrade-sm:hover { filter: brightness(0.9); }

/* Locked state (fallback full-page) */
.locked-all {
  text-align: center;
  padding: 80px 40px;
  background: #f9fafb;
  border-radius: 16px;
  border: 1px dashed #d1d5db;
}
.locked-icon {
  width: 72px; height: 72px;
  background: #fee2e2;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 20px;
}
.locked-icon svg { width: 32px; height: 32px; color: #ef4444; }
.locked-all h3 { font-size: 18px; font-weight: 700; color: #111; margin: 0 0 10px; }
.locked-all p { font-size: 13.5px; color: #6b7280; max-width: 400px; line-height: 1.6; margin: 0; }

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
  gap: 6px;
  margin-top: 12px;
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
  color: #888;
}

/* ── Pagination ── */
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
  color: var(--primary-color, #007bff);
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
  background: var(--primary-color, #007bff);
  color: #fff;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.page-num-btn.active:hover {
  filter: brightness(1.1);
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
  color: var(--primary-color, #007bff);
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
  background: var(--primary-color, #007bff);
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
