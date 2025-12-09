<template>
  <DashboardLayout page-title="OAuth2 Clients" page-description="Manage OAuth2 clients for external applications">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">OAuth2 Clients</h2>
          <Button
            variant="primary"
            size="sm"
            @click="openCreateModal"
            :disabled="isLoading"
          >
            + Add Client
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
        <p class="text-gray-400 mt-2">Loading clients...</p>
      </div>

      <!-- Clients Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Name</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Client ID</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Type</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Status</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="client in items" :key="client.id" class="border-b border-[#162936] hover:bg-[#162936] transition">
              <td class="py-3 px-4">
                <span class="font-semibold text-[#27e9b5]">{{ client.name }}</span>
              </td>
              <td class="py-3 px-4 text-gray-300">
                <code class="bg-[#162936] px-2 py-1 rounded text-xs">{{ client.client_id }}</code>
              </td>
              <td class="py-3 px-4">
                <span class="inline-block bg-[#3b5265] text-[#27e9b5] px-2 py-1 rounded text-sm">
                  {{ client.confidential ? 'Confidential' : 'Public' }}
                </span>
              </td>
              <td class="py-3 px-4">
                <span :class="[
                  'inline-block px-2 py-1 rounded text-sm',
                  client.revoked ? 'bg-red-500 bg-opacity-20 text-red-400' : 'bg-green-500 bg-opacity-20 text-white'
                ]">
                  {{ client.revoked ? 'Revoked' : 'Active' }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2 flex-wrap">
                  <Button
                    variant="ghost"
                    size="sm"
                    @click="openViewModal(client)"
                    :disabled="isLoading"
                    title="View client details"
                  >
                    👁️ View
                  </Button>
                  <Button
                    v-if="!client.revoked"
                    variant="secondary"
                    size="sm"
                    @click="openEditModal(client)"
                    :disabled="isLoading"
                    title="Edit client"
                  >
                    ✏️ Edit
                  </Button>
                  <Button
                    v-if="!client.revoked"
                    variant="warning"
                    size="sm"
                    @click="handleRegenerateSecret(client)"
                    :disabled="isLoading"
                    title="Regenerate secret"
                  >
                    🔄 Regenerate
                  </Button>
                  <Button
                    variant="danger"
                    size="sm"
                    @click="handleDelete(client)"
                    :disabled="isLoading"
                    title="Delete client"
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
          <p class="text-gray-400">No OAuth2 clients found</p>
        </div>
      </div>

      <!-- Pagination -->
      <Pagination
        :current-page="oauth2Store.pagination.current_page"
        :total-pages="oauth2Store.pagination.total > 0 ? Math.ceil(oauth2Store.pagination.total / oauth2Store.pagination.per_page) : 1"
        :total="oauth2Store.pagination.total"
        :per-page="oauth2Store.pagination.per_page"
        :is-loading="oauth2Store.isLoading"
        @prev="oauth2Store.fetchClients(oauth2Store.pagination.current_page - 1)"
        @next="oauth2Store.fetchClients(oauth2Store.pagination.current_page + 1)"
        @go-to-page="oauth2Store.fetchClients"
      />
    </Card>

    <!-- CRUD Modal -->
    <CrudModal
      :is-open="isCreateModalOpen || isEditModalOpen"
      :mode="modalMode"
      :fields="clientFields"
      :initial-data="selectedClientForModal || {}"
      :is-loading="isLoading"
      :errors="errors"
      @close="closeModal"
      @submit="handleSubmit"
    />

    <!-- View Modal -->
    <OAuth2ClientViewModal
      :is-open="isViewModalOpen"
      :client="selectedClientForModal"
      :is-loading="isLoading"
      @close="isViewModalOpen = false"
      @regenerate="handleRegenerateSecret"
    />

    <!-- Confirmation Modal for Delete -->
    <ConfirmationModal
      :isOpen="isDeleteConfirmOpen"
      title="Delete OAuth2 Client"
      :message="`Are you sure you want to delete the '${clientToDelete?.name}' client? This action cannot be undone.`"
      confirmButtonText="Delete"
      cancelButtonText="Cancel"
      buttonType="danger"
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />

    <!-- Confirmation Modal for Regenerate Secret -->
    <ConfirmationModal
      :isOpen="isRegenerateConfirmOpen"
      title="Regenerate Client Secret"
      :message="`Are you sure you want to regenerate the secret for '${clientToRegenerate?.name}'? This will invalidate the old secret.`"
      confirmButtonText="Regenerate"
      cancelButtonText="Cancel"
      buttonType="warning"
      @confirm="confirmRegenerate"
      @cancel="cancelRegenerate"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useOAuth2ClientsStore } from '../../stores/oauth2ClientsStore';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import Pagination from '../../components/ui/Pagination.vue';
