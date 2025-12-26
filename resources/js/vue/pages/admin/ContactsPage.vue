<template>
  <DashboardLayout page-title="Contact Messages" page-description="Manage incoming contact form submissions">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Contact Messages</h2>
          <Button
            variant="primary"
            size="sm"
            @click="fetchContacts"
            :disabled="isLoading"
          >
            <i class="fas fa-sync-alt mr-2"></i>Refresh
          </Button>
        </div>
      </template>

      <!-- Error Message -->
      <div v-if="error" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ error }}
      </div>

      <!-- Filters -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Search</label>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name or email"
            class="w-full px-4 py-2 border border-[#3b5265] rounded-lg bg-[#162936] text-white placeholder-gray-500 focus:ring-2 focus:ring-[#27e9b5] focus:border-[#27e9b5]"
          />
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
          <select
            v-model="selectedStatus"
            class="w-full px-4 py-2 border border-[#3b5265] rounded-lg bg-[#162936] text-white focus:ring-2 focus:ring-[#27e9b5] focus:border-[#27e9b5]"
          >
            <option value="">All</option>
            <option value="new">New</option>
            <option value="read">Read</option>
            <option value="replied">Replied</option>
          </select>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && contacts.length === 0" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-2">Loading contacts...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="contacts.length === 0" class="text-center py-8">
        <p class="text-gray-400 mb-4">No contacts found</p>
        <Button variant="secondary" @click="fetchContacts">Try Again</Button>
      </div>

      <!-- Contacts Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Name</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Email</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Service</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Status</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Date</th>
              <th class="text-left py-3 px-4 text-gray-300 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="contact in contacts" :key="contact.id" class="border-b border-[#3b5265] hover:bg-[#162936] transition">
              <td class="py-3 px-4 text-white">{{ contact.name }}</td>
              <td class="py-3 px-4 text-gray-300">{{ contact.email }}</td>
              <td class="py-3 px-4 text-gray-300">{{ contact.service || '-' }}</td>
              <td class="py-3 px-4">
                <span
                  :class="getStatusBadgeClass(contact.status)"
                  class="px-3 py-1 rounded-full text-xs font-semibold inline-block"
                >
                  {{ getStatusLabel(contact.status) }}
                </span>
              </td>
              <td class="py-3 px-4 text-gray-300">{{ formatDate(contact.created_at) }}</td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2 flex-wrap">
                  <Button
                    variant="ghost"
                    size="sm"
                    @click="viewContact(contact)"
                    :disabled="isLoading"
                    title="View contact details"
                  >
                    👁️ View
                  </Button>
                  <Button
                    variant="danger"
                    size="sm"
                    @click="handleDelete(contact)"
                    :disabled="isLoading"
                    title="Delete contact"
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
      <div v-if="!isLoading && contacts.length > 0" class="mt-6 flex justify-between items-center">
        <p class="text-sm text-gray-400">
          Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} contacts
        </p>
        <div class="flex gap-2">
          <Button
            variant="secondary"
            size="sm"
            @click="previousPage"
            :disabled="pagination.current_page === 1 || isLoading"
          >
            Previous
          </Button>
          <Button
            variant="secondary"
            size="sm"
            @click="nextPage"
            :disabled="pagination.current_page === pagination.last_page || isLoading"
          >
            Next
          </Button>
        </div>
      </div>
    </Card>

    <!-- Contact View Modal -->
    <ContactViewModal
      :is-open="isViewModalOpen"
      :contact="selectedContact"
      :is-loading="isLoading"
      @close="closeViewModal"
      @reply="handleReply"
      @updated="handleContactUpdated"
    />

    <!-- Delete Confirmation Modal -->
    <ConfirmationModal
      :isOpen="isDeleteConfirmOpen"
      title="Delete Contact"
      :message="`Are you sure you want to delete the contact from ${contactToDelete?.name}? This action cannot be undone.`"
      confirmButtonText="Delete"
      cancelButtonText="Cancel"
      buttonType="danger"
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useContactsStore } from '../../stores/Admin/contactsStore';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import ContactViewModal from '../../components/contact/ContactViewModal.vue';
import ConfirmationModal from '../../components/ui/ConfirmationModal.vue';

const contactsStore = useContactsStore();
const isLoading = ref(false);
const error = ref(null);
const searchQuery = ref('');
const selectedStatus = ref('');
const selectedContact = ref(null);
const isViewModalOpen = ref(false);

// Delete Confirmation Modal States
const isDeleteConfirmOpen = ref(false);
const contactToDelete = ref(null);

const contacts = computed(() => contactsStore.contacts);
const pagination = computed(() => contactsStore.pagination);

const fetchContacts = async (page = 1) => {
  isLoading.value = true;
  error.value = null;
  try {
    await contactsStore.fetchContacts({
      page,
      per_page: 15,
      search: searchQuery.value,
      status: selectedStatus.value,
    });
  } catch (err) {
    error.value = err.message || 'Failed to fetch contacts';
  } finally {
    isLoading.value = false;
  }
};

const viewContact = (contact) => {
  selectedContact.value = { ...contact };
  isViewModalOpen.value = true;
};

const closeViewModal = () => {
  isViewModalOpen.value = false;
  selectedContact.value = null;
};

const handleDelete = (contact) => {
  contactToDelete.value = contact;
  isDeleteConfirmOpen.value = true;
};

const confirmDelete = async () => {
  if (contactToDelete.value) {
    isLoading.value = true;
    try {
      await contactsStore.deleteContact(contactToDelete.value.id);
      isDeleteConfirmOpen.value = false;
      contactToDelete.value = null;
      await fetchContacts();
    } catch (err) {
      error.value = err.message || 'Failed to delete contact';
    } finally {
      isLoading.value = false;
    }
  }
};

const cancelDelete = () => {
  isDeleteConfirmOpen.value = false;
  contactToDelete.value = null;
};

const handleReply = async (replyData) => {
  if (!selectedContact.value) return;

  isLoading.value = true;
  try {
    await contactsStore.updateContact(selectedContact.value.id, replyData);
    // The email will be sent automatically by the backend
  } catch (err) {
    error.value = err.message || 'Failed to send reply';
  } finally {
    isLoading.value = false;
  }
};

const handleContactUpdated = async () => {
  closeViewModal();
  await fetchContacts();
};

const previousPage = () => {
  if (pagination.value.current_page > 1) {
    fetchContacts(pagination.value.current_page - 1);
  }
};

const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    fetchContacts(pagination.value.current_page + 1);
  }
};

const getStatusLabel = (status) => {
  const labels = {
    new: 'New',
    read: 'Read',
    replied: 'Replied',
  };
  return labels[status] || status;
};

const getStatusBadgeClass = (status) => {
  const classes = {
    new: 'bg-blue-500 bg-opacity-20 text-blue-300 border border-blue-500 border-opacity-30',
    read: 'bg-yellow-500 bg-opacity-20 text-yellow-300 border border-yellow-500 border-opacity-30',
    replied: 'bg-green-500 bg-opacity-20 text-green-300 border border-green-500 border-opacity-30',
  };
  return classes[status] || 'bg-gray-500 bg-opacity-20 text-gray-300';
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

onMounted(() => {
  fetchContacts();
});
</script>

