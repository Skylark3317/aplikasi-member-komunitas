<template>
  <Teleport to="body">
    <Transition name="alert-fade">
      <div v-if="modelValue" class="alert-overlay" @click.self="close">
        <div class="alert-dialog" :class="`variant-${variant}`">
          <!-- Icon -->
          <div class="alert-icon-wrapper">
            <div class="alert-icon" :class="`icon-${variant}`">
              <svg v-if="variant === 'error'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
              </svg>
              <svg v-else-if="variant === 'warning'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
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
          <div class="alert-content">
            <h3 v-if="title" class="alert-title">{{ title }}</h3>
            <p class="alert-message">{{ message }}</p>
          </div>

          <!-- Close Button -->
          <button 
            type="button" 
            class="alert-btn" 
            :class="`btn-${variant}`"
            @click="close"
          >
            {{ buttonText }}
          </button>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'info', // 'error', 'warning', 'info', 'success'
    validator: (value) => ['error', 'warning', 'info', 'success'].includes(value)
  },
  buttonText: {
    type: String,
    default: 'OK'
  }
});

const emit = defineEmits(['update:modelValue', 'close']);

function close() {
  emit('update:modelValue', false);
  emit('close');
}
</script>

<style scoped>
.alert-overlay {
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

.alert-dialog {
  background: #fff;
  border-radius: 12px;
  max-width: 380px;
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

.alert-icon-wrapper {
  display: flex;
  justify-content: center;
  margin-bottom: 16px;
}

.alert-icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.alert-icon svg {
  width: 28px;
  height: 28px;
}

.icon-error {
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

.alert-content {
  text-align: center;
  margin-bottom: 20px;
}

.alert-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 8px;
}

.alert-message {
  font-size: 14px;
  color: #6b7280;
  line-height: 1.6;
  margin: 0;
}

.alert-btn {
  width: 100%;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  font-family: inherit;
}

.btn-error {
  background: #dc2626;
  color: #fff;
}

.btn-error:hover {
  background: #b91c1c;
}

.btn-warning {
  background: #f59e0b;
  color: #fff;
}

.btn-warning:hover {
  background: #d97706;
}

.btn-info {
  background: #3b82f6;
  color: #fff;
}

.btn-info:hover {
  background: #2563eb;
}

.btn-success {
  background: #10b981;
  color: #fff;
}

.btn-success:hover {
  background: #059669;
}

/* Transition */
.alert-fade-enter-active,
.alert-fade-leave-active {
  transition: opacity 0.2s ease;
}

.alert-fade-enter-active .alert-dialog,
.alert-fade-leave-active .alert-dialog {
  transition: all 0.3s ease;
}

.alert-fade-enter-from,
.alert-fade-leave-to {
  opacity: 0;
}

.alert-fade-enter-from .alert-dialog,
.alert-fade-leave-to .alert-dialog {
  transform: translateY(-20px) scale(0.95);
}

/* Responsive */
@media (max-width: 480px) {
  .alert-dialog {
    max-width: 100%;
  }
}
</style>
