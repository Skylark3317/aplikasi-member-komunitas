<template>
  <MemberLayout>
    <Head title="Pertanyaan - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Pertanyaan</h1>
      <Link v-if="$page.props.auth.user.is_premium" :href="route('member.pertanyaan.create')" class="btn-buat-pertanyaan">
        Buat pertanyaan baru
      </Link>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Non-Premium Alert (Same as Konten/Index.vue) -->
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

      <template v-else>
        <!-- Empty State -->
        <div v-if="conversations.length === 0" class="empty-state-card">
          <div class="empty-icon-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="empty-icon">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
          </div>
          <h3>Belum ada pertanyaan</h3>
          <p>Apakah Anda memiliki pertanyaan atau kendala seputar materi? Silakan buat pertanyaan baru.</p>
          <Link :href="route('member.pertanyaan.create')" class="btn-buat-pertanyaan mt-4">
            Buat pertanyaan pertama
          </Link>
        </div>

        <!-- Cards List -->
        <div v-else class="qa-list-wrapper">
          <div class="qa-cards-grid">
            <Link 
              v-for="ticket in paginatedConversations" 
              :key="ticket.id"
              :href="route('member.pertanyaan.show', ticket.id)" 
              class="qa-card"
            >
              <span class="qa-ticket-id">#{{ ticket.id }}</span>
              <h3 class="qa-title">
                {{ ticket.messages && ticket.messages.length > 0 ? truncateText(ticket.messages[0].content, 80) : 'Pertanyaan baru' }}
              </h3>
              <span class="qa-timestamp">{{ formatDate(ticket.created_at) }}</span>
              
              <div class="qa-status-row">
                <span :class="['qa-badge', ticket.is_closed ? 'badge-selesai' : 'badge-belum-selesai']">
                  {{ ticket.is_closed ? 'Selesai' : 'Belum selesai' }}
                </span>
              </div>
            </Link>
          </div>

          <!-- Pagination matching mockup -->
          <div v-if="totalPages > 1" class="pagination-row">
            <button 
              @click="prevPage" 
              :disabled="currentPage === 1"
              class="pagination-arrow"
            >
              &lt; Sebelumnya
            </button>
            
            <div class="page-numbers">
              <button 
                v-for="p in totalPages" 
                :key="p"
                @click="currentPage = p"
                :class="['page-num-btn', currentPage === p ? 'active-page' : '']"
              >
                {{ p }}
              </button>
            </div>

            <button 
              @click="nextPage" 
              :disabled="currentPage === totalPages"
              class="pagination-arrow active-arrow"
            >
              Berikutnya &gt;
            </button>
          </div>
        </div>
      </template>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  conversations: {
    type: Array,
    default: () => [],
  },
});

const showAlert = ref(true);

// Client-side pagination matching mockup visually
const currentPage = ref(1);
const itemsPerPage = 3;

const totalPages = computed(() => Math.ceil(props.conversations.length / itemsPerPage));

const paginatedConversations = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return props.conversations.slice(start, start + itemsPerPage);
});

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}

function truncateText(str, n) {
  if (!str) return '';
  return str.length > n ? str.substr(0, n - 1) + '...' : str;
}

function formatDate(dateStr) {
  const date = new Date(dateStr);
  
  // Format to e.g. "10 April 2026 • 14:00"
  const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
  const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: false };
  
  const formattedDate = date.toLocaleDateString('id-ID', dateOptions);
  const formattedTime = date.toLocaleTimeString('id-ID', timeOptions).replace('.', ':');
  
  return `${formattedDate} • ${formattedTime}`;
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

.btn-buat-pertanyaan {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.15s ease;
}

.btn-buat-pertanyaan:hover {
  background: #0056b3;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content Area */
.content-area {
  padding: 32px;
  background: #f9fafb;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
}

/* Non-Premium Alert (Same as Konten/Index.vue) */
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

/* QA List Wrapper - Centered exactly like mockup */
.qa-list-wrapper {
  max-width: 760px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 32px;
}

.qa-cards-grid {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.qa-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 24px;
  text-decoration: none;
  display: flex;
  flex-direction: column;
  gap: 10px;
  transition: all 0.15s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
}

.qa-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
  border-color: #bfdbfe;
}

.qa-ticket-id {
  font-size: 12.5px;
  color: #6b7280;
  font-weight: 500;
}

.qa-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0;
  line-height: 1.4;
}

.qa-timestamp {
  font-size: 12.5px;
  color: #9ca3af;
  font-weight: 500;
}

.qa-status-row {
  display: flex;
  margin-top: 4px;
}

/* Badges */
.qa-badge {
  display: inline-block;
  padding: 4px 10px;
  font-size: 11.5px;
  font-weight: 700;
  border-radius: 6px;
}

.badge-selesai {
  background: #def7ec;
  color: #03543f;
}

.badge-belum-selesai {
  background: #fef3c7;
  color: #b45309;
}

/* Pagination design */
.pagination-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  margin-top: 16px;
}

.pagination-arrow {
  background: transparent;
  border: none;
  color: #9ca3af;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  padding: 4px;
}

.pagination-arrow:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.active-arrow {
  color: #007bff;
}

.page-numbers {
  display: flex;
  align-items: center;
  gap: 8px;
}

.page-num-btn {
  width: 36px;
  height: 36px;
  border: none;
  background: transparent;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.15s ease;
}

.active-page {
  background: #007bff;
  color: #fff !important;
}

/* Empty State Card */
.empty-state-card {
  background: #fff;
  border: 1px dashed #d1d5db;
  border-radius: 12px;
  padding: 64px 32px;
  text-align: center;
  max-width: 600px;
  margin: 40px auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.empty-icon-wrapper {
  width: 64px;
  height: 64px;
  background: #eff6ff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}

.empty-icon {
  width: 30px;
  height: 30px;
  color: #007bff;
}

.empty-state-card h3 {
  font-size: 17px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

.empty-state-card p {
  font-size: 14px;
  color: #6b7280;
  line-height: 1.6;
  max-width: 460px;
  margin: 0 0 20px;
}

.mt-4 {
  margin-top: 16px;
}
</style>
