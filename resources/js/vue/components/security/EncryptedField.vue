<template>
  <div class="mb-3">
    <div class="flex items-center justify-between mb-2">
      <label class="text-gray-400 text-sm font-semibold">{{ label }}</label>
      <button
        @click="toggleCopy"
        class="text-xs px-2 py-1 rounded bg-[#27e9b5]/20 text-[#27e9b5] hover:bg-[#27e9b5]/30 transition-colors"
      >
        {{ copied ? 'Copied!' : 'Copy' }}
      </button>
    </div>
    <div class="bg-[#1f3a4a] rounded p-3 border border-[#3b5265]">
      <p class="text-gray-300 text-sm font-mono break-all select-all">
        {{ value || 'N/A' }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  value: {
    type: String,
    default: null,
  },
});

const copied = ref(false);

const toggleCopy = async () => {
  if (!props.value) return;

  try {
    await navigator.clipboard.writeText(props.value);
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 2000);
  } catch (err) {
    console.error('Failed to copy:', err);
  }
};
</script>

