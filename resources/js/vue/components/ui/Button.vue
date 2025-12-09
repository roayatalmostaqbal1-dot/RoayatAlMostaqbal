<template>
  <button
    :class="[
      'px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2',
      'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#051824]',
      'disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none',
      variantClasses,
      sizeClasses,
      isLoading && 'opacity-75 cursor-wait',
      !disabled && !isLoading && 'shadow-md hover:shadow-lg'
    ]"
    :disabled="disabled || isLoading"
    :aria-busy="isLoading"
    :aria-disabled="disabled"
    @click="$emit('click')"
  >
    <svg
      v-if="isLoading"
      class="animate-spin h-5 w-5"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      aria-hidden="true"
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
    validator: (value) => ['primary', 'secondary', 'danger', 'ghost', 'warning'].includes(value),
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
    primary: 'bg-[#27e9b5] text-[#051824] hover:bg-[#1fd4a0] active:bg-[#17b88a] focus:ring-[#27e9b5]',
    secondary: 'bg-[#162936] text-white border border-[#3b5265] hover:bg-[#1f3a4a] active:bg-[#162936] focus:ring-[#3b5265]',
    danger: 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:ring-red-500',
    warning: 'bg-yellow-600 text-white hover:bg-yellow-700 active:bg-yellow-800 focus:ring-yellow-500',
    ghost: 'bg-transparent text-white border border-white hover:bg-white hover:text-[#051824] active:bg-gray-200 focus:ring-white',
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

