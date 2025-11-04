<template>
  <Card>
    <div class="flex items-start justify-between">
      <div>
        <p class="text-gray-400 text-sm font-medium mb-2">{{ title }}</p>
        <p class="text-3xl font-bold text-white mb-2">{{ value }}</p>
        <div class="flex items-center gap-1">
          <svg
            v-if="trendUp"
            class="w-4 h-4 text-green-400"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414-1.414L13.586 7H12z" clip-rule="evenodd" />
          </svg>
          <svg
            v-else
            class="w-4 h-4 text-red-400"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path fill-rule="evenodd" d="M12 13a1 1 0 110 2H7a1 1 0 01-1-1V9a1 1 0 112 0v3.586l4.293-4.293a1 1 0 011.414 1.414L9.414 13H12z" clip-rule="evenodd" />
          </svg>
          <span :class="trendUp ? 'text-green-400' : 'text-red-400'" class="text-sm font-semibold">
            {{ trend }}
          </span>
        </div>
      </div>
      <div
        :class="[
          'w-12 h-12 rounded-lg flex items-center justify-center',
          'bg-[#27e9b5] bg-opacity-20 text-[#27e9b5]',
        ]"
      >
        <component :is="iconComponent" class="w-6 h-6" />
      </div>
    </div>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import Card from '../ui/Card.vue';

const props = defineProps({
  title: String,
  value: String,
  icon: String,
  trend: String,
  trendUp: Boolean,
});

// Simple inline SVG icons
const IconUsers = {
  template: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'
};

const IconActivity = {
  template: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9" style="fill:none;stroke:currentColor;stroke-width:2"/></svg>'
};

const IconTrendingUp = {
  template: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9" style="fill:none;stroke:currentColor;stroke-width:2"/></svg>'
};

const IconTarget = {
  template: '<svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="12" r="5" style="fill:none;stroke:currentColor;stroke-width:2"/><circle cx="12" cy="12" r="9" style="fill:none;stroke:currentColor;stroke-width:2"/></svg>'
};

const iconComponent = computed(() => {
  const icons = {
    users: IconUsers,
    activity: IconActivity,
    'trending-up': IconTrendingUp,
    target: IconTarget,
  };
  return icons[props.icon] || IconUsers;
});
</script>

