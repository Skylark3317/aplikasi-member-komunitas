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

        <!-- Filter Tabs (Only show when searchQuery is empty) -->
        <div v-if="searchQuery.trim() === ''" class="filter-tabs">
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

        <!-- Standard Chat List (Only show when searchQuery is empty) -->
        <div v-if="searchQuery.trim() === ''" class="chat-list-wrapper">
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

        <!-- Search Results (Only show when searchQuery is not empty) -->
        <div v-else class="chat-list-wrapper">
          <div v-if="searchResults.chats.length === 0 && searchResults.messages.length === 0" class="empty-chats">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <p>Tidak ada hasil untuk "{{ searchQuery }}"</p>
          </div>

          <div v-else class="search-results-list">
            <!-- Chats Section -->
            <div v-if="searchResults.chats.length > 0" class="search-section">
              <div class="search-section-title">CHAT</div>
              <div class="chat-list">
                <div 
                  v-for="conv in searchResults.chats" 
                  :key="'chat-' + conv.id" 
                  class="chat-item"
                  @click="openChat(conv.id)"
                >
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
                  <div class="chat-item-body">
                    <div class="chat-item-header">
                      <span class="chat-item-name">
                        <span v-for="(part, idx) in getHighlightedParts(conv.submitter.name, searchQuery)" :key="idx" :class="part.isMatch ? 'search-highlight' : ''">
                          {{ part.text }}
                        </span>
                      </span>
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

            <!-- Messages Section -->
            <div v-if="searchResults.messages.length > 0" class="search-section">
              <div class="search-section-title">PESAN</div>
              <div class="chat-list">
                <div 
                  v-for="(item, index) in searchResults.messages" 
                  :key="'msg-' + index" 
                  class="chat-item"
                  @click="openChat(item.conversationId, item.message.id)"
                >
                  <div class="chat-avatar-container">
                    <img 
                      v-if="item.submitter.avatar_url" 
                      :src="item.submitter.avatar_url" 
                      alt="Avatar" 
                      class="chat-avatar-img"
                    />
                    <div v-else class="chat-avatar-placeholder">
                      {{ item.submitter.name.charAt(0).toUpperCase() }}
                    </div>
                  </div>
                  <div class="chat-item-body">
                    <div class="chat-item-header">
                      <span class="chat-item-name">{{ item.submitter.name }}</span>
                      <span class="chat-item-time">{{ formatTime(item.message.created_at) }}</span>
                    </div>
                    <div class="chat-item-message-row">
                      <p class="chat-item-preview">
                        <span v-for="(part, idx) in getHighlightedParts(item.message.content, searchQuery)" :key="idx" :class="part.isMatch ? 'search-highlight' : ''">
                          {{ part.text }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination Controls (Only show when searchQuery is empty) -->
        <div v-if="searchQuery.trim() === '' && totalPages > 1" class="sidebar-pagination">
          <button 
            :disabled="currentPage === 1" 
            @click="currentPage--" 
            class="pagination-btn"
            title="Sebelumnya"
          >
            &lt;
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
            &gt;
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
          <h2 class="welcome-title">Chatroom</h2>
          <p class="welcome-subtitle">Pilih percakapan member dari daftar di sebelah kiri untuk mulai membalas pertanyaan.</p>
        </div>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
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

// Computed property for global search results
const searchResults = computed(() => {
  const q = searchQuery.value.trim().toLowerCase();
  if (q === '') {
    return {
      chats: [],
      messages: []
    };
  }

  const matchingChats = [];
  const matchingMessages = [];

  props.conversations.forEach(c => {
    // Check if name matches
    const nameMatches = c.submitter.name.toLowerCase().includes(q);
    if (nameMatches) {
      matchingChats.push(c);
    }

    // Check all messages
    c.messages.forEach(m => {
      if (m.content.toLowerCase().includes(q)) {
        matchingMessages.push({
          conversationId: c.id,
          submitter: c.submitter,
          message: m
        });
      }
    });
  });

  return {
    chats: matchingChats,
    messages: matchingMessages
  };
});

// Safe highlight parser
function getHighlightedParts(text, query) {
  if (!text) return [];
  if (!query) return [{ text, isMatch: false }];
  const parts = [];
  const lowerText = text.toLowerCase();
  const lowerQuery = query.toLowerCase();
  let index = 0;
  
  while (true) {
    const matchIndex = lowerText.indexOf(lowerQuery, index);
    if (matchIndex === -1) {
      parts.push({ text: text.substring(index), isMatch: false });
      break;
    }
    
    if (matchIndex > index) {
      parts.push({ text: text.substring(index, matchIndex), isMatch: false });
    }
    
    parts.push({ text: text.substring(matchIndex, matchIndex + query.length), isMatch: true });
    index = matchIndex + query.length;
  }
  
  return parts;
}

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

function openChat(id, msgId = null) {
  if (msgId) {
    router.visit(route('petugas.pertanyaan.show', id) + `?msg=${msgId}`);
  } else {
    router.visit(route('petugas.pertanyaan.show', id));
  }
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

// Add animations on mount
onMounted(() => {
  // Animations removed for better performance when switching between chat rooms
});
</script>

<style scoped>
.chat-container-layout {
  display: flex;
  width: 100%;
  height: 100vh;
  background: #f8fafc;
  overflow: hidden;
}

/* Disable all animations inside chat container */
.chat-container-layout * {
  animation: none !important;
  transition-duration: 0.15s !important;
}

/* Sidebar styling */
.chat-sidebar {
  width: 320px;
  min-width: 220px;
  max-width: 360px;
  background: #ffffff;
  border-right: 1px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}

.sidebar-header {
  padding: 14px 18px 10px;
}

.sidebar-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
}

/* Search Box */
.search-container {
  padding: 0 16px 12px;
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
  padding: 8px 12px 8px 38px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 13px;
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
  gap: 6px;
  padding: 0 16px 12px;
  border-bottom: 1px solid #f1f5f9;
}

.filter-tab {
  padding: 5px 13px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  color: #64748b;
  background: #f1f5f9;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
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
  font-size: 11px;
  font-weight: 700;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
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
  gap: 10px;
  padding: 12px 16px;
  cursor: pointer;
  border-bottom: 1px solid #f1f5f9;
  transition: background 0.15s ease;
}

.chat-item:hover {
  background: #f8fafc;
}

/* Avatar styling */
.chat-avatar-container {
  width: 40px;
  height: 40px;
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

/* Search results styles */
.search-section {
  margin-bottom: 24px;
}

.search-section-title {
  padding: 12px 24px 6px;
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  letter-spacing: 1.5px;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
}

.search-highlight {
  background-color: #fef08a;
  color: #1e293b;
  font-weight: 700;
  border-radius: 2px;
  padding: 0 2px;
}

/* ── Responsive (HP & tablet kecil < 1024px) ── */
@media (max-width: 1024px) {
  /* Full height minus mobile topbar */
  .chat-container-layout {
    height: calc(100vh - 52px);
  }

  /* Chat list takes full width */
  .chat-sidebar {
    width: 100%;
    border-right: none;
  }

  /* Hide welcome panel on mobile — user goes to Show.vue when selecting a chat */
  .chat-welcome-panel {
    display: none;
  }

  /* Compact sidebar header */
  .sidebar-header {
    padding: 10px 16px;
  }
  .sidebar-title {
    font-size: 16px;
  }

  /* Compact search */
  .search-container {
    padding: 8px 12px;
  }

  /* Compact filter tabs */
  .filter-tabs {
    padding: 4px 12px 8px;
    gap: 6px;
  }
  .filter-tab {
    font-size: 12.5px;
    padding: 5px 12px;
  }

  /* Slightly tighter chat items */
  .chat-item {
    padding: 10px 14px;
  }
  .chat-item-name {
    font-size: 14px;
  }
  .chat-item-preview {
    font-size: 12.5px;
  }
}
</style>
