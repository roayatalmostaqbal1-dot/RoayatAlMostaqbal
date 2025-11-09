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
          'bg-[#27e9b5] bg-opacity-20 text-[#000]',
        ]"
      >
        <component :is="iconComponent" class="w-6 h-6" />
      </div>
    </div>
  </Card>
</template>

<script setup>
import { computed, h } from 'vue';
import Card from '../ui/Card.vue';

const props = defineProps({
  title: String,
  value: String,
  icon: String,
  trend: String,
  trendUp: Boolean,
});

// Simple inline SVG icons using render functions (no runtime compilation needed)
const IconUsers = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' })
  ])
};

const IconRoles = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z' })
  ])
};

const IconPermissions = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.72-7 8.77V12H5V6.3l7-3.11v8.8z' })
  ])
};

const IconActivity = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z' }),
    h('polyline', { points: '13 2 13 9 20 9', style: 'fill:none;stroke:currentColor;stroke-width:2' })
  ])
};

const IconTrendingUp = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('polyline', { points: '23 6 13.5 15.5 8.5 10.5 1 17', style: 'fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round' }),
    h('polyline', { points: '17 6 23 6 23 12', style: 'fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round' })
  ])
};

const IconTarget = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('circle', { cx: '12', cy: '12', r: '1' }),
    h('circle', { cx: '12', cy: '12', r: '5', style: 'fill:none;stroke:currentColor;stroke-width:2' }),
    h('circle', { cx: '12', cy: '12', r: '9', style: 'fill:none;stroke:currentColor;stroke-width:2' })
  ])
};

const iconComponent = computed(() => {
  const icons = {
    users: IconUsers,
    roles: IconRoles,
    permissions: IconPermissions,
    activity: IconActivity,
    'trending-up': IconTrendingUp,
    target: IconTarget,
  };
  return icons[props.icon] || IconUsers;
});
</script>

