<template>
  <div class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
      <!-- Loading State -->
      <div v-if="isLoading" class="text-center">
        <svg class="animate-spin h-12 w-12 text-[#27e9b5] mx-auto mb-4">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-400 mt-4">Completing authentication...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center">
        <div class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
          <p class="text-red-400">{{ error }}</p>
        </div>
        <Button
          variant="primary"
          size="lg"
          class="w-full"
          @click="closeWindow"
        >
          Close
        </Button>
      </div>

      <!-- Success State -->
      <div v-else class="text-center">
        <div class="mb-4 p-4 bg-green-500 bg-opacity-20 border border-green-500 rounded-lg">
          <p class="text-green-400">Authentication successful!</p>
        </div>
        <p class="text-gray-400">You can close this window.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Button from '../../components/ui/Button.vue';

const isLoading = ref(true);
const error = ref(null);

const closeWindow = () => {
  window.close();
};

const getQueryParam = (name) => {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
};

onMounted(async () => {
  try {
    // Get the token and user data from query parameters
    const token = getQueryParam('token');
    const userData = getQueryParam('user');
    const errorMessage = getQueryParam('error');

    if (errorMessage) {
      error.value = decodeURIComponent(errorMessage);
      isLoading.value = false;
      return;
    }

    if (!token || !userData) {
      error.value = 'Invalid authentication response';
      isLoading.value = false;
      return;
    }

    // Parse user data
    const user = JSON.parse(decodeURIComponent(userData));

    // Send success message to parent window
    if (window.opener) {
      window.opener.postMessage(
        {
          type: 'SOCIAL_AUTH_SUCCESS',
          token,
          data: user,
        },
        window.location.origin
      );
    }

    isLoading.value = false;

    // Close window after 2 seconds
    setTimeout(() => {
      window.close();
    }, 2000);
  } catch (err) {
    error.value = 'Failed to process authentication response';
    isLoading.value = false;
  }
});
</script>

