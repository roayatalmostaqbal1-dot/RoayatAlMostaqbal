<template>
  <!-- Modal Overlay -->
  <div
    v-if="isOpen"
    class="fixed inset-0  bg-opacity-50 z-50 flex items-center justify-center p-4"
    @click.self="closeModal"
    @keydown.esc="closeModal"
  >
    <!-- Modal Container -->
    <div class="bg-[#162936] rounded-lg shadow-2xl max-w-md w-full border border-[#3b5265]">
      <!-- Modal Header -->
      <div class="bg-[#051824] border-b border-[#3b5265] px-6 py-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Change Password</h2>
        <button
          @click="closeModal"
          class="text-gray-400 hover:text-white transition-colors"
          aria-label="Close modal"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6 space-y-4">
        <!-- Current Password -->
        <div>
          <label class="block text-white font-semibold mb-2">
            Current Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              v-model="form.currentPassword"
              :type="showCurrentPassword ? 'text' : 'password'"
              placeholder="Enter your current password"
              class="w-full px-4 py-2.5 rounded-lg border-2 transition-all duration-300 bg-[#162936] text-white placeholder-gray-500 focus:outline-none"
              :class="errors.currentPassword ? 'border-red-500' : 'border-[#3b5265] focus:border-[#27e9b5]'"
            />
            <button
              type="button"
              @click="showCurrentPassword = !showCurrentPassword"
              class="absolute right-3 top-3 text-gray-400 hover:text-white"
            >
              <svg v-if="!showCurrentPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
          <p v-if="errors.currentPassword" class="text-red-500 text-sm mt-1">{{ errors.currentPassword }}</p>
        </div>

        <!-- New Password -->
        <div>
          <label class="block text-white font-semibold mb-2">
            New Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              v-model="form.newPassword"
              :type="showNewPassword ? 'text' : 'password'"
              placeholder="Enter new password"
              @input="validateNewPassword"
              class="w-full px-4 py-2.5 rounded-lg border-2 transition-all duration-300 bg-[#162936] text-white placeholder-gray-500 focus:outline-none"
              :class="errors.newPassword ? 'border-red-500' : 'border-[#3b5265] focus:border-[#27e9b5]'"
            />
            <button
              type="button"
              @click="showNewPassword = !showNewPassword"
              class="absolute right-3 top-3 text-gray-400 hover:text-white"
            >
              <svg v-if="!showNewPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
          <p v-if="errors.newPassword" class="text-red-500 text-sm mt-1">{{ errors.newPassword }}</p>

          <!-- Password Strength Indicator -->
          <div v-if="form.newPassword" class="mt-2">
            <div class="flex items-center gap-2">
              <div class="flex-1 h-2 bg-[#1f3a4a] rounded-full overflow-hidden">
                <div
                  class="h-full transition-all duration-300"
                  :class="passwordStrength.color"
                  :style="{ width: passwordStrength.percentage + '%' }"
                ></div>
              </div>
              <span class="text-xs font-semibold" :class="passwordStrength.textColor">
                {{ passwordStrength.label }}
              </span>
            </div>
          </div>
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-white font-semibold mb-2">
            Confirm New Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              v-model="form.confirmPassword"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="Confirm new password"
              class="w-full px-4 py-2.5 rounded-lg border-2 transition-all duration-300 bg-[#162936] text-white placeholder-gray-500 focus:outline-none"
              :class="errors.confirmPassword ? 'border-red-500' : 'border-[#3b5265] focus:border-[#27e9b5]'"
            />
            <button
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute right-3 top-3 text-gray-400 hover:text-white"
            >
              <svg v-if="!showConfirmPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
          <p v-if="errors.confirmPassword" class="text-red-500 text-sm mt-1">{{ errors.confirmPassword }}</p>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="bg-[#051824] border-t border-[#3b5265] px-6 py-4 flex items-center justify-end gap-3">
        <Button
          variant="secondary"
          @click="closeModal"
          :disabled="isLoading"
        >
          Cancel
        </Button>
        <Button
          variant="primary"
          @click="handleSubmit"
          :disabled="!isFormValid || isLoading"
          :is-loading="isLoading"
        >
          Change Password
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Button from '../ui/Button.vue';
import { useAuthStore } from '../../stores/Auth/auth';
import { useToastStore } from '../../stores/toastStore';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close']);

