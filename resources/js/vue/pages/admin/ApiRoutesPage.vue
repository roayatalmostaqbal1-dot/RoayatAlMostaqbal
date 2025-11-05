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
              @click="syncRoutes"
              :disabled="isSyncing"
            >
              {{ isSyncing ? 'Syncing...' : '🔄 Sync Routes' }}
            </Button>
            <Button
              variant="primary"
              size="sm"
              @click="crud.openCreateModal"
              :disabled="crud.isLoading.value"
            >
              + Add Route
            </Button>
          </div>
        </div>
      </template>

      <!-- Error Message -->
      <div v-if="crud.errors.general" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ crud.errors.general }}
      </div>

      <!-- Sync Message -->
      <div v-if="syncMessage" class="mb-4 p-4 rounded-lg" :class="syncMessageType === 'success' ? 'bg-green-500 bg-opacity-10 border border-green-500 text-green-400' : 'bg-red-500 bg-opacity-10 border border-red-500 text-red-400'">
        {{ syncMessage }}
      </div>

      <!-- Loading State -->
      <div v-if="crud.isLoading.value && crud.items.value.length === 0" class="text-center py-8">
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
            <tr v-for="route in crud.items.value" :key="route.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
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
                  @click="crud.openEditModal(route)"
                  :disabled="crud.isLoading.value"
                >
                  Edit
                </Button>
                <Button
                  variant="danger"
                  size="sm"
                  @click="handleDelete(route)"
                  :disabled="crud.isLoading.value"
                >
                  Delete
                </Button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Empty State -->
        <div v-if="crud.items.value.length === 0" class="text-center py-8">
          <p class="text-gray-400">No API routes found</p>
        </div>
      </div>
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="crud.isModalOpen.value"
      :mode="crud.modalMode.value"
      :fields="routeFields"
      :initial-data="crud.selectedItem.value || {}"
      :is-loading="crud.isLoading.value"
      :errors="crud.errors"
      @close="crud.closeModal"
      @submit="crud.handleSubmit"
      @delete="handleDelete"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useCrud } from '../../composables/useCrud';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import CrudModal from '../../components/crud/CrudModal.vue';

const crud = useCrud('/SuperAdmin/api-routes');
const isSyncing = ref(false);
const syncMessage = ref('');
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

onMounted(() => {
  crud.fetchItems();
  loadPermissions();
});

const loadPermissions = async () => {
  try {
    const response = await fetch('/SuperAdmin/role-permissions/all', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    });
    const data = await response.json();

    // Flatten permissions from grouped data
    const permissionOptions = [];
    Object.values(data.data).forEach(group => {
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
  } catch (error) {
    console.error('Error loading permissions:', error);
  }
};

const syncRoutes = async () => {
  isSyncing.value = true;
  syncMessage.value = '';

  try {
    const response = await fetch('/SuperAdmin/sync-routes', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    });

    const data = await response.json();

    if (response.ok) {
      syncMessage.value = data.message || 'Routes synced successfully!';
      syncMessageType.value = 'success';
      await crud.fetchItems();
    } else {
      syncMessage.value = data.message || 'Error syncing routes';
      syncMessageType.value = 'error';
    }
  } catch (error) {
    syncMessage.value = 'Error syncing routes: ' + error.message;
    syncMessageType.value = 'error';
  } finally {
    isSyncing.value = false;
    setTimeout(() => {
      syncMessage.value = '';
    }, 5000);
  }
};

const handleDelete = async (item) => {
  if (confirm(`Are you sure you want to delete the route "${item.route_name}"?`)) {
    await crud.deleteItem(item.id);
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

