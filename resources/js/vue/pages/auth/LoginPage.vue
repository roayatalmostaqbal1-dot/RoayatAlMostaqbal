<template>
  <div class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
    <!-- 2FA Verification Modal -->
    <TwoFactorVerification
      v-if="show2FAModal"
      :user-id="twoFactorUserId"
      @verified="handle2FAVerified"
      @cancel="handle2FACancel"
    />

    <div class="w-full max-w-md">
      <!-- Logo and Title -->
      <div class="text-center mb-8">
        <img :src="logoUrl" alt="Logo" class="h-16 w-auto mx-auto mb-4">
        <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
        <p class="text-gray-400">Sign in to your account</p>
      </div>

      <!-- Login Card -->
      <Card class="mb-6">
        <form @submit.prevent="handleLogin">
          <!-- Error Alert -->
          <div v-if="authStore.errors" class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
            <p class="text-red-400 text-sm">{{ authStore.errors }}</p>
          </div>

          <!-- Email Input -->
          <div class="mb-4">
            <Input
              v-model="form.email"
              type="email"
              label="Email Address"
              placeholder="you@example.com"
              :error="errors.email"
              required
            />
          </div>

          <!-- Password Input -->
          <div class="mb-6">
            <Input
              v-model="form.password"
              type="password"
              label="Password"
              placeholder="••••••••"
              :error="errors.password"
              required
            />
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                v-model="form.rememberMe"
                type="checkbox"
                class="w-4 h-4 rounded border-[#3b5265] bg-[#051824] text-[#27e9b5] cursor-pointer"
              />
              <span class="text-sm text-gray-400">Remember me</span>
            </label>
            <a href="#" class="text-sm text-[#27e9b5] hover:text-white transition-colors">
              Forgot password?
            </a>
          </div>

          <!-- Login Button -->
          <Button
            type="submit"
            variant="primary"
            size="lg"
            class="w-full mb-4"
            :is-loading="authStore.isLoading"
          >
            Sign In
          </Button>
        </form>

        <!-- Divider -->
        <div class="relative mb-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-[#3b5265]"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-[#162936] text-gray-400">Or continue with</span>
          </div>
        </div>

        <!-- Social Login Buttons -->
        <div class="space-y-3">
          <SocialLoginButton provider="google" />
          <SocialLoginButton provider="microsoft" />
          <SocialLoginButton provider="apple" />
        </div>
      </Card>

      <!-- Sign Up Link -->
      <div class="text-center">
        <p class="text-gray-400">
          Don't have an account?
          <router-link to="/register" class="text-[#27e9b5] hover:text-white transition-colors font-semibold">
            Sign up
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import Card from '../../components/ui/Card.vue';
import Input from '../../components/ui/Input.vue';
import Button from '../../components/ui/Button.vue';
import SocialLoginButton from '../../components/auth/SocialLoginButton.vue';
import TwoFactorVerification from '../../components/auth/TwoFactorVerification.vue';

const router = useRouter();
const authStore = useAuthStore();

// Logo URL - using public asset
const logoUrl = '/RoayatAlMostaqbal.svg';

// 2FA state
const show2FAModal = ref(false);
const twoFactorUserId = ref(null);
const isSocialLogin2FA = ref(false);

const form = reactive({
  email: '',
  password: '',
  rememberMe: false,
});

const errors = reactive({
  email: '',
  password: '',
});

const validateForm = () => {
  errors.email = '';
  errors.password = '';

  if (!form.email) {
    errors.email = 'Email is required';
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Please enter a valid email';
  }

  if (!form.password) {
    errors.password = 'Password is required';
  } else if (form.password.length < 6) {
    errors.password = 'Password must be at least 6 characters';
  }

  return !errors.email && !errors.password;
};

const handleLogin = async (event) => {
  // Prevent form submission and page refresh
  if (event) {
    event.preventDefault();
  }

  if (!validateForm()) return;

  // Clear any previous auth errors before attempting login
  authStore.clearError();

  const result = await authStore.login(form.email, form.password);

  if (result.success) {
    if (result.twoFactor) {
      show2FAModal.value = true;
      twoFactorUserId.value = result.data.user_id;
      return;
    }

    // Clear password field on successful login for security
    form.password = '';
    router.push('/dashboard');
  } else {
    // Error is automatically set in authStore.error by the login action
    // The error alert will display due to v-if="authStore.error" binding
    // Keep the email field filled for user convenience, but clear password for security
    form.password = '';
  }
};

const handle2FAVerified = () => {
  // Check if this was social login 2FA before resetting the flag
  const wasSocialLogin = isSocialLogin2FA.value;

  show2FAModal.value = false;
  twoFactorUserId.value = null;
  isSocialLogin2FA.value = false;

  // If this was social login 2FA, redirect to dashboard
  // Otherwise, the regular login flow will handle it
  if (wasSocialLogin) {
    router.push('/dashboard');
  } else {
    router.push('/admin/dashboard');
  }
};

const handle2FACancel = () => {
  show2FAModal.value = false;
  twoFactorUserId.value = null;
  isSocialLogin2FA.value = false;
  authStore.resetTwoFactor();
};

// Handle social login 2FA requirement
const handleSocialAuth2FARequired = (event) => {
  if (event.detail && event.detail.user_id) {
    isSocialLogin2FA.value = true;
    twoFactorUserId.value = event.detail.user_id;
    show2FAModal.value = true;
  }
};

onMounted(() => {
  window.addEventListener('social-auth-2fa-required', handleSocialAuth2FARequired);
});

onUnmounted(() => {
  window.removeEventListener('social-auth-2fa-required', handleSocialAuth2FARequired);
});
</script>