const authStore = useAuthStore();
const toastStore = useToastStore();

const form = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
});

const errors = ref({});
const isLoading = ref(false);
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const passwordStrength = computed(() => {
  const pwd = form.value.newPassword;
  let strength = 0;

  if (pwd.length >= 8) strength++;
  if (/[a-z]/.test(pwd)) strength++;
  if (/[A-Z]/.test(pwd)) strength++;
  if (/[0-9]/.test(pwd)) strength++;
  if (/[^a-zA-Z0-9]/.test(pwd)) strength++;

  const levels = [
    { label: 'Weak', color: 'bg-red-500', textColor: 'text-red-400', percentage: 20 },
    { label: 'Fair', color: 'bg-yellow-500', textColor: 'text-yellow-400', percentage: 40 },
    { label: 'Good', color: 'bg-blue-500', textColor: 'text-blue-400', percentage: 60 },
    { label: 'Strong', color: 'bg-green-500', textColor: 'text-green-400', percentage: 80 },
    { label: 'Very Strong', color: 'bg-[#27e9b5]', textColor: 'text-[#27e9b5]', percentage: 100 },
  ];

  return levels[Math.min(strength, 4)];
});

const isFormValid = computed(() => {
  return (
    form.value.currentPassword &&
    form.value.newPassword &&
    form.value.confirmPassword &&
    !errors.value.currentPassword &&
    !errors.value.newPassword &&
    !errors.value.confirmPassword
  );
});

const validateNewPassword = () => {
  errors.value.newPassword = '';

  if (form.value.newPassword.length < 8) {
    errors.value.newPassword = 'Password must be at least 8 characters';
    return;
  }
  if (!/[a-z]/.test(form.value.newPassword)) {
    errors.value.newPassword = 'Password must contain lowercase letters';
    return;
  }
  if (!/[A-Z]/.test(form.value.newPassword)) {
    errors.value.newPassword = 'Password must contain uppercase letters';
    return;
  }
  if (!/[0-9]/.test(form.value.newPassword)) {
    errors.value.newPassword = 'Password must contain numbers';
    return;
  }
  if (!/[^a-zA-Z0-9]/.test(form.value.newPassword)) {
    errors.value.newPassword = 'Password must contain special characters';
    return;
  }
  if (form.value.newPassword === form.value.currentPassword) {
    errors.value.newPassword = 'New password cannot be the same as current password';
    return;
  }

  if (form.value.confirmPassword && form.value.newPassword !== form.value.confirmPassword) {
    errors.value.confirmPassword = 'Passwords do not match';
  } else if (form.value.confirmPassword) {
    errors.value.confirmPassword = '';
  }
};

const handleSubmit = async () => {
  errors.value = {};

  // Validate confirm password
  if (form.value.newPassword !== form.value.confirmPassword) {
    errors.value.confirmPassword = 'Passwords do not match';
    return;
  }

  isLoading.value = true;

  try {
    const result = await authStore.changePassword(
      form.value.currentPassword,
      form.value.newPassword,
      form.value.confirmPassword
    );

    if (result.success) {
      toastStore.success('Success', 'Password changed successfully');
      closeModal();
    } else {
      if (result.errors) {
        errors.value = result.errors;
      } else {
        toastStore.error('Error', result.error || 'Failed to change password');
      }
    }
  } catch (err) {
    toastStore.error('Error', 'An unexpected error occurred');
  } finally {
    isLoading.value = false;
  }
};

const closeModal = () => {
  form.value = { currentPassword: '', newPassword: '', confirmPassword: '' };
  errors.value = {};
  showCurrentPassword.value = false;
  showNewPassword.value = false;
  showConfirmPassword.value = false;
  emit('close');
};
</script>

