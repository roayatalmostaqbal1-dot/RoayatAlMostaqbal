<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="sticky top-0 bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Contact Details</h2>
        <button
          @click="$emit('close')"
          class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
        >
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Content -->
      <div class="p-6 space-y-4">
        <!-- Contact Info -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Name</label>
            <p class="text-gray-900 dark:text-white">{{ contact.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <p class="text-gray-900 dark:text-white">{{ contact.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <p class="text-gray-900 dark:text-white">{{ contact.phone || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Company</label>
            <p class="text-gray-900 dark:text-white">{{ contact.company || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Service</label>
            <p class="text-gray-900 dark:text-white">{{ contact.service || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <span
              :class="getStatusBadgeClass(contact.status)"
              class="px-3 py-1 rounded-full text-xs font-semibold inline-block"
            >
              {{ getStatusLabel(contact.status) }}
            </span>
          </div>
        </div>

        <!-- Message -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Message</label>
          <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-900 dark:text-white whitespace-pre-wrap">
            {{ contact.message }}
          </div>
        </div>

        <!-- Reply Section -->
        <div v-if="contact.status !== 'replied'" class="border-t border-gray-200 dark:border-gray-600 pt-4">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reply</label>
          <textarea
            v-model="replyMessage"
            rows="4"
            placeholder="Write your reply here..."
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          ></textarea>
        </div>

        <!-- Previous Reply -->
        <div v-else class="border-t border-gray-200 dark:border-gray-600 pt-4">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Previous Reply</label>
          <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg text-gray-900 dark:text-white whitespace-pre-wrap">
            {{ contact.reply_message }}
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            Replied at: {{ formatDate(contact.replied_at) }}
          </p>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-gray-100 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-3">
        <button
          @click="$emit('close')"
          class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition"
        >
          Close
        </button>
        <button
          v-if="contact.status !== 'replied'"
          @click="submitReply"
          :disabled="!replyMessage.trim() || submitting"
          class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:bg-gray-400 transition"
        >
          <i v-if="submitting" class="fas fa-spinner fa-spin mr-2"></i>
          {{ submitting ? 'Sending...' : 'Send Reply' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  contact: {
    type: Object,
    required: true,
  },
  isOpen: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(['close', 'reply']);

const replyMessage = ref('');
const submitting = ref(false);

const submitReply = async () => {
  if (!replyMessage.value.trim()) return;

  submitting.value = true;
  try {
    emit('reply', {
      reply_message: replyMessage.value,
      status: 'replied',
    });
    replyMessage.value = '';
  } finally {
    submitting.value = false;
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
</script>

