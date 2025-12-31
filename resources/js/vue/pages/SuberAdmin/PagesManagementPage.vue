<template>
  <DashboardLayout page-title="Pages Management" page-description="Manage page access control for roles">
    <Card>
      <template #header>
        <h2 class="text-lg font-bold text-white">Pages & Role Access</h2>
      </template>

      <!-- Error Message -->
      <div v-if="pagesStore.error" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ pagesStore.error }}
        <button @click="pagesStore.clearError()" class="ml-2 text-red-300 hover:text-red-200">✕</button>
      </div>

      <!-- Loading State -->
      <div v-if="pagesStore.isLoading && pagesStore.pages.length === 0" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-2">Loading pages...</p>
      </div>

      <!-- Pages List -->
      <div v-else class="space-y-4">
        <div v-for="page in pagesStore.pages" :key="page.key" class="p-4 rounded-lg bg-[#162936] border border-[#3b5265] hover:border-[#27e9b5] transition">
          <!-- Page Header -->
          <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
              <h3 class="text-white font-semibold">{{ page.name }}</h3>
              <p v-if="page.description" class="text-gray-400 text-sm mt-1">{{ page.description }}</p>
              <p class="text-gray-500 text-xs mt-1">Key: <code class="bg-[#051824] px-2 py-1 rounded">{{ page.key }}</code></p>
            </div>
            <span class="text-[#27e9b5] text-sm font-medium">{{ page.roles_count || 0 }} role(s)</span>
          </div>

          <!-- Role Checkboxes -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 pt-3 border-t border-[#3b5265]">
            <label v-for="role in roles" :key="role.id" class="flex items-center gap-2 cursor-pointer group">
              <input
                type="checkbox"
                :checked="isPageAssignedToRole(page.key, role.id)"
                @change="togglePageForRole(page.key, role.id)"
                :disabled="pagesStore.isLoading"
                class="w-4 h-4 rounded bg-[#051824] border border-[#3b5265] checked:bg-[#27e9b5] checked:border-[#27e9b5] cursor-pointer disabled:opacity-50"
              />
              <span class="text-gray-300 group-hover:text-white transition">{{ role.name }}</span>
            </label>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="pagesStore.pages.length === 0" class="text-center py-8 text-gray-400">
          <p>No pages found</p>
        </div>
      </div>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePagesStore } from '@/vue/stores/SuberAdmin/pagesStore';
import apiClient from '@/vue/services/api';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import Card from '@/vue/components/ui/Card.vue';

const pagesStore = usePagesStore();
const roles = ref([]);

/**
 * Check if a page is assigned to a role
 */
const isPageAssignedToRole = (pageKey, roleId) => {
  const page = pagesStore.pages.find(p => p.key === pageKey);
  return page?.roles?.some(r => r.id === roleId) || false;
};

/**
 * Toggle page assignment for a role
 */
const togglePageForRole = async (pageKey, roleId) => {
  const isAssigned = isPageAssignedToRole(pageKey, roleId);

  if (isAssigned) {
    // Remove page from role
    await pagesStore.removePageFromRole(roleId, pageKey);
  } else {
    // Get all pages currently assigned to this role
    const currentPages = pagesStore.pages
      .filter(p => p.roles?.some(r => r.id === roleId))
      .map(p => p.key);

    // Add the new page
    const newPageKeys = [...currentPages, pageKey];

    // Assign all pages to the role
    await pagesStore.assignPagesToRole(roleId, newPageKeys);
  }
};

/**
 * Fetch roles from API
 */
const fetchRoles = async () => {
  try {
    const response = await apiClient.get('/SuperAdmin/roles', {
      params: { per_page: 100 },
    });
    roles.value = response.data.data;
  } catch (err) {
    console.error('Error fetching roles:', err);
  }
};

/**
 * Initialize component
 */
onMounted(async () => {
  await pagesStore.fetchPages();
  await fetchRoles();
});
</script>

