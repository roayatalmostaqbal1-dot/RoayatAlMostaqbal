<template>
  <div v-if="isOpen" class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-[#1a2332] rounded-lg shadow-lg max-w-md w-full mx-4">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-[#3b5265]">
        <h2 class="text-lg font-bold text-white">{{ title }}</h2>
        <button
          @click="handleCancel"
          class="text-gray-400 hover:text-white transition"
        >
          ✕
        </button>
      </div>

      <!-- Content -->
      <div class="p-6">
        <p class="text-gray-300 text-base">{{ message }}</p>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 p-6 border-t border-[#3b5265]">
        <button
          @click="handleCancel"
          class="bg-[#3b5265] hover:bg-[#4a6580] text-white px-4 py-2 rounded font-semibold transition"
        >
          {{ cancelButtonText }}
        </button>
        <button
          @click="handleConfirm"
          :class="[
            'px-4 py-2 rounded font-semibold transition',
            buttonType === 'danger'
              ? 'bg-red-600 hover:bg-red-700 text-white'
              : buttonType === 'warning'
              ? 'bg-yellow-600 hover:bg-yellow-700 text-white'
              : 'bg-[#27e9b5] hover:bg-[#1fd4a0] text-[#051824]'
          ]"
        >
          {{ confirmButtonText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Confirm Action',
  },
  message: {
    type: String,
    default: 'Are you sure you want to proceed?',
  },
  confirmButtonText: {
    type: String,
    default: 'Confirm',
  },
  cancelButtonText: {
    type: String,
    default: 'Cancel',
  },
  buttonType: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'danger', 'warning'].includes(value),
  },
});

const emit = defineEmits(['confirm', 'cancel']);

const handleConfirm = () => {
  emit('confirm');
};

const handleCancel = () => {
  emit('cancel');
};
</script>

