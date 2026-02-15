<template>
  <div v-if="isOpen" class="fixed inset-0 bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-[#1a2332] rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-[#3b5265]">
        <h2 class="text-xl font-bold text-white">OAuth2 Client Details</h2>
        <button
          @click="$emit('close')"
          class="text-gray-400 hover:text-white transition"
        >
          ✕
        </button>
      </div>

      <!-- Content -->
      <div v-if="client" class="p-6 space-y-6">
        <!-- Client Name -->
        <div>
          <label class="block text-sm font-semibold text-gray-300 mb-2">Application Name</label>
          <p class="text-white text-lg">{{ client.name }}</p>
        </div>

        <!-- Client ID -->
        <div>
          <label class="block text-sm font-semibold text-gray-300 mb-2">Client ID</label>
          <div class="flex items-center gap-2">
            <code class="flex-1 bg-[#162936] text-[#27e9b5] px-3 py-2 rounded font-mono text-sm break-all">
              {{ client.client_id }}
            </code>
            <button
              @click="copyToClipboard(client.client_id, 'Client ID')"
              class="bg-[#27e9b5] hover:bg-[#1fb89f] text-[#0a1419] px-3 py-2 rounded font-semibold transition"
              title="Copy to clipboard"
            >
              📋 Copy
            </button>
          </div>
        </div>

        <!-- Client Secret (only if available) -->
        <div v-if="client.client_secret">
          <label class="block text-sm font-semibold text-gray-300 mb-2">Client Secret</label>
          <div class="flex items-center gap-2">
            <code class="flex-1 bg-[#162936] text-[#27e9b5] px-3 py-2 rounded font-mono text-sm break-all">
              {{ showSecret ? client.client_secret : '••••••••••••••••••••' }}
            </code>
            <button
              @click="showSecret = !showSecret"
              class="bg-[#3b5265] hover:bg-[#4a6580] text-white px-3 py-2 rounded font-semibold transition"
              title="Toggle visibility"
            >
              {{ showSecret ? '🙈 Hide' : '👁️ Show' }}
            </button>
            <button
              @click="copyToClipboard(client.client_secret, 'Client Secret')"
              class="bg-[#27e9b5] hover:bg-[#1fb89f] text-[#0a1419] px-3 py-2 rounded font-semibold transition"
              title="Copy to clipboard"
            >
              📋 Copy
            </button>
          </div>
          <p class="text-xs text-yellow-400 mt-2">⚠️ Save this secret securely. You won't be able to see it again!</p>
        </div>

        <!-- Redirect URI -->
        <div>
          <label class="block text-sm font-semibold text-gray-300 mb-2">Redirect URI</label>
          <code class="block bg-[#162936] text-[#27e9b5] px-3 py-2 rounded font-mono text-sm break-all">
            {{ client.redirect_uris }}
          </code>
        </div>

        <!-- Client Type -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Type</label>
            <span class="inline-block bg-[#3b5265] text-[#27e9b5] px-3 py-1 rounded text-sm">
              {{ client.confidential ? 'Confidential' : 'Public' }}
            </span>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Status</label>
            <span :class="[
              'inline-block px-3 py-1 rounded text-sm',
              client.revoked ? 'bg-red-500 bg-opacity-20 text-white' : 'bg-green-500 bg-opacity-20 text-white'
            ]">
              {{ client.revoked ? 'Revoked' : 'Active' }}
            </span>
          </div>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <label class="block text-gray-400 mb-1">Created</label>
            <p class="text-gray-300">{{ formatDate(client.created_at) }}</p>
          </div>
          <div>
            <label class="block text-gray-400 mb-1">Updated</label>
            <p class="text-gray-300">{{ formatDate(client.updated_at) }}</p>
          </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-500 bg-opacity-10 border border-blue-500 rounded p-4">
          <p class="text-blue-300 text-sm">
            <strong>ℹ️ Note:</strong> Use the Client ID and Client Secret to authenticate your application with our OAuth2 server.
          </p>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-between p-6 border-t border-[#3b5265]">
        <button
          v-if="!client?.revoked"
          @click="$emit('regenerate', client)"
          :disabled="isLoading"
          class="bg-yellow-600 hover:bg-yellow-700 disabled:opacity-50 text-white px-4 py-2 rounded font-semibold transition"
        >
          🔄 Regenerate Secret
        </button>
        <button
          @click="$emit('close')"
          class="bg-[#3b5265] hover:bg-[#4a6580] text-white px-4 py-2 rounded font-semibold transition"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  client: {
    type: Object,
    default: null,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['close', 'regenerate']);

const showSecret = ref(false);

const copyToClipboard = (text, label) => {
  navigator.clipboard.writeText(text).then(() => {
    // Toast notification would be nice here
    alert(`${label} copied to clipboard!`);
  }).catch(() => {
    alert(`Failed to copy ${label}`);
  });
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>

