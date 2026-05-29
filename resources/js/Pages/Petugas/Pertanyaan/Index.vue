<template>
  <PetugasLayout>
    <Head title="Pertanyaan - AMK" />

    <div class="chat-container-layout">
      <!-- Left Panel: Chat List -->
      <div class="chat-sidebar">
        <div class="sidebar-header">
          <h1 class="sidebar-title">Pertanyaan</h1>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
          <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Cari pesan atau nama member..." 
              class="search-input" 
            />
          </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
          <button 
            :class="['filter-tab', activeTab === 'all' ? 'active' : '']" 
            @click="activeTab = 'all'"
          >
            Semua
          </button>
          <button 
            :class="['filter-tab', activeTab === 'unread' ? 'active' : '']" 
            @click="activeTab = 'unread'"
          >
            Belum Dibaca
            <span v-if="totalUnreadCount > 0" class="tab-badge">{{ totalUnreadCount }}</span>
          </button>
        </div>

        <!-- Chat List Scrollable Area -->
        <div class="chat-list-wrapper">
          <div v-if="filteredConversations.length === 0" class="empty-chats">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <p>Tidak ada percakapan ditemukan</p>
          </div>

          <div v-else class="chat-list">
            <div 
              v-for="conv in paginatedConversations" 
              :key="conv.id" 
              class="chat-item"
              @click="openChat(conv.id)"
            >
              <!-- Avatar -->
              <div class="chat-avatar-container">
                <img 
                  v-if="conv.submitter.avatar_url" 
                  :src="conv.submitter.avatar_url" 
                  alt="Avatar" 
                  class="chat-avatar-img"
                />
                <div v-else class="chat-avatar-placeholder">
                  {{ conv.submitter.name.charAt(0).toUpperCase() }}
                </div>
              </div>

              <!-- Chat Info -->
              <div class="chat-item-body">
                <div class="chat-item-header">
                  <span class="chat-item-name">{{ conv.submitter.name }}</span>
                  <span class="chat-item-time">{{ formatTime(conv.last_message?.created_at || conv.updated_at) }}</span>
                </div>
                <div class="chat-item-message-row">
                  <p class="chat-item-preview">
                    {{ conv.last_message?.content || 'Belum ada pesan' }}
                  </p>
                  <span v-if="conv.unread_count > 0" class="chat-unread-badge">
                    {{ conv.unread_count }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div v-if="totalPages > 1" class="sidebar-pagination">
          <button 
            :disabled="currentPage === 1" 
            @click="currentPage--" 
            class="pagination-btn"
            title="Sebelumnya"
          >
            &laquo;
          </button>
          <span class="pagination-info">
            {{ currentPage }} / {{ totalPages }}
          </span>
          <button 
            :disabled="currentPage === totalPages" 
            @click="currentPage++" 
            class="pagination-btn"
            title="Berikutnya"
          >
            &raquo;
          </button>
        </div>
      </div>

      <!-- Right Panel: Welcome / No Chat Selected -->
      <div class="chat-welcome-panel">
        <div class="welcome-inner">
          <div class="welcome-icon-box">
            <svg class="welcome-chat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
            </svg>
          </div>
          <h2 class="welcome-title">AMK Chatroom</h2>
          <p class="welcome-subtitle">Pilih percakapan member dari daftar di sebelah kiri untuk mulai membalas pertanyaan.</p>
        </div>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const props = defineProps({
  conversations: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

const searchQuery = ref('');
const activeTab = ref('all'); // 'all' or 'unread'

// Pagination state
const currentPage = ref(1);
const itemsPerPage = 5;

// Count total unread conversations
const totalUnreadCount = computed(() => {
  return props.conversations.filter(c => c.unread_count > 0).length;
});

// Client side filtering for maximum performance and instant typing experience
const filteredConversations = computed(() => {
  let list = props.conversations;

  if (activeTab.value === 'unread') {
    list = list.filter(c => c.unread_count > 0);
  }

  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase();
    list = list.filter(c => {
      const nameMatch = c.submitter.name.toLowerCase().includes(q);
      const msgMatch = c.last_message?.content.toLowerCase().includes(q);
      return nameMatch || msgMatch;
    });
  }

  return list;
});

const totalPages = computed(() => {
  return Math.ceil(filteredConversations.value.length / itemsPerPage);
});

const paginatedConversations = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredConversations.value.slice(start, end);
});

// Reset page on search or filter change
watch([searchQuery, activeTab], () => {
  currentPage.value = 1;
});

