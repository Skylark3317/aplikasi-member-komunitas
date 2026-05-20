<template>
  <MemberLayout>
    <Head title="Buat Pertanyaan Baru - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <div class="top-left">
        <Link :href="route('member.pertanyaan.index')" class="back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="back-icon">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </Link>
        <h1 class="page-title">Buat Pertanyaan Baru</h1>
      </div>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Empty space representing the pending question -->
      <div class="empty-conversation-body">
        <!-- Waiting for first query -->
      </div>

      <!-- Bottom Chat Bar matching mockup -->
      <div class="chat-input-bar-container">
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
import { Head, Link, useForm } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const form = useForm({
  content: '',
});

function submit() {
  if (!form.content.trim()) return;
  form.post(route('member.pertanyaan.store'));
}

function submitOnEnter(e) {
  if (!e.shiftKey) {
    submit();
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

/* Empty Conversation Body */
.empty-conversation-body {
  flex-grow: 1;
}

/* Bottom Chat Input Bar */
.chat-input-bar-container {
  padding: 24px 32px 32px;
  display: flex;
  justify-content: center;
  background: transparent;
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
