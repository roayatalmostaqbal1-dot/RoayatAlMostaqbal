<template>
  <DashboardLayout page-title="Permissions" page-description="Manage system permissions">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Permissions List</h2>

        </div>
      </template>

      <!-- Error Message -->
      <div v-if="errors.general" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-white">
        {{ errors.general }}
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && items.length === 0" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-2">Loading permissions...</p>
      </div>

      <!-- Permissions Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Name</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Group</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Status</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Roles</th>
              <!-- <th class="text-left py-3 px-4 text-gray-300 font-semibold">Actions</th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="permission in items" :key="permission.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
              <td class="py-3 px-4 text-white font-mono text-sm">{{ permission.name }}</td>
              <td class="py-3 px-4">
                <span class="inline-block bg-[#3b5265] text-[#27e9b5] px-2 py-1 rounded text-xs">
                  {{ permission.group || 'general' }}
                </span>
              </td>
              <td class="py-3 px-4">
                <span v-if="permission.is_seeded" class="inline-block bg-blue-500 bg-opacity-20 text-white px-2 py-1 rounded text-xs border border-blue-500">
                  🔒 Seeded
                </span>
                <span v-else class="inline-block bg-gray-600 bg-opacity-20 text-white px-2 py-1 rounded text-xs border border-gray-600">
                  Custom
                </span>
              </td>
              <td class="py-3 px-4 text-gray-300">
                <span class="text-sm">{{ permission.roles_count || 0 }}</span>
              </td>
              <!-- <td class="py-3 px-4">
                <div class="flex items-center gap-2 flex-wrap">
                  <Button
                    v-if="!permission.is_seeded"
                    variant="secondary"
                    size="sm"
                    @click="openEditModal(permission)"
                    :disabled="isLoading"
                    title="Edit permission details"
                  >
                    ✏️ Edit
                  </Button>
                  <Button
                    v-if="!permission.is_seeded"
                    variant="danger"
                    size="sm"
                    @click="handleDelete(permission)"
                    :disabled="isLoading"
                    title="Delete this permission"
                  >
                    🗑️ Delete
                  </Button>
                  <span v-if="permission.is_seeded" class="text-gray-500 text-xs italic">
                    Read-only
                  </span>
                </div>
              </td> -->
            </tr>
          </tbody>
        </table>

        <!-- Empty State -->
        <div v-if="items.length === 0" class="text-center py-8">
          <p class="text-gray-400">No permissions found</p>
        </div>
      </div>

      <!-- Pagination -->
      <Pagination
        :current-page="permissionsStore.pagination.current_page"
        :total-pages="permissionsStore.pagination.total > 0 ? Math.ceil(permissionsStore.pagination.total / permissionsStore.pagination.per_page) : 1"
        :total="permissionsStore.pagination.total"
        :per-page="permissionsStore.pagination.per_page"
        :is-loading="permissionsStore.isLoading"
        @prev="permissionsStore.fetchPermissions(permissionsStore.pagination.current_page - 1)"
        @next="permissionsStore.fetchPermissions(permissionsStore.pagination.current_page + 1)"
        @go-to-page="permissionsStore.fetchPermissions"
      />
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="isCreateModalOpen || isEditModalOpen"
      :mode="modalMode"
      :fields="permissionFields"
      :initial-data="selectedPermissionForModal || {}"
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
import { usePermissionsStore } from '@/vue/stores/SuberAdmin/permissionsStore';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import Card from '@/vue/components/ui/Card.vue';
import Button from '@/vue/components/ui/Button.vue';
import Pagination from '@/vue/components/ui/Pagination.vue';
import CrudModal from '@/vue/components/crud/CrudModal.vue';

const permissionsStore = usePermissionsStore();

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const modalMode = ref('create');
const selectedPermissionForModal = ref(null);

const permissionFields = [
  { name: 'name', label: 'Permission Name', type: 'text', required: true, placeholder: 'e.g., users.view' },
  {
    name: 'group',
    label: 'Group',
    type: 'select',
    required: false,
    options: [
      { value: 'users', label: 'Users' },
      { value: 'roles', label: 'Roles' },
      { value: 'permissions', label: 'Permissions' },
      { value: 'settings', label: 'Settings' },
      { value: 'dashboard', label: 'Dashboard' },
    ]
  },
];

// Computed properties
const items = computed(() => permissionsStore.permissionsList);
const isLoading = computed(() => permissionsStore.isLoading);
const errors = computed(() => ({ general: permissionsStore.error }));

onMounted(async () => {
  await permissionsStore.fetchPermissions();
});

const openCreateModal = () => {
  modalMode.value = 'create';
  selectedPermissionForModal.value = null;
  isCreateModalOpen.value = true;
};

const openEditModal = (permission) => {
  modalMode.value = 'edit';
  selectedPermissionForModal.value = { ...permission };
  isEditModalOpen.value = true;
};

const closeModal = () => {
  isCreateModalOpen.value = false;
  isEditModalOpen.value = false;
  selectedPermissionForModal.value = null;
};

const handleSubmit = async (formData) => {
  if (modalMode.value === 'create') {
    const result = await permissionsStore.createPermission(formData);
    if (result.success) {
      closeModal();
      // Toast notification is handled in the store
    }
    // Error toast is also handled in the store
  } else {
    const result = await permissionsStore.updatePermission(selectedPermissionForModal.value.id, formData);
    if (result.success) {
      closeModal();
      // Toast notification is handled in the store
    }
    // Error toast is also handled in the store
  }
};

const handleDelete = async (item) => {
  if (confirm(`Are you sure you want to delete the "${item.name}" permission?`)) {
    const result = await permissionsStore.deletePermission(item.id);
    if (result.success) {
      alert('Permission deleted successfully');
    } else {
      alert(`Error: ${result.error}`);
    }
  }
};
</script>

