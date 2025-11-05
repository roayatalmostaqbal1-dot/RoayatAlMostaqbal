<template>
  <DashboardLayout page-title="API Routes" page-description="Manage API routes and their permissions">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">API Routes</h2>
          <div class="space-x-2">
            <Button
              variant="secondary"
              size="sm"
              @click="handleSyncRoutes"
              :disabled="apiRoutesStore.isSyncing"
            >
              {{ apiRoutesStore.isSyncing ? 'Syncing...' : '🔄 Sync Routes' }}
            </Button>
            <Button
              variant="primary"
              size="sm"
              @click="openCreateModal"
              :disabled="isLoading"
            >
              + Add Route
            </Button>
          </div>
        </div>
      </template>

      <!-- Error Message -->
      <div v-if="errors.general" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ errors.general }}
      </div>

      <!-- Sync Message -->
      <div v-if="apiRoutesStore.syncMessage" class="mb-4 p-4 rounded-lg" :class="syncMessageType === 'success' ? 'bg-green-500 bg-opacity-10 border border-green-500 text-green-400' : 'bg-red-500 bg-opacity-10 border border-red-500 text-red-400'">
        {{ apiRoutesStore.syncMessage }}
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && items.length === 0" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-2">Loading routes...</p>
      </div>

      <!-- Routes Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Route Name</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Path</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Method</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Permission</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Status</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="route in items" :key="route.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
              <td class="py-3 px-4 text-white font-mono text-xs">{{ route.route_name }}</td>
              <td class="py-3 px-4 text-gray-300 font-mono text-xs">{{ route.route_path }}</td>
              <td class="py-3 px-4">
                <span class="inline-block px-2 py-1 rounded text-xs font-semibold" :class="getMethodColor(route.http_method)">
                  {{ route.http_method }}
                </span>
              </td>
              <td class="py-3 px-4 text-gray-300 text-xs">
                <span v-if="route.permission" class="inline-block bg-[#3b5265] text-[#27e9b5] px-2 py-1 rounded">
                  {{ route.permission.name }}
                </span>
                <span v-else class="text-gray-500">—</span>
              </td>
              <td class="py-3 px-4">
                <span v-if="route.is_active" class="inline-block bg-green-500 bg-opacity-20 text-green-400 px-2 py-1 rounded text-xs">
                  Active
                </span>
                <span v-else class="inline-block bg-red-500 bg-opacity-20 text-red-400 px-2 py-1 rounded text-xs">
                  Inactive
                </span>
              </td>
              <td class="py-3 px-4 space-x-2">
                <Button
                  variant="ghost"
                  size="sm"
                  @click="openEditModal(route)"
                  :disabled="isLoading"
                >
                  Edit
                </Button>
                <Button
                  variant="danger"
                  size="sm"
                  @click="handleDelete(route)"
                  :disabled="isLoading"
                >
                  Delete
                </Button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Empty State -->
        <div v-if="items.length === 0" class="text-center py-8">
          <p class="text-gray-400">No API routes found</p>
        </div>
      </div>
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="isCreateModalOpen || isEditModalOpen"
      :mode="modalMode"
      :fields="routeFields"
      :initial-data="selectedRouteForModal || {}"
      :is-loading="isLoading"
      :errors="errors"
      @close="closeModal"
      @submit="handleSubmit"
      @delete="handleDelete"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useApiRoutesStore } from '../../stores/apiRoutesStore';
import { usePermissionsStore } from '../../stores/permissionsStore';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import CrudModal from '../../components/crud/CrudModal.vue';

const apiRoutesStore = useApiRoutesStore();
const permissionsStore = usePermissionsStore();

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const modalMode = ref('create');
const selectedRouteForModal = ref(null);
const syncMessageType = ref('success');

