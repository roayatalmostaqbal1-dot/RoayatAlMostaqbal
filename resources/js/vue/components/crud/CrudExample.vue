<template>
  <div class="space-y-6">
    <!-- Header with Create Button -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-white">Users Management</h1>
      <Button
        variant="primary"
        @click="crud.openCreateModal"
        :disabled="crud.isLoading.value"
      >
        + Create User
      </Button>
    </div>

    <!-- Error Message -->
    <div
      v-if="crud.errors.general"
      class="p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400"
    >
      {{ crud.errors.general }}
    </div>

    <!-- Loading State -->
    <div v-if="crud.isLoading.value && crud.items.value.length === 0" class="text-center py-8">
      <div class="inline-block">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <p class="text-gray-400 mt-2">Loading users...</p>
    </div>

    <!-- Data Table -->
    <CrudTable
      v-else
      :items="crud.items.value"
      :columns="userColumns"
      @view="crud.openViewModal"
      @edit="crud.openEditModal"
      @delete="handleDelete"
    />

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="crud.isModalOpen.value"
      :mode="crud.modalMode.value"
      :fields="userFields"
      :initial-data="crud.selectedItem.value || {}"
      :is-loading="crud.isLoading.value"
      :errors="crud.errors"
      @close="crud.closeModal"
      @submit="crud.handleSubmit"
      @delete="handleDelete"
    />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import Button from '../ui/Button.vue';
import CrudTable from './CrudTable.vue';
import CrudModal from './CrudModal.vue';
import { useCrud } from '../../composables/useCrud';

// Initialize CRUD operations for users endpoint
const crud = useCrud('/api/users');

// Define table columns
const userColumns = [
  { key: 'id', label: 'ID' },
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'role', label: 'Role' },
  {
    key: 'created_at',
    label: 'Created',
    format: (date) => new Date(date).toLocaleDateString(),
  },
];

// Define form fields
const userFields = [
  {
    name: 'name',
    label: 'Full Name',
    type: 'text',
    placeholder: 'Enter full name',
    required: true,
    hint: 'User\'s full name',
  },
  {
    name: 'email',
    label: 'Email Address',
    type: 'email',
    placeholder: 'Enter email address',
    required: true,
    hint: 'Must be a valid email',
  },
  {
    name: 'password',
    label: 'Password',
    type: 'password',
    placeholder: 'Enter password',
    required: true,
    hint: 'Minimum 8 characters',
  },
  {
    name: 'role',
    label: 'Role',
    type: 'select',
    required: true,
    options: [
      { value: 'admin', label: 'Administrator' },
      { value: 'user', label: 'User' },
      { value: 'guest', label: 'Guest' },
    ],
  },
  {
    name: 'is_active',
    label: 'Active',
    type: 'checkbox',
  },
];

// Fetch users on component mount
onMounted(() => {
  crud.fetchItems();
});

// Handle delete with confirmation
const handleDelete = async (item) => {
  if (confirm(`Are you sure you want to delete ${item.name}?`)) {
    try {
      await crud.deleteItem(item.id);
    } catch (error) {
      console.error('Delete failed:', error);
    }
  }
};
</script>

