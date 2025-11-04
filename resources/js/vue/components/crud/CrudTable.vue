<template>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-[#3b5265] bg-[#051824]">
          <th
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-3 text-left text-sm font-semibold text-white"
          >
            {{ column.label }}
          </th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-white">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(row, index) in items"
          :key="index"
          class="border-b border-[#3b5265] hover:bg-[#051824] transition-colors"
        >
          <td
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-4 text-sm text-gray-300"
          >
            {{ getColumnValue(row, column) }}
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center gap-2">
              <button
                @click="$emit('view', row)"
                class="px-3 py-1.5 rounded-lg bg-[#27e9b5] text-[#051824] font-semibold hover:bg-[#1fd4a0] transition-colors text-xs"
                aria-label="View item"
              >
                View
              </button>
              <button
                @click="$emit('edit', row)"
                class="px-3 py-1.5 rounded-lg bg-[#162936] text-white border border-[#3b5265] hover:bg-[#1f3a4a] transition-colors text-xs"
                aria-label="Edit item"
              >
                Edit
              </button>
              <button
                @click="$emit('delete', row)"
                class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors text-xs"
                aria-label="Delete item"
              >
                Delete
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="items.length === 0">
          <td :colspan="columns.length + 1" class="px-6 py-8 text-center text-gray-400">
            No items found
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
});

defineEmits(['view', 'edit', 'delete']);

const getColumnValue = (row, column) => {
  if (column.format) {
    return column.format(row[column.key]);
  }
  return row[column.key];
};
</script>