const routeFields = [
  { name: 'route_name', label: 'Route Name', type: 'text', required: true, placeholder: 'e.g., api.users.index' },
  { name: 'route_path', label: 'Route Path', type: 'text', required: true, placeholder: 'e.g., /api/users' },
  {
    name: 'http_method',
    label: 'HTTP Method',
    type: 'select',
    required: true,
    options: [
      { value: 'GET', label: 'GET' },
      { value: 'POST', label: 'POST' },
      { value: 'PUT', label: 'PUT' },
      { value: 'DELETE', label: 'DELETE' },
      { value: 'PATCH', label: 'PATCH' },
    ]
  },
  { name: 'permission_id', label: 'Permission', type: 'select', required: false, options: [] },
  { name: 'description', label: 'Description', type: 'textarea', required: false },
  { name: 'is_active', label: 'Active', type: 'checkbox' },
];

// Computed properties
const items = computed(() => apiRoutesStore.apiRoutesList);
const isLoading = computed(() => apiRoutesStore.isLoading);
const errors = computed(() => ({ general: apiRoutesStore.error }));

onMounted(async () => {
  await apiRoutesStore.fetchApiRoutes();
  await loadPermissions();
});

const loadPermissions = async () => {
  const result = await permissionsStore.fetchAllPermissions();
  if (result.success && permissionsStore.allPermissions) {
    // Flatten permissions from grouped data
    const permissionOptions = [];
    Object.values(permissionsStore.allPermissions).forEach(group => {
      if (Array.isArray(group)) {
        group.forEach(permission => {
          permissionOptions.push({
            value: permission.id,
            label: `${permission.name} (${permission.group})`,
          });
        });
      }
    });

    const permField = routeFields.find(f => f.name === 'permission_id');
    if (permField) {
      permField.options = permissionOptions;
    }
  }
};

const openCreateModal = () => {
  modalMode.value = 'create';
  selectedRouteForModal.value = null;
  isCreateModalOpen.value = true;
};

const openEditModal = (route) => {
  modalMode.value = 'edit';
  selectedRouteForModal.value = { ...route };
  isEditModalOpen.value = true;
};

const closeModal = () => {
  isCreateModalOpen.value = false;
  isEditModalOpen.value = false;
  selectedRouteForModal.value = null;
};

const handleSubmit = async (formData) => {
  if (modalMode.value === 'create') {
    const result = await apiRoutesStore.createApiRoute(formData);
    if (result.success) {
      alert('Route created successfully');
      closeModal();
    } else {
      alert(`Error: ${result.error}`);
    }
  } else {
    const result = await apiRoutesStore.updateApiRoute(selectedRouteForModal.value.id, formData);
    if (result.success) {
      alert('Route updated successfully');
      closeModal();
    } else {
      alert(`Error: ${result.error}`);
    }
  }
};

const handleSyncRoutes = async () => {
  const result = await apiRoutesStore.syncRoutes();
  if (result.success) {
    syncMessageType.value = 'success';
    await apiRoutesStore.fetchApiRoutes();
  } else {
    syncMessageType.value = 'error';
  }
  setTimeout(() => {
    apiRoutesStore.syncMessage = '';
  }, 5000);
};

const handleDelete = async (item) => {
  if (confirm(`Are you sure you want to delete the route "${item.route_name}"?`)) {
    const result = await apiRoutesStore.deleteApiRoute(item.id);
    if (result.success) {
      alert('Route deleted successfully');
    } else {
      alert(`Error: ${result.error}`);
    }
  }
};

const getMethodColor = (method) => {
  const colors = {
    'GET': 'bg-blue-500 bg-opacity-20 text-blue-400',
    'POST': 'bg-green-500 bg-opacity-20 text-green-400',
    'PUT': 'bg-yellow-500 bg-opacity-20 text-yellow-400',
    'DELETE': 'bg-red-500 bg-opacity-20 text-red-400',
    'PATCH': 'bg-purple-500 bg-opacity-20 text-purple-400',
  };
  return colors[method] || 'bg-gray-500 bg-opacity-20 text-gray-400';
};
</script>

