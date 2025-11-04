<template>
  <!-- Modal Overlay -->
  <div
    v-if="isOpen"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
    @click.self="closeModal"
  >
    <!-- Modal Container -->
    <div class="bg-[#162936] rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-[#3b5265]">
      <!-- Modal Header -->
      <div class="sticky top-0 bg-[#051824] border-b border-[#3b5265] px-6 py-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">
          {{ modalTitle }}
        </h2>
        <button
          @click="closeModal"
          class="text-gray-400 hover:text-white transition-colors"
          aria-label="Close modal"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <!-- View Mode -->
        <div v-if="mode === 'view'" class="space-y-4">
          <div v-for="field in fields" :key="field.name" class="border-b border-[#3b5265] pb-4 last:border-b-0">
            <p class="text-gray-400 text-sm font-semibold mb-1">{{ field.label }}</p>
            <p class="text-white text-base">{{ getFieldValue(field.name) || 'N/A' }}</p>
          </div>
        </div>

        <!-- Form Mode (Create/Edit) -->
        <CrudForm
          v-else
          :fields="fields"
          :form-data="formData"
          :errors="errors"
          :is-loading="isLoading"
          @update:form-data="formData = $event"
          @submit="submitForm"
        />
      </div>

      <!-- Modal Footer -->
      <div class="sticky bottom-0 bg-[#051824] border-t border-[#3b5265] px-6 py-4 flex items-center justify-end gap-3">
        <Button
          variant="secondary"
          @click="closeModal"
          :disabled="isLoading"
        >
          {{ mode === 'view' ? 'Close' : 'Cancel' }}
        </Button>

        <Button
          v-if="mode === 'view'"
          variant="primary"
          @click="mode = 'edit'"
        >
          Edit
        </Button>

        <Button
          v-else
          variant="primary"
          :is-loading="isLoading"
          @click="submitForm"
        >
          {{ mode === 'create' ? 'Create' : 'Update' }}
        </Button>

        <Button
          v-if="mode === 'view'"
          variant="danger"
          @click="deleteItem"
          :is-loading="isLoading"
        >
          Delete
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import Button from '../ui/Button.vue';
import CrudForm from './CrudForm.vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  mode: {
    type: String,
    default: 'create',
    validator: (value) => ['create', 'edit', 'view'].includes(value),
  },
  fields: {
    type: Array,
    required: true,
  },
  initialData: {
    type: Object,
    default: () => ({}),
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['close', 'submit', 'delete']);

const formData = ref({});

const modalTitle = computed(() => {
  const titles = {
    create: 'Create New Item',
    edit: 'Edit Item',
    view: 'View Item',
  };
  return titles[props.mode];
});

watch(
  () => props.isOpen,
  (newVal) => {
    if (newVal) {
      // Initialize form data from initialData or empty object
      formData.value = { ...props.initialData };
    }
  },
  { immediate: true }
);

const closeModal = () => {
  emit('close');
};

const submitForm = () => {
  emit('submit', formData.value);
};

const deleteItem = () => {
  if (confirm('Are you sure you want to delete this item?')) {
    emit('delete', props.initialData);
  }
};

const getFieldValue = (fieldName) => {
  return props.initialData[fieldName];
};
</script>

