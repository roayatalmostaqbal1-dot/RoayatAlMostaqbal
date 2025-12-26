<template>
  <DashboardLayout page-title="Users" page-description="Manage system users">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Users List</h2>
          <Button
            variant="primary"
            size="sm"
            @click="openCreateModal"
            :disabled="usersStore.isLoading"
          >
            + Add User
          </Button>
        </div>
      </template>

      <!-- Error Message -->
      <div
        v-if="usersStore.error"
        class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400"
      >
        {{ usersStore.error }}
      </div>

      <!-- Loading State -->
      <div v-if="usersStore.isLoading && usersStore.usersList.length === 0" class="text-center py-8">
        <div class="inline-block">
          <svg class="animate-spin h-8 w-8 text-[#27e9b5]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p class="text-gray-400 mt-2">Loading users...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="usersStore.usersList.length === 0" class="text-center py-8">
        <p class="text-gray-400 mb-4">No users found</p>
        <Button variant="primary" @click="openCreateModal">Create First User</Button>
      </div>

      <!-- Users Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Name</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Email</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Role</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Status</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in usersStore.usersList"
              :key="user.id"
              class="border-b border-[#3b5265] hover:bg-[#1f3a4a] transition-colors"
            >
              <td class="py-3 px-4 text-white">{{ user.name }}</td>
              <td class="py-3 px-4 text-gray-400">{{ user.email }}</td>
              <td class="py-3 px-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#27e9b5] bg-opacity-20 text-[#000]">
                  {{ user.roles && user.roles.length > 0 ? user.roles[0] : 'User' }}
                </span>
              </td>
              <td class="py-3 px-4">
                <span class="flex items-center gap-2">
                  <span
                    class="w-2 h-2 rounded-full"
                    :class="user.is_active ? 'bg-green-500' : 'bg-red-500'"
                  ></span>
                  <span
                    class="text-sm"
                    :class="user.is_active ? 'text-green-400' : 'text-red-400'"
                  >
                    {{ user.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2 flex-wrap">
                  <Button
                    variant="secondary"
                    size="sm"
                    @click="openViewModal(user)"
                    title="View user details"
                  >
                    👁️ View
                  </Button>
                  <Button
                    variant="secondary"
                    size="sm"
                    @click="openEditModal(user)"
                    title="Edit user details"
                  >
                    ✏️ Edit
                  </Button>
                  <Button
                    variant="danger"
                    size="sm"
                    @click="handleDelete(user)"
                    :disabled="usersStore.isLoading"
                    title="Delete this user"
                  >
                    🗑️ Delete
                  </Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <Pagination
        :current-page="usersStore.currentPage"
        :total-pages="usersStore.lastPage"
        :total="usersStore.totalUsers"
        :per-page="usersStore.pagination.per_page"
        :is-loading="usersStore.isLoading"
        @prev="usersStore.prevPage"
        @next="usersStore.nextPage"
        @go-to-page="usersStore.goToPage"
      />
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="isModalOpen"
      :mode="modalMode"
      :fields="userFields"
      :initial-data="selectedUser || {}"
      :is-loading="usersStore.isLoading"
      :errors="modalErrors"
      @close="closeModal"
      @submit="handleSubmit"
      @delete="handleDelete"
    />
  </DashboardLayout>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import Pagination from '../../components/ui/Pagination.vue';
import CrudModal from '../../components/crud/CrudModal.vue';
import { useUsersStore } from '../../stores/Admin/usersStore';
import { useRolesStore } from '../../stores/SuberAdmin/rolesStore';

// Initialize stores
const usersStore = useUsersStore();
const rolesStore = useRolesStore();

// Modal state
const isModalOpen = ref(false);
const modalMode = ref('create');
const selectedUser = ref(null);
const modalErrors = ref({});

// Computed property for dynamic role options
const roleOptions = computed(() => {
  return rolesStore.rolesList.map(role => ({
    value: role.name,
    label: role.name
  }));
});

// Define form fields for user creation/editing
const userFields = computed(() => [
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
    required: modalMode.value === 'create',
    hint: modalMode.value === 'create' ? 'Minimum 8 characters' : 'Leave empty to keep current password',
  },
  {
    name: 'role',
    label: 'Role',
    type: 'select',
    required: true,
    options: roleOptions.value,
  },
  {
    name: 'is_active',
    label: 'Active',
    type: 'checkbox',
  },
]);

const openCreateModal = async () => {
  // Ensure roles are loaded
  if (rolesStore.rolesList.length === 0) {
    await rolesStore.fetchRoles(1, 100);
  }

  modalMode.value = 'create';
  selectedUser.value = null;
  modalErrors.value = {};
  isModalOpen.value = true;
};

const openEditModal = async (user) => {
  // Ensure roles are loaded
  if (rolesStore.rolesList.length === 0) {
    await rolesStore.fetchRoles(1, 100);
  }

  // Fetch fresh user data from API
  const result = await usersStore.fetchUserById(user.id);
  if (result.success) {
    modalMode.value = 'edit';
    selectedUser.value = { ...result.data };
    modalErrors.value = {};
    isModalOpen.value = true;
  } else {
    console.error('Failed to fetch user:', result.error);
  }
};

const openViewModal = async (user) => {
  // Ensure roles are loaded
  if (rolesStore.rolesList.length === 0) {
    await rolesStore.fetchRoles(1, 100);
  }

  // Fetch fresh user data from API
  const result = await usersStore.fetchUserById(user.id);
  if (result.success) {
    modalMode.value = 'view';
    selectedUser.value = { ...result.data };
    modalErrors.value = {};
    isModalOpen.value = true;
  } else {
    console.error('Failed to fetch user:', result.error);
  }
};

const closeModal = () => {
  isModalOpen.value = false;
  selectedUser.value = null;
  modalErrors.value = {};
  usersStore.clearError();
};

const handleSubmit = async (formData) => {
  modalErrors.value = {};
  let result;

  if (modalMode.value === 'create') {
    result = await usersStore.createUser(formData);
  } else if (modalMode.value === 'edit') {
    result = await usersStore.updateUser(selectedUser.value.id, formData);
  }

  if (result.success) {
    closeModal();
  } else {
    if (result.errors) {
      modalErrors.value = result.errors;
    } else {
      modalErrors.value.general = result.error;
    }
  }
};

// Fetch users and roles on component mount
onMounted(async () => {
  await usersStore.fetchUsers();
  // Load roles for the role dropdown
  if (rolesStore.rolesList.length === 0) {
    await rolesStore.fetchRoles(1, 100);
  }
});

// Handle delete with confirmation
const handleDelete = async (item) => {
  if (confirm(`Are you sure you want to delete ${item.name}?`)) {
    const result = await usersStore.deleteUser(item.id);
    if (!result.success) {
      console.error('Delete failed:', result.error);
    }
  }
};
</script>

