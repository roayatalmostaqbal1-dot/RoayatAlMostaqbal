<template>
  <div class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-[#162936] rounded-lg p-8 max-w-md w-full mx-4 border border-[#3b5265]">
      <h2 class="text-2xl font-bold text-white mb-2">Two-Factor Authentication</h2>
      <p class="text-gray-400 mb-6">Enter the 6-digit code from your authenticator app</p>
      <form @submit.prevent="verifyCode">
        <div class="mb-6">
          <label class="block text-white font-semibold mb-2">Authentication Code</label>
          <input
            v-model="totpCode"
            type="text"
            inputmode="numeric"
            maxlength="6"
            placeholder="000000"
            class="w-full px-4 py-3 rounded-lg border-2 border-[#3b5265] bg-[#051824] text-white placeholder-gray-500 focus:outline-none focus:border-[#27e9b5] text-center text-2xl tracking-widest"
          />
        </div>

        <div v-if="error" class="mb-4 p-3 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400 text-sm">
          {{ error }}
        </div>

        <!-- Verify Button -->
        <button
          type="submit"
          :disabled="totpCode.length !== 6 || isLoading"
          class="w-full px-4 py-3 rounded-lg font-semibold transition-all duration-300 mb-3"
          :class="[
            totpCode.length === 6 && !isLoading
              ? 'bg-[#27e9b5] text-[#051824] hover:bg-[#1fc9a0]'
              : 'bg-gray-600 text-gray-400 cursor-not-allowed'
          ]"
        >
          <span v-if="!isLoading">Verify Code</span>
          <span v-else class="flex items-center justify-center gap-2">
            <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-current"></div>
            Verifying...
          </span>
        </button>
      </form>

      <!-- Recovery Code Option -->
      <button
        @click="showRecoveryCodeInput = !showRecoveryCodeInput"
        class="w-full text-center text-[#27e9b5] hover:text-[#1fc9a0] text-sm font-semibold transition-colors"
      >
        {{ showRecoveryCodeInput ? 'Use authenticator code instead' : 'Use recovery code instead' }}
      </button>

      <!-- Recovery Code Input -->
      <div v-if="showRecoveryCodeInput" class="mt-4 pt-4 border-t border-[#3b5265]">
        <label class="block text-white font-semibold mb-2">Recovery Code</label>
        <input
          v-model="recoveryCode"
          type="text"
          placeholder="XXXXXXXX"
          class="w-full px-4 py-3 rounded-lg border-2 border-[#3b5265] bg-[#051824] text-white placeholder-gray-500 focus:outline-none focus:border-[#27e9b5] mb-3"
        />
        <button
          @click="verifyRecoveryCode"
          :disabled="!recoveryCode || isLoading"
          class="w-full px-4 py-3 rounded-lg font-semibold transition-all duration-300"
          :class="[
            recoveryCode && !isLoading
              ? 'bg-[#27e9b5] text-[#051824] hover:bg-[#1fc9a0]'
              : 'bg-gray-600 text-gray-400 cursor-not-allowed'
          ]"
        >
          <span v-if="!isLoading">Verify Recovery Code</span>
          <span v-else class="flex items-center justify-center gap-2">
            <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-current"></div>
            Verifying...
          </span>
        </button>
      </div>

      <!-- Cancel Button -->
      <button
        @click="$emit('cancel')"
        class="w-full mt-4 px-4 py-3 rounded-lg font-semibold text-gray-400 hover:text-white hover:bg-[#1f3a4a] transition-all duration-300"
      >
        Cancel
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../stores/Auth/auth';
import { useToastStore } from '../../stores/toastStore';

const props = defineProps({
  userId: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['verified', 'cancel']);

const authStore = useAuthStore();
const toastStore = useToastStore();
const totpCode = ref('');
const recoveryCode = ref('');
const showRecoveryCodeInput = ref(false);
const error = ref('');
const isLoading = ref(false);

const verifyCode = async () => {
  if (totpCode.value.length !== 6) return;

  isLoading.value = true;
  error.value = '';

  const result = await authStore.verify(totpCode.value);

  if (result.success) {
    toastStore.success('Success', '2FA verification successful! Logging you in...');
    // Emit verified event - LoginPage will handle the redirect
    emit('verified');
  } else {
    error.value = result.error || 'Invalid code. Please try again.';
    toastStore.error('Error', error.value);
    totpCode.value = '';
  }

  isLoading.value = false;
};

const verifyRecoveryCode = async () => {
  if (!recoveryCode.value) return;

  isLoading.value = true;
  error.value = '';

  const result = await authStore.verify(recoveryCode.value);

  if (result.success) {
    toastStore.success('Success', 'Recovery code verified! Logging you in...');
    // Emit verified event - LoginPage will handle the redirect
    emit('verified');
  } else {
    error.value = result.error || 'Invalid recovery code. Please try again.';
    toastStore.error('Error', error.value);
    recoveryCode.value = '';
  }

  isLoading.value = false;
};
</script>

