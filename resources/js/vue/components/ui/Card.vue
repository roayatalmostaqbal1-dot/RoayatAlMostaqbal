<template>
  <div
    :class="[
      'rounded-lg border border-[#3b5265] bg-[#162936] transition-all duration-300',
      'hover:shadow-lg hover:border-[#27e9b5]',
      className,
    ]"
  >
    <div v-if="$slots.header" class="border-b border-[#3b5265] px-6 py-4">
      <slot name="header" />
    </div>
    <div class="px-6 py-4">
      <slot />
    </div>
    <div v-if="$slots.footer" class="border-t border-[#3b5265] px-6 py-4">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  className: String,
});
</script>

