<template>
  <PetugasLayout>
    <Head title="Kelola Pertanyaan - AMK" />

    <div class="top-bar">
      <h1 class="page-title">Pertanyaan</h1>
    </div>
    <div class="divider" />

    <div class="content-area">
      <div v-if="conversations.data.length === 0" class="empty-state">
        Belum ada pertanyaan.
      </div>

      <div v-else class="question-list">
        <div v-for="conv in conversations.data" :key="conv.id" class="question-card">
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
      <div class="pagination" v-if="conversations.links.length > 3">
        <Link 
          v-for="(link, k) in conversations.links" 
          :key="k"
          :href="link.url || '#'"
          :class="[
            'page-btn', 
            link.active ? 'active' : '', 
            !link.url ? 'btn-disabled' : '',
            k === 0 ? 'text-muted' : (k === conversations.links.length - 1 ? 'text-primary' : '')
          ]"
        >
          <template v-if="k === 0">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg> 
            Sebelumnya
          </template>
          <template v-else-if="k === conversations.links.length - 1">
            Berikutnya 
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </template>
          <template v-else>
            {{ link.label }}
          </template>
        </Link>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

defineProps({
  conversations: Object,
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
  gap: 20px;
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
  opacity: 0.3;
  cursor: not-allowed;
}

.page-btn.active {
  background: #acc000;
  color: #fff;
  border-radius: 10px;
  width: 40px;
  height: 40px;
  justify-content: center;
  font-weight: 600;
}

.text-muted { color: #ccc; }
.text-primary { color: #acc000; }
</style>
