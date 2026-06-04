<template>
  <MemberLayout>
    <Head title="Tanya Jawab - AMK" />

    <template v-if="!currentUser?.is_premium">
      <!-- Top Bar -->
      <div class="top-bar">
        <h1 class="page-title">Pertanyaan</h1>
      </div>
      <div class="divider" />
  
      <!-- Content Area -->
      <div class="content-area">
        <!-- Non-Premium Alert (Image 1) -->
        <div v-if="!$page.props.currentUser?.is_premium" class="non-premium-alert-container">
          <div class="non-premium-alert">
            <span class="alert-message">Anda perlu menjadi member untuk mengakses fitur ini.</span>
            <button class="alert-close-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="close-icon-svg">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </template>

    <template v-else>
      <div class="chat-container-layout">
        <!-- Chat Main Room -->
        <div class="chat-main-room">
          <!-- Active Chat Header -->
          <div class="chat-room-header">
            <div class="header-avatar-container">
              <!-- Show community support icon / avatar -->
              <div class="header-avatar-placeholder">
                CS
              </div>
            </div>
            <div class="header-info">
              <h2 class="header-name">Layanan Tanya Jawab Petugas</h2>
              <span class="header-sub">Semua petugas kami siap membantu Anda</span>
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
                    <!-- Sender Name inside bubble for staff/admins -->
                    <div 
                      v-if="msg.sender_id !== currentUser.id" 
                      class="bubble-sender-name"
                    >
                      {{ msg.sender?.name || 'Petugas' }}
                    </div>
                    
                    <div class="bubble-text">
                      {{ msg.content }}
                    </div>

                    <div class="bubble-meta">
                      <span class="bubble-time">{{ formatTimeOnly(msg.created_at) }}</span>
                      <!-- Status checkmark for current user's messages (member's messages) -->
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
                placeholder="Tulis pesan ke petugas..." 
                class="chat-room-input"
                required
                ref="inputField"
                :disabled="form.processing"
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
    </template>
  </MemberLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  conversation: Object,
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const scrollContainer = ref(null);
const inputField = ref(null);

const form = useForm({
  content: '',
});

function submitReply() {
  form.post(route('member.pertanyaan.reply', props.conversation.id), {
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

onMounted(() => {
  scrollToBottom();
  if (inputField.value) inputField.value.focus();
});

watch(() => props.conversation.messages, () => {
  scrollToBottom();
}, { deep: true });
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

.chat-container-layout {
  display: flex;
  width: 100%;
  height: 100vh;
  background: #efeae2;
  overflow: hidden;
}

/* Chat Room */
.chat-main-room {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #efeae2;
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

.header-avatar-placeholder {
  width: 100%;
  height: 100%;
  background: var(--primary-color, #2563eb);
  color: #ffffff;
  font-weight: 700;
  font-size: 15px;
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
  color: #000000;
}

.msg-outgoing .bubble-time {
  color: rgba(0, 0, 0, 0.8);
}

.msg-outgoing .tick-svg.blue-tick {
  color: #000000;
}

.msg-outgoing .tick-svg.gray-tick {
  color: rgba(0, 0, 0, 0.6);
}

/* Inside-bubble Sender Name (for Admins) */
.bubble-sender-name {
  font-size: 12px;
  font-weight: 700;
  color: #b25e29;
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
  color: #53bdeb;
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
