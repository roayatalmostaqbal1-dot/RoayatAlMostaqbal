<template>
  <button
    :class="[
      'px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2',
      'disabled:opacity-50 disabled:cursor-not-allowed',
      variantClasses,
      sizeClasses,
      isLoading && 'opacity-75 cursor-wait'
    ]"
    :disabled="disabled || isLoading"
    @click="$emit('click')"
  >
    <svg
      v-if="isLoading"
      class="animate-spin h-5 w-5"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'ghost'].includes(value),
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  disabled: Boolean,
  isLoading: Boolean,
});

defineEmits(['click']);

const variantClasses = computed(() => {
  const variants = {
    primary: 'bg-[#27e9b5] text-[#051824] hover:bg-[#1fd4a0] active:bg-[#17b88a]',
    secondary: 'bg-[#162936] text-white border border-[#3b5265] hover:bg-[#1f3a4a] active:bg-[#162936]',
    danger: 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800',
    ghost: 'bg-transparent text-white border border-white hover:bg-white hover:text-[#051824] active:bg-gray-200',
  };
  return variants[props.variant];
});

const sizeClasses = computed(() => {
  const sizes = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-6 py-3 text-lg',
  };
  return sizes[props.size];
});
</script>

