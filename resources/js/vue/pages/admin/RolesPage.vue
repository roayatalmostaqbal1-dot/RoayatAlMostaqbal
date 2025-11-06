<template>
  <DashboardLayout page-title="Roles" page-description="Manage system roles and permissions">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Roles List</h2>
          <Button
            variant="primary"
            size="sm"
            @click="openCreateModal"
            :disabled="isLoading"
          >
            + Add Role
          </Button>
        </div>
      </template>

      <!-- Error Message -->
      <div v-if="errors.general" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ errors.general }}
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && items.length === 0" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-2">Loading roles...</p>
      </div>

      <!-- Roles Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Name</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Permissions</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Users</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="role in items" :key="role.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
              <td class="py-3 px-4 text-white">{{ role.name }}</td>
              <td class="py-3 px-4 text-gray-300">
                <span class="inline-block bg-[#3b5265] text-[#27e9b5] px-2 py-1 rounded text-sm">
                  {{ role.permissions?.length || 0 }} permissions
                </span>
              </td>
              <td class="py-3 px-4 text-gray-300">
                <span class="text-sm">{{ role.users_count || 0 }}</span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2 flex-wrap">
                  <Button
                    variant="ghost"
                    size="sm"
                    @click="openPermissionsModal(role)"
                    :disabled="isLoading"
                    title="Manage role permissions"
                  >
                    🔐 Permissions
                  </Button>
                  <Button
                    variant="secondary"
                    size="sm"
                    @click="openEditModal(role)"
                    :disabled="isLoading"
                    title="Edit role details"
                  >
                    ✏️ Edit
                  </Button>
                  <Button
                    v-if="role.name !== 'super-admin'"
                    variant="danger"
                    size="sm"
                    @click="handleDelete(role)"
                    :disabled="isLoading"
                    title="Delete this role"
                  >
                    🗑️ Delete
                  </Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Empty State -->
        <div v-if="items.length === 0" class="text-center py-8">
          <p class="text-gray-400">No roles found</p>
        </div>
      </div>

      <!-- Pagination -->
      <Pagination
        :current-page="rolesStore.pagination.current_page"
        :total-pages="rolesStore.pagination.total > 0 ? Math.ceil(rolesStore.pagination.total / rolesStore.pagination.per_page) : 1"
        :total="rolesStore.pagination.total"
        :per-page="rolesStore.pagination.per_page"
        :is-loading="rolesStore.isLoading"
        @prev="rolesStore.fetchRoles(rolesStore.pagination.current_page - 1)"
        @next="rolesStore.fetchRoles(rolesStore.pagination.current_page + 1)"
        @go-to-page="rolesStore.fetchRoles"
      />
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="isCreateModalOpen || isEditModalOpen"
      :mode="modalMode"
      :fields="roleFields"
      :initial-data="selectedRoleForModal || {}"
      :is-loading="isLoading"
      :errors="errors"
      @close="closeModal"
      @submit="handleSubmit"
      @delete="handleDelete"
    />

    <!-- Permissions Modal -->
    <PermissionsModal
      :is-open="isPermissionsModalOpen"
      :role="rolesStore.selectedRole"
      :is-loading="permissionsStore.isLoading"
      @close="isPermissionsModalOpen = false"
      @save="savePermissions"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRolesStore } from '../../stores/rolesStore';
import { usePermissionsStore } from '../../stores/permissionsStore';
import { useToastStore } from '../../stores/toastStore';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import Pagination from '../../components/ui/Pagination.vue';
import CrudModal from '../../components/crud/CrudModal.vue';
import PermissionsModal from '../../components/access-control/PermissionsModal.vue';

const rolesStore = useRolesStore();
const permissionsStore = usePermissionsStore();

const isPermissionsModalOpen = ref(false);
const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const modalMode = ref('create');
const selectedRoleForModal = ref(null);

const roleFields = [
  { name: 'name', label: 'Role Name', type: 'text', required: true },
];

// Computed properties
const items = computed(() => rolesStore.rolesList);
const isLoading = computed(() => rolesStore.isLoading);
const errors = computed(() => ({ general: rolesStore.error }));

onMounted(async () => {
  await rolesStore.fetchRoles();
});

const handleDelete = async (item) => {
  if (item.name === 'super-admin') {
    // Use toast instead of alert
    const toastStore = useToastStore();
    toastStore.error('Cannot Delete', 'Cannot delete the super-admin role');
    return;
  }

  if (confirm(`Are you sure you want to delete the "${item.name}" role?`)) {
    await rolesStore.deleteRole(item.id);
    // Toast notifications are handled in the store
  }
};

const openCreateModal = () => {
  modalMode.value = 'create';
  selectedRoleForModal.value = null;
  isCreateModalOpen.value = true;
};

const openEditModal = (role) => {
  modalMode.value = 'edit';
  selectedRoleForModal.value = { ...role };
  isEditModalOpen.value = true;
};

const closeModal = () => {
  isCreateModalOpen.value = false;
  isEditModalOpen.value = false;
  selectedRoleForModal.value = null;
};

const handleSubmit = async (formData) => {
  if (modalMode.value === 'create') {
    const result = await rolesStore.createRole(formData);
    if (result.success) {
      closeModal();
      // Toast notification is handled in the store
    }
    // Error toast is also handled in the store
  } else {
    const result = await rolesStore.updateRole(selectedRoleForModal.value.id, formData);
    if (result.success) {
      closeModal();
      // Toast notification is handled in the store
    }
    // Error toast is also handled in the store
  }
};

const openPermissionsModal = (role) => {
  rolesStore.setSelectedRole(role);
  isPermissionsModalOpen.value = true;
};

const savePermissions = async (permissionIds) => {
  const result = await permissionsStore.assignPermissionsToRole(
    rolesStore.selectedRole.id,
    permissionIds
  );
  if (result.success) {
    alert('Permissions assigned successfully');
    isPermissionsModalOpen.value = false;
    await rolesStore.fetchRoles();
  } else {
    alert(`Error: ${result.error}`);
  }
};
</script>

