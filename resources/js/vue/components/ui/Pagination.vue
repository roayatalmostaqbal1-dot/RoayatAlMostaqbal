<template>
  <div v-if="totalPages > 1" class="flex items-center justify-between mt-6">
    <!-- Page Info -->
    <div class="text-sm text-gray-400">
      Showing {{ startItem }} to {{ endItem }} of {{ total }} results
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center gap-2">
      <!-- Previous Button -->
      <Button
        variant="secondary"
        size="sm"
        @click="$emit('prev')"
        :disabled="!hasPrev || isLoading"
        title="Previous page"
      >
        ← Previous
      </Button>

      <!-- Page Numbers -->
      <div class="flex items-center gap-1">
        <!-- First page -->
        <Button
          v-if="showFirstPage"
          variant="ghost"
          size="sm"
          @click="$emit('goToPage', 1)"
          :disabled="isLoading"
          :class="{ 'bg-[#27e9b5] text-[#051824]': currentPage === 1 }"
        >
          1
        </Button>

        <!-- First ellipsis -->
        <span v-if="showFirstEllipsis" class="text-gray-400 px-2">...</span>

        <!-- Visible page numbers -->
        <Button
          v-for="page in visiblePages"
          :key="page"
          variant="ghost"
          size="sm"
          @click="$emit('goToPage', page)"
          :disabled="isLoading"
          :class="{ 'bg-[#27e9b5] text-[#051824]': currentPage === page }"
        >
          {{ page }}
        </Button>

        <!-- Last ellipsis -->
        <span v-if="showLastEllipsis" class="text-gray-400 px-2">...</span>

        <!-- Last page -->
        <Button
          v-if="showLastPage"
          variant="ghost"
          size="sm"
          @click="$emit('goToPage', totalPages)"
          :disabled="isLoading"
          :class="{ 'bg-[#27e9b5] text-[#051824]': currentPage === totalPages }"
        >
          {{ totalPages }}
        </Button>
      </div>

      <!-- Next Button -->
      <Button
        variant="secondary"
        size="sm"
        @click="$emit('next')"
        :disabled="!hasNext || isLoading"
        title="Next page"
      >
        Next →
      </Button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Button from './Button.vue';

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  },
  total: {
    type: Number,
    required: true,
  },
  perPage: {
    type: Number,
    required: true,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['prev', 'next', 'goToPage']);

// Computed properties
const hasPrev = computed(() => props.currentPage > 1);
const hasNext = computed(() => props.currentPage < props.totalPages);

const startItem = computed(() => {
  return (props.currentPage - 1) * props.perPage + 1;
});

const endItem = computed(() => {
  const end = props.currentPage * props.perPage;
  return end > props.total ? props.total : end;
});

// Pagination logic for visible pages
const visiblePages = computed(() => {
  const delta = 2; // Number of pages to show on each side of current page
  const range = [];
  const rangeWithDots = [];

  for (
    let i = Math.max(2, props.currentPage - delta);
    i <= Math.min(props.totalPages - 1, props.currentPage + delta);
    i++
  ) {
    range.push(i);
  }

  if (props.currentPage - delta > 2) {
    rangeWithDots.push(2, '...');
  } else {
    rangeWithDots.push(2);
  }

  rangeWithDots.push(...range);

  if (props.currentPage + delta < props.totalPages - 1) {
    rangeWithDots.push('...', props.totalPages - 1);
  } else {
    rangeWithDots.push(props.totalPages - 1);
  }

  return range;
});

const showFirstPage = computed(() => props.totalPages > 1);
const showLastPage = computed(() => props.totalPages > 1 && props.totalPages !== props.currentPage);
const showFirstEllipsis = computed(() => props.currentPage > 4);
const showLastEllipsis = computed(() => props.currentPage < props.totalPages - 3);
</script>
