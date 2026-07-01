<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div v-if="modelValue" class="confirm-overlay" @click.self="cancel">
        <div class="confirm-dialog" :class="variantClass">
          <!-- Icon -->
          <div class="confirm-icon-wrapper">
            <div class="confirm-icon" :class="`icon-${variant}`">
              <svg v-if="variant === 'danger'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>
              <svg v-else-if="variant === 'warning'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              <svg v-else-if="variant === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
              </svg>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
              </svg>
            </div>
          </div>

          <!-- Content -->
          <div class="confirm-content">
            <h3 class="confirm-title">{{ title }}</h3>
            <p class="confirm-message">{{ message }}</p>
          </div>

          <!-- Actions -->
          <div class="confirm-actions">
            <button 
              type="button" 
              class="confirm-btn btn-cancel" 
              @click="cancel"
              :disabled="loading"
            >
              {{ cancelText }}
            </button>
            <button 
              type="button" 
              class="confirm-btn btn-confirm" 
              :class="`btn-${variant}`"
              @click="confirm"
              :disabled="loading"
            >
              <span v-if="loading" class="spinner"></span>
              {{ loading ? 'Memproses...' : confirmText }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: 'Konfirmasi'
  },
  message: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'danger', // 'danger', 'warning', 'info', 'success'
    validator: (value) => ['danger', 'warning', 'info', 'success'].includes(value)
  },
  confirmText: {
    type: String,
    default: 'Konfirmasi'
  },
  cancelText: {
    type: String,
    default: 'Batal'
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue', 'confirm', 'cancel']);

const variantClass = computed(() => `variant-${props.variant}`);

function confirm() {
  if (!props.loading) {
    emit('confirm');
  }
}

function cancel() {
  if (!props.loading) {
    emit('update:modelValue', false);
    emit('cancel');
  }
}
</script>

<style scoped>
.confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(17, 24, 39, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 20px;
}

.confirm-dialog {
  background: #fff;
  border-radius: 12px;
  max-width: 420px;
  width: 100%;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
  padding: 24px;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    transform: translateY(-20px) scale(0.95);
    opacity: 0;
  }
  to {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

.confirm-icon-wrapper {
  display: flex;
  justify-content: center;
  margin-bottom: 16px;
}

.confirm-icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.confirm-icon svg {
  width: 28px;
  height: 28px;
}

.icon-danger {
  background: #fee2e2;
  color: #dc2626;
}

.icon-warning {
  background: #fef3c7;
  color: #f59e0b;
}

.icon-info {
  background: #dbeafe;
  color: #3b82f6;
}

.icon-success {
  background: #d1fae5;
  color: #10b981;
}

.confirm-content {
  text-align: center;
  margin-bottom: 24px;
}

.confirm-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 8px;
}

.confirm-message {
  font-size: 14px;
  color: #6b7280;
  line-height: 1.6;
  margin: 0;
}

.confirm-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
}

.confirm-btn {
  flex: 1;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  font-family: inherit;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.confirm-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancel {
  background: #f3f4f6;
  color: #374151;
}

.btn-cancel:hover:not(:disabled) {
  background: #e5e7eb;
}

.btn-danger {
  background: #dc2626;
  color: #fff;
}

.btn-danger:hover:not(:disabled) {
  background: #b91c1c;
}

.btn-warning {
  background: #f59e0b;
  color: #fff;
}

.btn-warning:hover:not(:disabled) {
  background: #d97706;
}

.btn-info {
  background: #3b82f6;
  color: #fff;
}

.btn-info:hover:not(:disabled) {
  background: #2563eb;
}

.btn-success {
  background: #10b981;
  color: #fff;
}

.btn-success:hover:not(:disabled) {
  background: #059669;
}

/* Spinner */
.spinner {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Transition */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-active .confirm-dialog,
.modal-fade-leave-active .confirm-dialog {
  transition: all 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-from .confirm-dialog,
.modal-fade-leave-to .confirm-dialog {
  transform: translateY(-20px) scale(0.95);
}

/* Responsive */
@media (max-width: 480px) {
  .confirm-dialog {
    max-width: 100%;
  }
  
  .confirm-actions {
    flex-direction: column-reverse;
  }
  
  .confirm-btn {
    width: 100%;
  }
}
</style>
