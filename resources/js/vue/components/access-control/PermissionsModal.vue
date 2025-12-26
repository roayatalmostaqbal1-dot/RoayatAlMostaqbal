<template>
  <div v-if="isOpen" class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-[#162936] rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="sticky top-0 bg-[#051824] border-b border-[#3b5265] px-6 py-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">
          Manage Permissions for <span class="text-[#27e9b5]">{{ role?.name }}</span>
        </h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Content -->
      <div class="p-6">
        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-8">
          <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-400 mt-2">Loading permissions...</p>
        </div>

        <!-- Permissions List -->
        <div v-else class="space-y-6">
          <div v-for="(permissions, group) in groupedPermissions" :key="group" class="space-y-3">
            <h3 class="text-lg font-semibold text-[#27e9b5] capitalize">{{ group }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <label v-for="permission in permissions" :key="permission.id" class="flex items-center space-x-3 p-3 rounded bg-[#051824] hover:bg-[#162936] cursor-pointer transition">
                <input
                  type="checkbox"
                  :value="permission.id"
                  v-model="selectedPermissions"
                  class="w-4 h-4 rounded border-[#3b5265] bg-[#051824] text-[#27e9b5] cursor-pointer"
                >
                <div class="flex-1">
                  <p class="text-white font-mono text-sm">{{ permission.name }}</p>
                  <p class="text-gray-400 text-xs">{{ permission.description }}</p>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-[#051824] border-t border-[#3b5265] px-6 py-4 flex items-center justify-end space-x-3">
        <button
          @click="$emit('close')"
          class="px-4 py-2 rounded text-gray-300 hover:text-white transition"
        >
          Cancel
        </button>
        <button
          @click="handleSave"
          :disabled="isLoading"
          class="px-4 py-2 rounded bg-[#27e9b5] text-[#051824] font-semibold hover:bg-opacity-90 disabled:opacity-50 transition"
        >
          {{ isLoading ? 'Saving...' : 'Save Permissions' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { usePermissionsStore } from '../../stores/SuberAdmin/permissionsStore';

const props = defineProps({
  isOpen: Boolean,
  role: Object,
  isLoading: Boolean,
});

const emit = defineEmits(['close', 'save']);

const permissionsStore = usePermissionsStore();
const selectedPermissions = ref([]);

watch(() => props.isOpen, async (newVal) => {
  if (newVal && props.role) {
    await loadPermissions();
  }
});

const loadPermissions = async () => {
  // Load all permissions
  const result = await permissionsStore.fetchAllPermissions();
  if (!result.success) {
    console.error('Error loading permissions:', result.error);
    return;
  }

  // Load current role permissions
  const roleResult = await permissionsStore.fetchRolePermissions(props.role.id);
  if (roleResult.success && roleResult.data) {
    // Flatten and get permission IDs
    const currentPermissions = [];
    Object.values(roleResult.data).forEach(group => {
      if (Array.isArray(group)) {
        group.forEach(permission => {
          currentPermissions.push(permission.id);
        });
      }
    });
    selectedPermissions.value = currentPermissions;
  }
};

const groupedPermissions = computed(() => {
  return permissionsStore.allPermissions;
});

const handleSave = () => {
  emit('save', selectedPermissions.value);
};
</script>