function openChat(id) {
  router.visit(route('petugas.pertanyaan.show', id));
}

function formatTime(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  const now = new Date();
  
  // If today, show HH:MM
  if (d.toDateString() === now.toDateString()) {
    return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  }
  
  // If yesterday
  const yesterday = new Date(now);
  yesterday.setDate(now.getDate() - 1);
  if (d.toDateString() === yesterday.toDateString()) {
    return 'Kemarin';
  }
  
  // Otherwise show date
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
}
</script>

<style scoped>
.chat-container-layout {
  display: flex;
  width: 100%;
  height: 100vh;
  background: #f8fafc;
  overflow: hidden;
}

/* Sidebar styling */
.chat-sidebar {
  width: 380px;
  background: #ffffff;
  border-right: 1px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}

.sidebar-header {
  padding: 20px 24px 12px;
}

.sidebar-title {
  font-size: 22px;
  font-weight: 700;
  color: #0f172a;
}

/* Search Box */
.search-container {
  padding: 0 24px 16px;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 14px;
  width: 18px;
  height: 18px;
  color: #94a3b8;
  pointer-events: none;
}

.search-input {
  width: 100%;
  padding: 10px 14px 10px 42px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 14px;
  background: #f8fafc;
  color: #0f172a;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-color, #2563eb);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Filter Tabs */
.filter-tabs {
  display: flex;
  gap: 8px;
  padding: 0 24px 16px;
  border-bottom: 1px solid #f1f5f9;
}

.filter-tab {
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
  background: #f1f5f9;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}

.filter-tab:hover {
  background: #e2e8f0;
  color: #334155;
}

.filter-tab.active {
  background: var(--primary-color, #2563eb);
  color: #ffffff;
}

.filter-tab.active .tab-badge {
  background: #ffffff;
  color: var(--primary-color, #2563eb);
}

.tab-badge {
  background: var(--primary-color, #2563eb);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 10px;
}

/* Chat List Scrollable */
.chat-list-wrapper {
  flex: 1;
  overflow-y: auto;
}

.empty-chats {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 24px;
  text-align: center;
  color: #94a3b8;
}

.empty-icon {
  width: 48px;
  height: 48px;
  margin-bottom: 12px;
}

.empty-chats p {
  font-size: 14px;
  font-weight: 500;
}

.chat-list {
  display: flex;
  flex-direction: column;
}

.chat-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 24px;
  cursor: pointer;
  border-bottom: 1px solid #f1f5f9;
  transition: background 0.15s ease;
}

.chat-item:hover {
  background: #f8fafc;
}

/* Avatar styling */
.chat-avatar-container {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}

.chat-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.chat-avatar-placeholder {
  width: 100%;
  height: 100%;
  background: #e2e8f0;
  color: #475569;
  font-weight: 700;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Chat Item Body */
.chat-item-body {
  flex: 1;
  min-width: 0; /* Allows text truncation */
}

.chat-item-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 4px;
}

.chat-item-name {
  font-size: 15px;
  font-weight: 700;
  color: #0f172a;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.chat-item-time {
  font-size: 12px;
  color: #64748b;
  flex-shrink: 0;
}

.chat-item-message-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.chat-item-preview {
  font-size: 13.5px;
  color: #64748b;
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  flex: 1;
  padding-right: 8px;
}

.chat-unread-badge {
  background: var(--primary-color, #2563eb);
  color: #ffffff;
  font-size: 11px;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
  flex-shrink: 0;
}

/* Pagination */
.sidebar-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 12px 16px;
  border-top: 1px solid #f1f5f9;
  background: #ffffff;
  flex-shrink: 0;
}

.pagination-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  color: #475569;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.15s;
  line-height: 1;
}

.pagination-btn:hover:not(:disabled) {
  background: var(--primary-color, #2563eb);
  color: #ffffff;
  border-color: var(--primary-color, #2563eb);
}

.pagination-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

.pagination-info {
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
  min-width: 48px;
  text-align: center;
}

/* Welcome panel styling */
.chat-welcome-panel {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  padding: 40px;
}

.welcome-inner {
  max-width: 420px;
  text-align: center;
}

.welcome-icon-box {
  width: 80px;
  height: 80px;
  background: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
}

.welcome-chat-icon {
  width: 40px;
  height: 40px;
  color: #64748b;
}

.welcome-title {
  font-size: 20px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}

.welcome-subtitle {
  font-size: 14px;
  color: #64748b;
  line-height: 1.5;
}
</style>
