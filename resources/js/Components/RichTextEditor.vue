<template>
  <div class="rich-text-wrapper">
    <QuillEditor
      v-model:content="internalContent"
      :options="options"
      content-type="html"
      @update:content="onUpdate"
      class="quill-editor"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Tulis sesuatu...',
  },
});

const emit = defineEmits(['update:modelValue']);

const internalContent = ref(props.modelValue || '');

const options = {
  theme: 'snow',
  placeholder: props.placeholder,
  bounds: '.rich-text-wrapper',
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ align: [] }],
      ['link', 'image'],
      ['clean'],
    ],
  },
};

// Sync internal content with prop
watch(() => props.modelValue, (newVal) => {
  if (newVal !== internalContent.value) {
    internalContent.value = newVal || '';
  }
});

function onUpdate(content) {
  emit('update:modelValue', content);
}
</script>

<style>
.rich-text-wrapper {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  background: #fff;
}

/* Customize Quill Styles to match your UI */
.ql-toolbar.ql-snow {
  border: none !important;
  border-bottom: 1px solid #d1d5db !important;
  padding: 8px 12px !important;
  background: #f9fafb;
  border-radius: 8px 8px 0 0;
}

.ql-container.ql-snow {
  border: none !important;
  min-height: 300px;
  font-size: 14.5px;
  font-family: 'Inter', sans-serif;
  border-radius: 0 0 8px 8px;
}

/* Ensure tooltip can appear properly */
.ql-tooltip {
  z-index: 50 !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

/* If tooltip is at the very bottom, Quill sets top and left. We can make it flip up using bounds if configured, or just let it overflow */
.ql-editor {
  padding: 16px 20px !important;
  line-height: 1.6;
}

.ql-editor.ql-blank::before {
  color: #9ca3af;
  font-style: normal;
}

.ql-snow .ql-stroke {
  stroke: #4b5563;
}
.ql-snow .ql-fill {
  fill: #4b5563;
}
.ql-snow .ql-picker {
  color: #4b5563;
}
</style>
