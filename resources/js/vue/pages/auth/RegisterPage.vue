<template>
  <div class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
      <!-- Logo and Title -->
      <div class="text-center mb-8">
        <img :src="logoUrl" alt="Logo" class="h-16 w-auto mx-auto mb-4">
        <h1 class="text-3xl font-bold text-white mb-2">Create Account</h1>
        <p class="text-gray-400">Join the admin dashboard</p>
      </div>

      <!-- Register Card -->
      <Card class="mb-6">
        <!-- Error Alert -->
        <div v-if="authStore.error" class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
          <p class="text-red-400 text-sm">{{ authStore.error }}</p>
        </div>

        <!-- Success Alert -->
        <div v-if="successMessage" class="mb-4 p-4 bg-green-500 bg-opacity-20 border border-green-500 rounded-lg">
          <p class="text-green-400 text-sm">{{ successMessage }}</p>
        </div>

        <!-- Full Name Input -->
        <div class="mb-4">
          <Input
            v-model="form.name"
            type="text"
            label="Full Name"
            placeholder="John Doe"
            :error="errors.name"
            required
          />
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
        <div class="mb-4">
          <Input
            v-model="form.password"
            type="password"
            label="Password"
            placeholder="••••••••"
            :error="errors.password"
            required
          />
          <p class="text-xs text-gray-500 mt-1">At least 8 characters with uppercase, lowercase, and numbers</p>
        </div>

        <!-- Confirm Password Input -->
        <div class="mb-6">
          <Input
            v-model="form.passwordConfirmation"
            type="password"
            label="Confirm Password"
            placeholder="••••••••"
            :error="errors.passwordConfirmation"
            required
          />
        </div>

        <!-- Terms & Conditions -->
        <div class="mb-6">
          <label class="flex items-start gap-3 cursor-pointer">
            <input
              v-model="form.agreeToTerms"
              type="checkbox"
              class="w-4 h-4 rounded border-[#3b5265] bg-[#051824] text-[#27e9b5] cursor-pointer mt-1 flex-shrink-0"
            />
            <span class="text-sm text-gray-400">
              I agree to the
              <a href="#" class="text-[#27e9b5] hover:text-white transition-colors">Terms of Service</a>
              and
              <a href="#" class="text-[#27e9b5] hover:text-white transition-colors">Privacy Policy</a>
            </span>
          </label>
          <p v-if="errors.agreeToTerms" class="text-red-400 text-xs mt-2">{{ errors.agreeToTerms }}</p>
        </div>

        <!-- Sign Up Button -->
        <Button
          variant="primary"
          size="lg"
          class="w-full mb-4"
          :is-loading="authStore.isLoading"
          @click="handleRegister"
        >
          Create Account
        </Button>

        <!-- Divider -->
        <div class="relative mb-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-[#3b5265]"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-[#162936] text-gray-400">Or sign up with</span>
          </div>
        </div>

        <!-- Social Login Buttons -->
        <div class="space-y-3">
          <SocialLoginButton provider="google" />
          <SocialLoginButton provider="microsoft" />
          <SocialLoginButton provider="apple" />
        </div>
      </Card>

      <!-- Sign In Link -->
      <div class="text-center">
        <p class="text-gray-400">
          Already have an account?
          <router-link to="/admin/login" class="text-[#27e9b5] hover:text-white transition-colors font-semibold">
            Sign in
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/vue/stores/Auth/auth';
import Card from '@/vue/components/ui/Card.vue';
import Input from '@/vue/components/ui/Input.vue';
import Button from '@/vue/components/ui/Button.vue';
import SocialLoginButton from '@/vue/components/auth/SocialLoginButton.vue';

const router = useRouter();
const authStore = useAuthStore();

// Logo URL - using public asset
const logoUrl = '/RoayatAlMostaqbal.svg';

const successMessage = ref('');

const form = reactive({
  name: '',
  email: '',
  password: '',
  passwordConfirmation: '',
  agreeToTerms: false,
});

const errors = reactive({
  name: '',
  email: '',
  password: '',
  passwordConfirmation: '',
  agreeToTerms: '',
});

const validateForm = () => {
  // Reset errors
  errors.name = '';
  errors.email = '';
  errors.password = '';
  errors.passwordConfirmation = '';
  errors.agreeToTerms = '';

  // Validate name
  if (!form.name) {
    errors.name = 'Full name is required';
  } else if (form.name.length < 2) {
    errors.name = 'Name must be at least 2 characters';
  }

  // Validate email
  if (!form.email) {
    errors.email = 'Email is required';
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Please enter a valid email';
  }

  // Validate password
  if (!form.password) {
    errors.password = 'Password is required';
  } else if (form.password.length < 8) {
    errors.password = 'Password must be at least 8 characters';
  } else if (!/[A-Z]/.test(form.password)) {
    errors.password = 'Password must contain at least one uppercase letter';
  } else if (!/[a-z]/.test(form.password)) {
    errors.password = 'Password must contain at least one lowercase letter';
  } else if (!/[0-9]/.test(form.password)) {
    errors.password = 'Password must contain at least one number';
  }

  // Validate password confirmation
  if (!form.passwordConfirmation) {
    errors.passwordConfirmation = 'Please confirm your password';
  } else if (form.password !== form.passwordConfirmation) {
    errors.passwordConfirmation = 'Passwords do not match';
  }

  // Validate terms agreement
  if (!form.agreeToTerms) {
    errors.agreeToTerms = 'You must agree to the terms and conditions';
  }

  return !errors.name && !errors.email && !errors.password && !errors.passwordConfirmation && !errors.agreeToTerms;
};

const handleRegister = async () => {
  successMessage.value = '';

  if (!validateForm()) return;

  const result = await authStore.register({
    name: form.name,
    email: form.email,
    password: form.password,
    password_confirmation: form.passwordConfirmation,
  });

  if (result.success) {
    successMessage.value = 'Account created successfully! Redirecting to dashboard...';
    setTimeout(() => {
      router.push('/admin/dashboard');
    }, 1500);
  }
};
</script>

