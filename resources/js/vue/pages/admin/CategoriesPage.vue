<template>
  <DashboardLayout page-title="Categories" page-description="Manage product categories">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Categories List</h2>
          <Button 
            variant="primary" 
            size="sm"
            @click="crud.openCreateModal"
            :disabled="crud.isLoading.value"
          >
            + Add Category
          </Button>
        </div>
      </template>

      <!-- Error Message -->
      <div
        v-if="crud.errors.general"
        class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400"
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
        <p class="text-gray-400 mt-2">Loading categories...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="crud.items.value.length === 0" class="text-center py-8">
        <p class="text-gray-400 mb-4">No categories found</p>
        <Button variant="primary" @click="crud.openCreateModal">Create First Category</Button>
      </div>

      <!-- Categories Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Name</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Slug</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Description</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Status</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="category in crud.items.value" 
              :key="category.id" 
              class="border-b border-[#3b5265] hover:bg-[#1f3a4a] transition-colors"
            >
              <td class="py-3 px-4 text-white font-semibold">{{ category.name }}</td>
              <td class="py-3 px-4 text-gray-400 font-mono text-sm">{{ category.slug }}</td>
              <td class="py-3 px-4 text-gray-400 truncate max-w-xs">{{ category.description || '-' }}</td>
              <td class="py-3 px-4">
                <span 
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="category.is_active 
                    ? 'bg-green-500 bg-opacity-20 text-green-400' 
                    : 'bg-gray-500 bg-opacity-20 text-gray-400'"
                >
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex gap-2">
                  <Button 
                    variant="ghost" 
                    size="sm"
                    @click="crud.openViewModal(category)"
                  >
                    View
                  </Button>
                  <Button 
                    variant="ghost" 
                    size="sm"
                    @click="crud.openEditModal(category)"
                  >
                    Edit
                  </Button>
                  <Button 
                    variant="danger" 
                    size="sm"
                    @click="handleDelete(category)"
                    :disabled="crud.isLoading.value"
                  >
                    Delete
                  </Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="crud.isModalOpen.value"
      :mode="crud.modalMode.value"
      :fields="categoryFields"
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
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import CrudModal from '../../components/crud/CrudModal.vue';
import { useCrud } from '../../composables/useCrud';

// Initialize CRUD operations for categories endpoint
const crud = useCrud('/api/categories');

// Define form fields for category creation/editing
const categoryFields = [
  {
    name: 'name',
    label: 'Category Name',
    type: 'text',
    placeholder: 'Enter category name',
    required: true,
  },
  {
    name: 'slug',
    label: 'Slug',
    type: 'text',
    placeholder: 'category-slug',
    required: true,
    hint: 'URL-friendly identifier',
  },
  {
    name: 'description',
    label: 'Description',
    type: 'textarea',
    placeholder: 'Enter category description',
  },
  {
    name: 'is_active',
    label: 'Active',
    type: 'checkbox',
  },
];

// Fetch categories on component mount
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

