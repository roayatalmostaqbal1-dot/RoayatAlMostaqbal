<template>
  <div v-if="isOpen" class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-[#1a2332] rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto border border-[#3b5265]">
      <!-- Header -->
      <div class="sticky top-0 bg-[#162936] px-6 py-4 border-b border-[#3b5265] flex justify-between items-center">
        <h2 class="text-xl font-bold text-white">Contact Details</h2>
        <button
          @click="$emit('close')"
          class="text-gray-400 hover:text-white transition"
        >
          ✕
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="p-6 text-center">
        <svg class="animate-spin h-8 w-8 text-[#27e9b5] mx-auto">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-2">Loading...</p>
      </div>

      <!-- Content -->
      <div v-else class="p-6 space-y-4">
        <!-- Contact Info -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Name</label>
            <p class="text-white">{{ contact.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
            <p class="text-white">{{ contact.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Phone</label>
            <p class="text-gray-300">{{ contact.phone || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Company</label>
            <p class="text-gray-300">{{ contact.company || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Service</label>
            <p class="text-gray-300">{{ contact.service || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Status</label>
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
          <label class="block text-sm font-semibold text-gray-300 mb-2">Message</label>
          <div class="bg-[#162936] p-4 rounded-lg text-gray-300 whitespace-pre-wrap border border-[#3b5265]">
            {{ contact.message }}
          </div>
        </div>

        <!-- Reply Section -->
        <div v-if="contact.status !== 'replied'" class="border-t border-[#3b5265] pt-4">
          <label class="block text-sm font-semibold text-gray-300 mb-2">Reply</label>
          <textarea
            v-model="replyMessage"
            rows="4"
            placeholder="Write your reply here..."
            class="w-full px-4 py-2 border border-[#3b5265] rounded-lg bg-[#162936] text-white placeholder-gray-500 focus:ring-2 focus:ring-[#27e9b5] focus:border-[#27e9b5]"
          ></textarea>
        </div>

        <!-- Previous Reply -->
        <div v-else class="border-t border-[#3b5265] pt-4">
          <label class="block text-sm font-semibold text-gray-300 mb-2">Previous Reply</label>
          <div class="bg-green-500 bg-opacity-10 p-4 rounded-lg text-gray-300 whitespace-pre-wrap border border-green-500 border-opacity-30">
            {{ contact.reply_message }}
          </div>
          <p class="text-sm text-gray-400 mt-2">
            Replied at: {{ formatDate(contact.replied_at) }}
          </p>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-[#162936] px-6 py-4 border-t border-[#3b5265] flex justify-end gap-3">
        <button
          @click="$emit('close')"
          class="px-4 py-2 text-gray-300 bg-[#3b5265] hover:bg-[#4a6580] rounded-lg transition font-semibold"
        >
          Close
        </button>
        <button
          v-if="contact.status !== 'replied'"
          @click="submitReply"
          :disabled="!replyMessage.trim() || submitting || isLoading"
          class="px-4 py-2 bg-[#27e9b5] hover:bg-[#1fd4a0] text-[#051824] rounded-lg transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
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
  isLoading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'reply', 'updated']);

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
    emit('updated');
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
</script>

