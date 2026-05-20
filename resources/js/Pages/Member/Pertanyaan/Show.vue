<template>
  <MemberLayout>
    <Head :title="`Pertanyaan #${conversation.id} - AMK`" />

    <!-- Top Bar -->
    <div class="top-bar">
      <div class="top-left">
        <Link :href="route('member.pertanyaan.index')" class="back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="back-icon">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </Link>
        <h1 class="page-title">Pertanyaan #{{ conversation.id }}</h1>
      </div>

      <!-- Right action button (Image 2) -->
      <div v-if="!conversation.is_closed" class="top-right-actions">
        <button @click="markAsResolved" class="btn-tandai-selesai">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="check-icon">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="9 11 12 14 22 4"/>
          </svg>
          <span>Tandai sebagai selesai</span>
        </button>
      </div>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Closed Alert Banner (Image 3) -->
      <div v-if="conversation.is_closed && showClosedAlert" class="closed-alert-banner">
        <span>Pertanyaan ini telah ditandai sebagai selesai.</span>
        <button @click="showClosedAlert = false" class="btn-close-alert">×</button>
      </div>

      <!-- Messages Stream -->
      <div class="messages-stream" ref="chatScrollContainer">
        <div v-for="(group, dateStr) in groupedMessages" :key="dateStr" class="date-group-wrapper">
          <!-- Date Separator in center -->
          <div class="date-separator">{{ dateStr }}</div>

          <!-- Messages in that day -->
          <div class="messages-list">
            <div 
              v-for="msg in group" 
              :key="msg.id" 
              :class="['message-bubble-row', msg.sender_id === $page.props.auth.user.id ? 'msg-member' : 'msg-staff']"
            >
              <!-- Member bubble (Image 2 - light grey box on right) -->
              <div v-if="msg.sender_id === $page.props.auth.user.id" class="member-bubble-box">
                <div class="bubble-header">
                  <span>Saya</span>
                  <span class="bullet">•</span>
                  <span>{{ formatTimeOnly(msg.created_at) }}</span>
                </div>
                <div class="bubble-content">{{ msg.content }}</div>
              </div>

              <!-- Staff bubble (Image 2 - plain text on left, no box) -->
              <div v-else class="staff-plain-box">
                <div class="plain-header">
                  <span class="staff-name">{{ msg.sender ? msg.sender.name : 'Petugas' }}</span>
                  <span class="bullet">•</span>
                  <span class="staff-time">{{ formatTimeOnly(msg.created_at) }}</span>
                </div>
                <div class="plain-content">{{ msg.content }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Chat Bar (only visible when not closed) -->
      <div v-if="!conversation.is_closed" class="chat-input-bar-container">
        <form @submit.prevent="submit" class="pill-chat-input-box">
          <textarea 
            v-model="form.content" 
            placeholder="Tulis Pertanyaan..."
            rows="1"
            class="chat-textarea"
            @keydown.enter.prevent="submitOnEnter"
          ></textarea>

          <button 
            type="submit" 
            :disabled="form.processing || !form.content.trim()"
            class="btn-send-chat"
          >
            <!-- Send Icon -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="send-icon-sm">
              <line x1="22" y1="2" x2="11" y2="13"/>
              <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            <span>Kirim</span>
          </button>
        </form>
      </div>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  conversation: Object,
});

const form = useForm({
  content: '',
});

const chatScrollContainer = ref(null);
const showClosedAlert = ref(true);

function scrollToBottom() {
  nextTick(() => {
    if (chatScrollContainer.value) {
      chatScrollContainer.value.scrollTop = chatScrollContainer.value.scrollHeight;
    }
  });
}

onMounted(() => {
  scrollToBottom();
});

// Group messages by Indonesian formatted date (e.g. "8 April 2026")
const groupedMessages = computed(() => {
  const groups = {};
  if (!props.conversation.messages) return groups;

  // Sorting oldest first
  const sorted = [...props.conversation.messages].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

  sorted.forEach(msg => {
    const d = new Date(msg.created_at);
    const dateStr = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    if (!groups[dateStr]) {
      groups[dateStr] = [];
    }
    groups[dateStr].push(msg);
  });

  return groups;
});

