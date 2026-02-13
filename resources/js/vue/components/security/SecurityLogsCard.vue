<template>
  <Card>
    <template #header>
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-2">
          <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/>
          </svg>
          <h3 class="text-lg font-bold text-white">Security Logs</h3>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#27e9b5]/20 text-[#27e9b5]">
          {{ totalCount }} logs
        </span>
      </div>
    </template>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-4">
      <Button
        v-for="period in filterPeriods"
        :key="period.value"
        :variant="currentFilter === period.value ? 'primary' : 'secondary'"
        size="sm"
        @click="$emit('filter', period.value)"
      >
        {{ period.label }}
      </Button>

      <div class="flex-1"></div>

      <Button variant="secondary" size="sm" @click="$emit('refresh')">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
        </svg>
      </Button>
    </div>

    <!-- Logs Table -->
    <div v-if="logs && logs.length > 0" class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-[#3b5265]">
            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Timestamp</th>
            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Event Type</th>
            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Source</th>
            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Severity</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="log in logs"
            :key="log.id"
            class="border-b border-[#3b5265] hover:bg-[#1f3a4a] transition-colors"
          >
            <td class="py-3 px-4 text-white text-sm">{{ formatTimestamp(log.created_at) }}</td>
            <td class="py-3 px-4 text-white text-sm capitalize">{{ formatEventType(log.event_type) }}</td>
            <td class="py-3 px-4 text-gray-400 text-sm font-mono">{{ log.source }}</td>
            <td class="py-3 px-4">
              <span :class="getSeverityClass(log.severity)" class="px-2 py-1 rounded text-xs font-semibold">
                {{ log.severity }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="text-center py-8">
      <p class="text-gray-400">No security logs found</p>
    </div>

    <!-- Actions -->
    <template #footer>
      <div class="flex flex-wrap gap-2">
        <Button variant="secondary" @click="$emit('export')">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2v9.67z"/>
          </svg>
          Export CSV
        </Button>
        <Button variant="primary" @click="$emit('generate')">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Generate Sample Logs
        </Button>
      </div>
    </template>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import Card from '@/vue/components/ui/Card.vue';
import Button from '@/vue/components/ui/Button.vue';

const props = defineProps({
  logs: {
    type: Array,
    default: () => [],
  },
  totalCount: {
    type: Number,
    default: 0,
  },
  currentFilter: {
    type: String,
    default: 'today',
  },
});

defineEmits(['refresh', 'filter', 'generate', 'export']);

const filterPeriods = [
  { label: 'Today', value: 'today' },
  { label: 'Week', value: 'week' },
  { label: 'Month', value: 'month' },
];

const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
};

const formatEventType = (eventType) => {
  return eventType.replace(/_/g, ' ');
};

const getSeverityClass = (severity) => {
  const classes = {
    low: 'bg-green-500/20 text-green-400',
    medium: 'bg-yellow-500/20 text-yellow-400',
    high: 'bg-orange-500/20 text-orange-400',
    critical: 'bg-red-500/20 text-red-400',
  };
  return classes[severity] || 'bg-gray-500/20 text-gray-400';
};
</script>
