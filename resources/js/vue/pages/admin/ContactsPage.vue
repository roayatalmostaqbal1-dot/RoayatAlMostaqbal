<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
        <i class="fas fa-envelope mr-3"></i>Contact Messages
      </h1>
      <p class="text-gray-600 dark:text-gray-400 mt-2">Manage incoming contact form submissions</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Search
          </label>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name or email"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Status
          </label>
          <select
            v-model="selectedStatus"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">All</option>
            <option value="new">New</option>
            <option value="read">Read</option>
            <option value="replied">Replied</option>
          </select>
        </div>

        <!-- Refresh Button -->
        <div class="flex items-end">
          <button
            @click="fetchContacts"
            class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
          >
            <i class="fas fa-sync-alt mr-2"></i>Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-8">
      <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
      <p class="text-gray-600 dark:text-gray-400 mt-2">Loading...</p>
    </div>

    <!-- Contacts Table -->
    <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Service</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Date</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="contact in contacts" :key="contact.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ contact.name }}</td>
            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ contact.email }}</td>
            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ contact.service || '-' }}</td>
            <td class="px-6 py-4 text-sm">
              <span
                :class="getStatusBadgeClass(contact.status)"
                class="px-3 py-1 rounded-full text-xs font-semibold"
              >
                {{ getStatusLabel(contact.status) }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
              {{ formatDate(contact.created_at) }}
            </td>
            <td class="px-6 py-4 text-sm">
              <button
                @click="viewContact(contact)"
                class="text-blue-500 hover:text-blue-700 mr-3"
                title="View"
              >
                <i class="fas fa-eye"></i>
              </button>
              <button
                @click="deleteContact(contact.id)"
                class="text-red-500 hover:text-red-700"
                title="Delete"
              >
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-if="contacts.length === 0" class="text-center py-8">
        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-600 dark:text-gray-400">No contacts found</p>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && contacts.length > 0" class="mt-6">
      <Pagination
        :current-page="pagination.current_page"
        :last-page="pagination.last_page"
        :total="pagination.total"
        :per-page="pagination.per_page"
        @page-changed="handlePageChange"
      />
    </div>

    <!-- View Modal -->
    <ContactViewModal
      v-if="selectedContact"
      :contact="selectedContact"
      :is-open="isViewModalOpen"
      @close="isViewModalOpen = false"
      @reply="handleReply"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useContactsStore } from '../../stores/contactsStore';
import Pagination from '../../components/ui/Pagination.vue';
import ContactViewModal from '../../components/contact/ContactViewModal.vue';

const contactsStore = useContactsStore();
const loading = ref(false);
const searchQuery = ref('');
const selectedStatus = ref('');
const selectedContact = ref(null);
const isViewModalOpen = ref(false);

const contacts = computed(() => contactsStore.contacts);
const pagination = computed(() => contactsStore.pagination);

const fetchContacts = async (page = 1) => {
  loading.value = true;
  try {
    await contactsStore.fetchContacts({
      page,
      per_page: 15,
      search: searchQuery.value,
      status: selectedStatus.value,
    });
  } finally {
    loading.value = false;
  }
};

const viewContact = (contact) => {
  selectedContact.value = contact;
  isViewModalOpen.value = true;
};

const deleteContact = async (id) => {
  if (confirm('Are you sure you want to delete this contact?')) {
    await contactsStore.deleteContact(id);
    await fetchContacts();
  }
};

const handlePageChange = (page) => {
  fetchContacts(page);
};

const handleReply = async (replyData) => {
  await contactsStore.updateContact(selectedContact.value.id, replyData);
  isViewModalOpen.value = false;
  await fetchContacts();
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
    new: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    read: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    replied: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
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

