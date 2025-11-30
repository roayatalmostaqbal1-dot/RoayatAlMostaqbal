<template>
  <button
    @click="handleSocialLogin"
    :disabled="isLoading"
    :class="[
      'w-full px-4 py-3 rounded-lg border-2 border-[#3b5265] bg-[#162936] text-white',
      'hover:border-[#27e9b5] hover:bg-[#1f3a4a] transition-all duration-300',
      'flex items-center justify-center gap-3 font-semibold',
      'disabled:opacity-50 disabled:cursor-not-allowed',
    ]"
  >
    <svg
      v-if="isLoading"
      class="animate-spin h-5 w-5"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <SocialIcons v-else :name="provider" class="h-5 w-5" />
    <span>{{ label }}</span>
  </button>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toastStore';
import SocialIcons from '../icons/SocialIcons.vue';

const props = defineProps({
  provider: {
    type: String,
    required: true,
    validator: (value) => ['google', 'facebook', 'github', 'linkedin', 'twitter', 'microsoft', 'apple'].includes(value),
  },
});

const router = useRouter();
const authStore = useAuthStore();
const toastStore = useToastStore();
const isLoading = ref(false);

const label = computed(() => {
  const labels = {
    google: 'Google',
    facebook: 'Facebook',
    github: 'GitHub',
    linkedin: 'LinkedIn',
    twitter: 'Twitter',
    microsoft: 'Microsoft',
    apple: 'Apple',
  };
  return `Sign in with ${labels[props.provider]}`;
});

const handleSocialAuthSuccess = (event) => {
  if (event.detail && event.detail.token) {
    isLoading.value = false;
    toastStore.success('Success', 'Social login successful! Redirecting...');
    // Redirect to dashboard after successful social auth
    router.push('/dashboard');
  }
};

const handleSocialAuthCancelled = () => {
  // User cancelled the social login popup
  isLoading.value = false;
  toastStore.error('Cancelled', 'Social login was cancelled');
};

const handleSocialAuthError = (event) => {
  // Social login error occurred
  isLoading.value = false;
  if (event.detail && event.detail.error) {
    toastStore.error('Error', event.detail.error);
  } else {
    toastStore.error('Error', 'Social login failed');
  }
};

const handleSocialLogin = () => {
  isLoading.value = true;
  authStore.socialAuthRedirect(props.provider);
};

onMounted(() => {
  window.addEventListener('social-auth-success', handleSocialAuthSuccess);
  window.addEventListener('social-auth-cancelled', handleSocialAuthCancelled);
  window.addEventListener('social-auth-error', handleSocialAuthError);
});

onUnmounted(() => {
  window.removeEventListener('social-auth-success', handleSocialAuthSuccess);
  window.removeEventListener('social-auth-cancelled', handleSocialAuthCancelled);
  window.removeEventListener('social-auth-error', handleSocialAuthError);
});
</script>

