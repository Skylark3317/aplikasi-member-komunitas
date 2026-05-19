<template>
  <PetugasLayout>
    <Head :title="`Pertanyaan #${conversation.ticket_number} - AMK`" />

    <div class="top-bar">
      <div class="page-header">
        <Link :href="route('petugas.pertanyaan.index')" class="back-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </Link>
        <h1 class="page-title">Pertanyaan #{{ conversation.ticket_number }}</h1>
      </div>
      <button 
        v-if="!conversation.is_closed" 
        @click="closeConversation" 
        class="btn-close-ticket"
      >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 6px;">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Selesaikan Pertanyaan
      </button>
    </div>
    <div class="divider" />

    <div class="content-area">
      <div class="chat-wrapper">
        <div class="chat-messages" ref="scrollContainer">
          <!-- Date Separator (Grouped by date if possible, but showing the one from screenshot) -->
          <div class="date-sep">{{ formatDateOnly(conversation.created_at) }}</div>

          <div v-for="msg in conversation.messages" :key="msg.id" :class="['chat-bubble-row', msg.sender_id === currentUser.id ? 'row-right' : 'row-left']">
            <div class="msg-meta">
              <span class="msg-author">{{ msg.sender_id === currentUser.id ? 'Anda' : (msg.sender?.name || 'User') }}</span>
              <span class="msg-dot">•</span>
              <span class="msg-time">{{ formatTimeOnly(msg.created_at) }}</span>
            </div>
            <div :class="['msg-content', msg.sender_id === currentUser.id ? 'content-bubble' : 'content-plain']">
              {{ msg.content }}
            </div>
          </div>
        </div>

        <div v-if="!conversation.is_closed" class="chat-footer">
          <form @submit.prevent="submitReply" class="input-form">
            <input 
              v-model="form.content" 
              type="text" 
              placeholder="Tulis Jawaban" 
              class="chat-input"
              required
            />
            <button type="submit" class="btn-send" :disabled="form.processing || !form.content.trim()">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
              </svg>
              Kirim
            </button>
          </form>
        </div>
        <div v-else class="chat-closed">
          Pertanyaan ini telah selesai.
        </div>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, nextTick } from 'vue';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';

const props = defineProps({
  conversation: Object,
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const scrollContainer = ref(null);

const form = useForm({
  content: '',
});

function scrollToBottom() {
  nextTick(() => {
    if (scrollContainer.value) {
      scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
    }
  });
}

function submitReply() {
  form.post(route('petugas.pertanyaan.reply', props.conversation.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('content');
      scrollToBottom();
    }
  });
}

function formatDateOnly(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function formatTimeOnly(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
}

function closeConversation() {
  router.post(route('petugas.pertanyaan.close', props.conversation.id), {}, {
    preserveScroll: true
  });
}

onMounted(scrollToBottom);
</script>

<style scoped>
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}

.btn-close-ticket {
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
  background: #10b981;
  color: #fff;
  border-color: #10b981;
}
.btn-close-ticket:hover { filter: brightness(0.9); }
.page-header { display: flex; align-items: center; gap: 12px; }

.back-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #111;
  text-decoration: none;
  width: 32px;
  height: 32px;
  border-radius: 4px;
  transition: background 0.2s;
}
.back-btn:hover { background: #f3f4f6; }

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
  margin: 0;
}
.divider { height: 1px; background: #e5e7eb; }

.content-area { 
  padding: 0; 
  background: #fff;
  min-height: calc(100vh - 65px);
  display: flex;
  flex-direction: column;
}

.chat-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  max-width: 900px;
  width: 100%;
  margin: 0 auto;
  position: relative;
}

.chat-messages {
  flex: 1;
  padding: 40px 32px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 32px;
}

.date-sep {
  text-align: center;
  font-size: 13px;
  color: #9ca3af;
  margin-bottom: 20px;
}

.chat-bubble-row {
  display: flex;
  flex-direction: column;
  max-width: 70%;
}

.row-left { align-self: flex-start; }
.row-right { align-self: flex-end; align-items: flex-end; }

.msg-meta {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 8px;
  font-size: 13.5px;
}
.msg-author { font-weight: 600; color: #111; }
.msg-dot { color: #d1d5db; }
.msg-time { color: #9ca3af; }

.msg-content {
  font-size: 14.5px;
  line-height: 1.6;
  color: #4b5563;
}

.content-bubble {
  background: #f3f4f6;
  padding: 16px 24px;
  border-radius: 12px;
  color: #111;
}

.content-plain {
  padding: 0;
}

.chat-footer {
  padding: 24px 32px 40px;
  background: #fff;
}

.input-form {
  position: relative;
  display: flex;
  align-items: center;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 6px 6px 6px 20px;
}

.chat-input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 14px;
  color: #111;
  background: transparent;
  padding: 10px 0;
}

.btn-send {
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
.btn-send:hover:not(:disabled) { filter: brightness(0.9); }
.btn-send:disabled { opacity: 0.6; cursor: not-allowed; }

.chat-closed {
  padding: 24px;
  text-align: center;
  color: #9ca3af;
  font-size: 14px;
}
</style>
