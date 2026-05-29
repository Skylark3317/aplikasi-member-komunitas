<template>
  <PetugasLayout>
    <Head title="Tanya Jawab - AMK" />

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

        <!-- Chat List Scrollable -->
        <div class="chat-list-wrapper">
          <div v-if="filteredConversations.length === 0" class="empty-chats">
            <p>Tidak ada percakapan ditemukan</p>
          </div>

          <div v-else class="chat-list">
            <div 
              v-for="conv in paginatedConversations" 
              :key="conv.id" 
              :class="['chat-item', conv.id === conversation.id ? 'active-chat-item' : '']"
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

      <!-- Right Panel: Active Chat Room -->
      <div class="chat-main-room">
        <!-- Active Chat Header -->
        <div class="chat-room-header">
          <div class="header-avatar-container">
            <img 
              v-if="conversation.submitter.avatar_url" 
              :src="conversation.submitter.avatar_url" 
              alt="Avatar" 
              class="header-avatar-img"
            />
            <div v-else class="header-avatar-placeholder">
              {{ conversation.submitter.name.charAt(0).toUpperCase() }}
            </div>
          </div>
          <div class="header-info">
            <h2 class="header-name">{{ conversation.submitter.name }}</h2>
            <span class="header-sub">Member Premium</span>
          </div>
        </div>

        <!-- Messages scrollable area -->
        <div class="chat-messages-area" ref="scrollContainer">
          <div class="chat-messages-inner">
            <template v-for="(msg, index) in conversation.messages" :key="msg.id">
              <!-- Date Separator if first message of day -->
              <div v-if="shouldShowDateSep(msg, index)" class="date-separator-row">
                <span class="date-separator-bubble">{{ formatDateOnly(msg.created_at) }}</span>
              </div>

              <!-- Message Row -->
              <div :class="['message-bubble-row', msg.sender_id === currentUser.id ? 'msg-outgoing' : 'msg-incoming']">
                <!-- Avatar for incoming messages -->
                <div v-if="msg.sender_id !== currentUser.id" class="bubble-avatar-container">
                  <img 
                    v-if="msg.sender?.avatar_url" 
                    :src="msg.sender.avatar_url" 
                    alt="Avatar" 
                    class="bubble-avatar-img"
                  />
                  <div v-else class="bubble-avatar-placeholder">
                    {{ msg.sender?.name.charAt(0).toUpperCase() }}
                  </div>
                </div>

                <!-- Bubble itself -->
                <div class="bubble-content-box">
                  <!-- Sender Name inside bubble for other people (especially admins/staff) -->
                  <div 
                    v-if="msg.sender_id !== currentUser.id && msg.sender_id !== conversation.submitter_id" 
                    class="bubble-sender-name"
                  >
                    {{ msg.sender?.name }} (Petugas)
                  </div>
                  
                  <div class="bubble-text">
                    {{ msg.content }}
                  </div>

                  <div class="bubble-meta">
                    <span class="bubble-time">{{ formatTimeOnly(msg.created_at) }}</span>
                    <!-- Status checkmark for current user's messages -->
                    <span v-if="msg.sender_id === currentUser.id" class="tick-wrapper">
                      <svg v-if="msg.is_read" class="tick-svg blue-tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
                      </svg>
                      <svg v-else class="tick-svg gray-tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
                      </svg>
                    </span>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-room-footer">
          <form @submit.prevent="submitReply" class="chat-input-form">
            <input 
              v-model="form.content" 
              type="text" 
              placeholder="Tulis jawaban..." 
              class="chat-room-input"
              required
              ref="inputField"
            />
            <button type="submit" class="chat-room-send-btn" :disabled="form.processing || !form.content.trim()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="send-icon-svg">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
              </svg>
              <span>Kirim</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const props = defineProps({
  conversations: {
    type: Array,
    default: () => []
  },
  conversation: Object,
  filters: {
    type: Object,
    default: () => ({})
  }
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const scrollContainer = ref(null);
const searchQuery = ref('');
const activeTab = ref('all');
const inputField = ref(null);

const form = useForm({
  content: '',
});

// Count total unread conversations
const totalUnreadCount = computed(() => {
  return props.conversations.filter(c => c.unread_count > 0).length;
});

// Client side filtering for sidebar list
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

// Pagination state
const currentPage = ref(1);
const itemsPerPage = 5;

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

function submitReply() {
  form.post(route('petugas.pertanyaan.reply', props.conversation.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('content');
      scrollToBottom();
      nextTick(() => {
        if (inputField.value) inputField.value.focus();
      });
    }
  });
}

function scrollToBottom() {
  nextTick(() => {
    if (scrollContainer.value) {
      scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
    }
  });
}

function shouldShowDateSep(msg, index) {
  if (index === 0) return true;
  const prevMsg = props.conversation.messages[index - 1];
  const prevDate = new Date(prevMsg.created_at).toDateString();
  const currDate = new Date(msg.created_at).toDateString();
  return prevDate !== currDate;
}

function formatTime(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  const now = new Date();
  
  if (d.toDateString() === now.toDateString()) {
    return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  }
  
  const yesterday = new Date(now);
  yesterday.setDate(now.getDate() - 1);
  if (d.toDateString() === yesterday.toDateString()) {
    return 'Kemarin';
  }
  
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
}

function formatDateOnly(dateStr) {
  const date = new Date(dateStr);
  const now = new Date();
  if (date.toDateString() === now.toDateString()) {
    return 'HARI INI';
  }
  const yesterday = new Date(now);
  yesterday.setDate(now.getDate() - 1);
  if (date.toDateString() === yesterday.toDateString()) {
    return 'KEMARIN';
  }
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).toUpperCase();
}

