<template>
  <DashboardLayout page-title="Permissions" page-description="Manage system permissions">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Permissions List</h2>
          <Button
            variant="primary"
            size="sm"
            @click="crud.openCreateModal"
            :disabled="crud.isLoading.value"
          >
            + Add Permission
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
        <p class="text-gray-400 mt-2">Loading permissions...</p>
      </div>

      <!-- Permissions Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Name</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Group</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Description</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Roles</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="permission in crud.items.value" :key="permission.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
              <td class="py-3 px-4 text-white font-mono text-sm">{{ permission.name }}</td>
              <td class="py-3 px-4">
                <span class="inline-block bg-[#3b5265] text-[#27e9b5] px-2 py-1 rounded text-xs">
                  {{ permission.group || 'general' }}
                </span>
              </td>
              <td class="py-3 px-4 text-gray-300 text-sm">{{ permission.description }}</td>
              <td class="py-3 px-4 text-gray-300">
                <span class="text-sm">{{ permission.roles_count || 0 }}</span>
              </td>
              <td class="py-3 px-4 space-x-2">
                <Button
                  variant="ghost"
                  size="sm"
                  @click="crud.openEditModal(permission)"
                  :disabled="crud.isLoading.value"
                >
                  Edit
                </Button>
                <Button
                  variant="danger"
                  size="sm"
                  @click="handleDelete(permission)"
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
          <p class="text-gray-400">No permissions found</p>
        </div>
      </div>
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="crud.isModalOpen.value"
      :mode="crud.modalMode.value"
      :fields="permissionFields"
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
import { onMounted } from 'vue';
import { useCrud } from '../../composables/useCrud';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import CrudModal from '../../components/crud/CrudModal.vue';

const crud = useCrud('/SuperAdmin/permissions');

const permissionFields = [
  { name: 'name', label: 'Permission Name', type: 'text', required: true, placeholder: 'e.g., users.view' },
  { name: 'description', label: 'Description', type: 'textarea', required: false },
  {
    name: 'group',
    label: 'Group',
    type: 'select',
    required: false,
    options: [
      { value: 'users', label: 'Users' },
      { value: 'products', label: 'Products' },
      { value: 'categories', label: 'Categories' },
      { value: 'roles', label: 'Roles' },
      { value: 'permissions', label: 'Permissions' },
      { value: 'api_routes', label: 'API Routes' },
    ]
  },
];

onMounted(() => crud.fetchItems());

const handleDelete = async (item) => {
  if (confirm(`Are you sure you want to delete the "${item.name}" permission?`)) {
    await crud.deleteItem(item.id);
  }
};
</script>

