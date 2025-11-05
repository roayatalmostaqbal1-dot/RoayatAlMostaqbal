<template>
  <DashboardLayout page-title="Roles" page-description="Manage system roles and permissions">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Roles List</h2>
          <Button
            variant="primary"
            size="sm"
            @click="crud.openCreateModal"
            :disabled="crud.isLoading.value"
          >
            + Add Role
          </Button>
        </div>
      </template>

      <!-- Error Message -->
      <div v-if="crud.errors.general" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ crud.errors.general }}
      </div>

      <!-- Loading State -->
      <div v-if="crud.isLoading.value && crud.items.value.length === 0" class="text-center py-8">
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
            <tr v-for="role in crud.items.value" :key="role.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
              <td class="py-3 px-4 text-white">{{ role.name }}</td>
              <td class="py-3 px-4 text-gray-300">
                <span class="inline-block bg-[#3b5265] text-[#27e9b5] px-2 py-1 rounded text-sm">
                  {{ role.permissions?.length || 0 }} permissions
                </span>
              </td>
              <td class="py-3 px-4 text-gray-300">
                <span class="text-sm">{{ role.users_count || 0 }}</span>
              </td>
              <td class="py-3 px-4 space-x-2">
                <Button
                  variant="ghost"
                  size="sm"
                  @click="openPermissionsModal(role)"
                  :disabled="crud.isLoading.value"
                >
                  Permissions
                </Button>
                <Button
                  variant="ghost"
                  size="sm"
                  @click="crud.openEditModal(role)"
                  :disabled="crud.isLoading.value"
                >
                  Edit
                </Button>
                <Button
                  v-if="role.name !== 'super-admin'"
                  variant="danger"
                  size="sm"
                  @click="handleDelete(role)"
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
          <p class="text-gray-400">No roles found</p>
        </div>
      </div>
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="crud.isModalOpen.value"
      :mode="crud.modalMode.value"
      :fields="roleFields"
      :initial-data="crud.selectedItem.value || {}"
      :is-loading="crud.isLoading.value"
      :errors="crud.errors"
      @close="crud.closeModal"
      @submit="crud.handleSubmit"
      @delete="handleDelete"
    />

    <!-- Permissions Modal -->
    <PermissionsModal
      :is-open="isPermissionsModalOpen"
      :role="selectedRole"
      :is-loading="isPermissionsLoading"
      @close="isPermissionsModalOpen = false"
      @save="savePermissions"
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
import PermissionsModal from '../../components/access-control/PermissionsModal.vue';

const crud = useCrud('/SuperAdmin/roles');
const isPermissionsModalOpen = ref(false);
const selectedRole = ref(null);
const isPermissionsLoading = ref(false);

const roleFields = [
  { name: 'name', label: 'Role Name', type: 'text', required: true },
];

onMounted(() => crud.fetchItems());

const handleDelete = async (item) => {
  if (item.name === 'super-admin') {
    alert('Cannot delete the super-admin role');
    return;
  }

  if (confirm(`Are you sure you want to delete the "${item.name}" role?`)) {
    await crud.deleteItem(item.id);
  }
};

const openPermissionsModal = (role) => {
  selectedRole.value = role;
  isPermissionsModalOpen.value = true;
};

const savePermissions = async (permissionIds) => {
  isPermissionsLoading.value = true;
  try {
    const response = await fetch(`/SuperAdmin/roles/${selectedRole.value.id}/permissions`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
      body: JSON.stringify({ permission_ids: permissionIds }),
    });

    if (response.ok) {
      isPermissionsModalOpen.value = false;
      await crud.fetchItems();
    }
  } catch (error) {
    console.error('Error saving permissions:', error);
  } finally {
    isPermissionsLoading.value = false;
  }
};
</script>