function formatTimeOnly(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

// Keep scrolled to bottom on load
onMounted(() => {
  scrollToBottom();
  if (inputField.value) inputField.value.focus();
});

// If the conversation updates, scroll to bottom
watch(() => props.conversation.messages, () => {
  scrollToBottom();
}, { deep: true });
</script>

<style scoped>
.chat-container-layout {
  display: flex;
  width: 100%;
  height: 100vh;
  background: #eae6df; /* WhatsApp web background like */
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

.active-chat-item {
  background: #f1f5f9;
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
  min-width: 0;
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

/* Right Panel: Chat Room */
.chat-main-room {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #efeae2; /* WhatsApp background color */
  position: relative;
}

.chat-room-header {
  height: 64px;
  background: #f0f2f5;
  padding: 10px 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid #e2e8f0;
  z-index: 10;
}

.header-avatar-container {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}

.header-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.header-avatar-placeholder {
  width: 100%;
  height: 100%;
  background: #cbd5e1;
  color: #334155;
  font-weight: 700;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.header-info {
  display: flex;
  flex-direction: column;
}

.header-name {
  font-size: 15px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.header-sub {
  font-size: 12px;
  color: #64748b;
}

/* Chat Messages Scrollable Area */
.chat-messages-area {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23b5c0c9' fill-opacity='0.1'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm1-61c3.148 0 5.7-2.552 5.7-5.7 0-3.148-2.552-5.7-5.7-5.7-3.148 0-5.7 2.552-5.7 5.7 0 3.148 2.552 5.7 5.7 5.7zm56 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z'/%3E%3C/g%3E%3C/svg%3E");
}

.chat-messages-inner {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* Date separator bubble */
.date-separator-row {
  display: flex;
  justify-content: center;
  margin: 16px 0;
}

.date-separator-bubble {
  background: #ffffff;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 11.5px;
  font-weight: 600;
  color: #54656f;
  box-shadow: 0 1px 2px rgba(0,0,0,0.08);
  letter-spacing: 0.5px;
}

/* Message Bubble Rows */
.message-bubble-row {
  display: flex;
  align-items: flex-end;
  gap: 8px;
  max-width: 65%;
}

.msg-incoming {
  align-self: flex-start;
}

.msg-outgoing {
  align-self: flex-end;
  flex-direction: row-reverse;
}

/* Bubble Avatar styling */
.bubble-avatar-container {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
  margin-bottom: 2px;
}

.bubble-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.bubble-avatar-placeholder {
  width: 100%;
  height: 100%;
  background: #cbd5e1;
  color: #475569;
  font-weight: 700;
  font-size: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Bubble Content Styling */
.bubble-content-box {
  background: #ffffff;
  padding: 8px 12px 6px;
  border-radius: 12px;
  box-shadow: 0 1.5px 2px rgba(0, 0, 0, 0.06);
  position: relative;
}

.msg-incoming .bubble-content-box {
  border-bottom-left-radius: 2px;
}

.msg-outgoing .bubble-content-box {
  background: var(--surface-color, #2563eb);
  border-bottom-right-radius: 2px;
}

.msg-outgoing .bubble-text {
  color: #ffffff;
}

.msg-outgoing .bubble-time {
  color: rgba(255, 255, 255, 0.8);
}

.msg-outgoing .tick-svg.blue-tick {
  color: #ffffff;
}

.msg-outgoing .tick-svg.gray-tick {
  color: rgba(255, 255, 255, 0.6);
}

/* Inside-bubble Sender Name (for other Admins) */
.bubble-sender-name {
  font-size: 12px;
  font-weight: 700;
  color: #b25e29; /* WhatsApp brown name color */
  margin-bottom: 4px;
}

.bubble-text {
  font-size: 14.2px;
  color: #111b21;
  line-height: 1.5;
  word-break: break-word;
  white-space: pre-wrap;
}

.bubble-meta {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 4px;
  margin-top: 4px;
  float: right;
  margin-left: 24px;
}

.bubble-time {
  font-size: 11px;
  color: #667781;
}

/* Tick icons */
.tick-wrapper {
  display: flex;
  align-items: center;
}

.tick-svg {
  width: 15px;
  height: 15px;
}

.gray-tick {
  color: #8696a0;
}

.blue-tick {
  color: #53bdeb; /* WhatsApp blue double checkmark color style */
}

/* Footer & Input */
.chat-room-footer {
  background: #f0f2f5;
  padding: 12px 24px;
  display: flex;
  align-items: center;
  border-top: 1px solid #e2e8f0;
}

.chat-input-form {
  display: flex;
  width: 100%;
  gap: 12px;
}

.chat-room-input {
  flex: 1;
  padding: 10px 18px;
  background: #ffffff;
  border: 1px solid #ffffff;
  border-radius: 8px;
  font-size: 14.5px;
  outline: none;
  color: #111b21;
}

.chat-room-input:focus {
  border-color: var(--primary-color, #2563eb);
}

.chat-room-send-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--primary-color, #2563eb);
  color: #ffffff;
  border: none;
  border-radius: 8px;
  padding: 0 18px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s;
}

.chat-room-send-btn:hover:not(:disabled) {
  filter: brightness(0.9);
}

.chat-room-send-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.send-icon-svg {
  width: 16px;
  height: 16px;
}
</style>
