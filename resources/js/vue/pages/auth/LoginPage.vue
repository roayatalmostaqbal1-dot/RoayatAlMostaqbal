<template>
  <div class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
      <!-- Logo and Title -->
      <div class="text-center mb-8">
        <img :src="logoUrl" alt="Logo" class="h-16 w-auto mx-auto mb-4">
        <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
        <p class="text-gray-400">Sign in to your account</p>
      </div>

      <!-- Login Card -->
      <Card class="mb-6">
        <!-- Error Alert -->
        <div v-if="authStore.error" class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
          <p class="text-red-400 text-sm">{{ authStore.error }}</p>
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
          variant="primary"
          size="lg"
          class="w-full mb-4"
          :is-loading="authStore.isLoading"
          @click="handleLogin"
        >
          Sign In
        </Button>

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
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import Card from '../../components/ui/Card.vue';
import Input from '../../components/ui/Input.vue';
import Button from '../../components/ui/Button.vue';
import SocialLoginButton from '../../components/auth/SocialLoginButton.vue';

const router = useRouter();
const authStore = useAuthStore();

// Logo URL - using public asset
const logoUrl = '/RoayatAlMostaqbal.svg';

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

const handleLogin = async () => {
  if (!validateForm()) return;

  const result = await authStore.login(form.email, form.password);

  if (result.success) {
    router.push('/admin/dashboard');
  }
};
</script>