import CrudModal from '../../components/crud/CrudModal.vue';
import OAuth2ClientViewModal from '../../components/oauth2/OAuth2ClientViewModal.vue';
import ConfirmationModal from '../../components/ui/ConfirmationModal.vue';

const oauth2Store = useOAuth2ClientsStore();

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const isViewModalOpen = ref(false);
const modalMode = ref('create');
const selectedClientForModal = ref(null);

// Confirmation Modal States
const isDeleteConfirmOpen = ref(false);
const isRegenerateConfirmOpen = ref(false);
const clientToDelete = ref(null);
const clientToRegenerate = ref(null);

const clientFields = [
  { name: 'name', label: 'Application Name', type: 'text', required: true },
  { name: 'redirect', label: 'Redirect URI', type: 'url', required: true },
  { name: 'confidential', label: 'Confidential Client', type: 'checkbox', required: false },
];

const items = computed(() => oauth2Store.clientsList);
const isLoading = computed(() => oauth2Store.isLoading);
const errors = computed(() => ({ general: oauth2Store.error }));

onMounted(async () => {
  await oauth2Store.fetchClients();
});

const handleDelete = (client) => {
  clientToDelete.value = client;
  isDeleteConfirmOpen.value = true;
};

const confirmDelete = async () => {
  if (clientToDelete.value) {
    await oauth2Store.deleteClient(clientToDelete.value.id);
    isDeleteConfirmOpen.value = false;
    clientToDelete.value = null;
  }
};

const cancelDelete = () => {
  isDeleteConfirmOpen.value = false;
  clientToDelete.value = null;
};

const openCreateModal = () => {
  modalMode.value = 'create';
  selectedClientForModal.value = null;
  isCreateModalOpen.value = true;
};

const openEditModal = (client) => {
  modalMode.value = 'edit';
  selectedClientForModal.value = { ...client };
  isEditModalOpen.value = true;
};

const openViewModal = (client) => {
  selectedClientForModal.value = { ...client };
  isViewModalOpen.value = true;
};

const closeModal = () => {
  isCreateModalOpen.value = false;
  isEditModalOpen.value = false;
  selectedClientForModal.value = null;
};

const handleSubmit = async (formData) => {
  if (modalMode.value === 'create') {
    const result = await oauth2Store.createClient(formData);
    if (result.success) {
      closeModal();
    }
  } else {
    const result = await oauth2Store.updateClient(selectedClientForModal.value.id, formData);
    if (result.success) {
      closeModal();
    }
  }
};

const handleRegenerateSecret = (client) => {
  clientToRegenerate.value = client;
  isRegenerateConfirmOpen.value = true;
};

const confirmRegenerate = async () => {
  if (clientToRegenerate.value) {
    const result = await oauth2Store.regenerateSecret(clientToRegenerate.value.id);
    if (result.success) {
      isViewModalOpen.value = false;
    }
    isRegenerateConfirmOpen.value = false;
    clientToRegenerate.value = null;
  }
};

const cancelRegenerate = () => {
  isRegenerateConfirmOpen.value = false;
  clientToRegenerate.value = null;
};
</script>

