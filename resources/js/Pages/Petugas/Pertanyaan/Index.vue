<template>
  <PetugasLayout>
    <Head title="Kelola Pertanyaan - AMK" />

    <div class="top-bar">
      <h1 class="page-title">Pertanyaan</h1>
    </div>
    <div class="divider" />

    <div class="content-area">
      <div v-if="paginatedConversations.length === 0" class="empty-state">
        Belum ada pertanyaan.
      </div>

      <div v-else class="question-list">
        <div v-for="conv in paginatedConversations" :key="conv.id" class="question-card">
          <div class="card-header">
            <span class="ticket-no">#{{ conv.ticket_number }}</span>
          </div>
          <h2 class="question-text">
            {{ conv.messages[conv.messages.length - 1]?.content || 'Tidak ada isi pertanyaan' }}
          </h2>
          <div class="card-footer">
            <span class="question-date">{{ formatDateTime(conv.created_at) }}</span>
            <span :class="['status-badge', conv.is_closed ? 'status-closed' : 'status-open']">
              {{ conv.is_closed ? 'Selesai' : 'Belum selesai' }}
            </span>
          </div>
          <Link :href="route('petugas.pertanyaan.show', conv.id)" class="card-link-overlay"></Link>
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
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const props = defineProps({
  conversations: {
    type: Array,
    default: () => []
  },
});

const currentPage = ref(1);
const itemsPerPage = 3;

const totalPages = computed(() => Math.ceil(props.conversations.length / itemsPerPage));

const paginatedConversations = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return props.conversations.slice(start, end);
});

function formatDateTime(dateStr) {
  const date = new Date(dateStr);
  const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
  return date.toLocaleDateString('id-ID', options).replace(' pukul', ' -');
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
.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}
.divider { height: 1px; background: #e5e7eb; }

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
  background: #f9fafb;
  border-radius: 12px;
  width: 100%;
  max-width: 600px;
}

.question-list {
  width: 100%;
  max-width: 600px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.question-card {
  position: relative;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  padding: 24px 32px;
  transition: all 0.2s;
}
.question-card:hover {
  border-color: #d1d5db;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.ticket-no {
  font-size: 13px;
  font-weight: 600;
  color: #9ca3af;
  display: block;
  margin-bottom: 8px;
}

.question-text {
  font-size: 18px;
  font-weight: 700;
  color: #111;
  margin: 0 0 16px;
  line-height: 1.4;
}

.card-footer {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.question-date {
  font-size: 13.5px;
  color: #9ca3af;
}

.status-badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
  width: fit-content;
}
.status-open { background: #fff7ed; color: #f97316; }
.status-closed { background: #f0fdf4; color: #22c55e; }

.card-link-overlay {
  position: absolute;
  inset: 0;
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