function formatTimeOnly(dateStr) {
  const date = new Date(dateStr);
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  return `${hours}:${minutes}`;
}

function submit() {
  if (!form.content.trim()) return;

  form.post(route('member.pertanyaan.reply', props.conversation.id), {
    onSuccess: () => {
      form.reset();
      scrollToBottom();
    }
  });
}

function submitOnEnter(e) {
  if (!e.shiftKey) {
    submit();
  }
}

function markAsResolved() {
  if (confirm('Apakah Anda yakin ingin menandai pertanyaan ini sebagai selesai?')) {
    router.post(route('member.pertanyaan.close', props.conversation.id), {}, {
      onSuccess: () => {
        scrollToBottom();
      }
    });
  }
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
.top-left {
  display: flex;
  align-items: center;
  gap: 16px;
}
.back-link {
  color: #111;
  display: flex;
  align-items: center;
  padding: 4px;
}
.back-icon { 
  width: 18px; 
  height: 18px; 
}

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}

/* Tandai sebagai selesai button */
.btn-tandai-selesai {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s ease;
}

.btn-tandai-selesai:hover {
  background: #0056b3;
}

.check-icon {
  width: 14px;
  height: 14px;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content Area */
.content-area {
  background: #f9fafb;
  min-height: calc(100vh - 65px);
  position: relative;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
}

/* Closed Alert Banner */
.closed-alert-banner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #def7ec;
  border: 1px solid #bbf7d0;
  color: #03543f;
  padding: 12px 24px;
  margin: 24px 32px 0;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
}

.btn-close-alert {
  background: transparent;
  border: none;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  color: #03543f;
  line-height: 1;
  padding: 0;
}

/* Messages Stream */
.messages-stream {
  flex-grow: 1;
  overflow-y: auto;
  padding: 32px 32px 100px;
  display: flex;
  flex-direction: column;
  gap: 32px;
}

.date-group-wrapper {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.date-separator {
  text-align: center;
  font-size: 12.5px;
  color: #6b7280;
  font-weight: 600;
  margin: 10px 0;
}

.messages-list {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.message-bubble-row {
  display: flex;
  width: 100%;
}

.msg-member {
  justify-content: flex-end;
}

.msg-staff {
  justify-content: flex-start;
}

/* Member bubble (right side card box) */
.member-bubble-box {
  max-width: 60%;
  background: #f3f4f6;
  border-radius: 12px;
  padding: 14px 20px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.bubble-header {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11.5px;
  color: #9ca3af;
  font-weight: 600;
}

.bullet {
  font-weight: bold;
}

.bubble-content {
  font-size: 14.5px;
  color: #1f2937;
  line-height: 1.5;
  word-break: break-word;
  white-space: pre-wrap;
}

/* Staff plain box (left side plain text) */
.staff-plain-box {
  max-width: 60%;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.plain-header {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11.5px;
  color: #9ca3af;
  font-weight: 600;
}

.staff-name {
  color: #1f2937;
  font-weight: 700;
}

.plain-content {
  font-size: 14.5px;
  color: #1f2937;
  line-height: 1.5;
  word-break: break-word;
  white-space: pre-wrap;
}

/* Bottom Chat Bar */
.chat-input-bar-container {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 24px 32px 32px;
  display: flex;
  justify-content: center;
  background: linear-gradient(to top, #f9fafb 80%, rgba(249, 250, 251, 0));
  box-sizing: border-box;
}

.pill-chat-input-box {
  width: 100%;
  max-width: 760px;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 20px;
  padding: 8px 16px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}

.chat-textarea {
  flex-grow: 1;
  border: none;
  background: transparent;
  font-size: 14px;
  font-family: inherit;
  resize: none;
  outline: none;
  color: #111827;
  padding: 8px 0;
  max-height: 80px;
  box-sizing: border-box;
}

.chat-textarea::placeholder {
  color: #9ca3af;
}

.btn-send-chat {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s ease;
  flex-shrink: 0;
}

.btn-send-chat:hover:not(:disabled) {
  background: #0056b3;
}

.btn-send-chat:disabled {
  background: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
}

.send-icon-sm {
  width: 14px;
  height: 14px;
}
</style>
