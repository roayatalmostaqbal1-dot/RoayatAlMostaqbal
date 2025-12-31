<template>
    <div
        class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <img :src="logoUrl" alt="Logo" class="h-16 w-auto mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white mb-2">Forgot Password?</h1>
                <p class="text-gray-400">Enter your email to reset your password</p>
            </div>

            <!-- Forgot Password Card -->
            <Card class="mb-6">
                <form @submit.prevent="handleSubmit" v-if="!emailSent">
                    <!-- Error Alert -->
                    <div v-if="error" class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
                        <p class="text-white text-sm">{{ error }}</p>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-6">
                        <Input v-model="form.email" type="email" label="Email Address" placeholder="you@example.com"
                            :error="errors.email" required />
                    </div>

                    <!-- Submit Button -->
                    <Button type="submit" variant="primary" size="lg" class="w-full mb-4" :is-loading="isLoading">
                        Send Reset Link
                    </Button>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <router-link to="/login" class="text-sm text-[#27e9b5] hover:text-white transition-colors">
                            Back to Login
                        </router-link>
                    </div>
                </form>

                <!-- Success Message -->
                <div v-else class="text-center py-4">
                    <div
                        class="w-16 h-16 rounded-full bg-green-500 bg-opacity-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2">Check Your Email</h3>
                    <p class="text-gray-400 mb-6">
                        If an account exists with <span class="text-[#27e9b5]">{{ form.email }}</span>, you will receive
                        a password reset link shortly.
                    </p>

                    <div class="bg-[#051824] bg-opacity-50 border border-[#3b5265] rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-400 mb-2">Didn't receive the email?</p>
                        <ul class="text-xs text-gray-500 text-left space-y-1">
                            <li>• Check your spam or junk folder</li>
                            <li>• Make sure the email address is correct</li>
                            <li>• Wait a few minutes, delivery may take some time</li>
                        </ul>
                    </div>

                    <Button variant="secondary" size="lg" class="w-full mb-3" @click="resendEmail"
                        :is-loading="isLoading">
                        Resend Link
                    </Button>

                    <router-link to="/login" class="text-sm text-[#27e9b5] hover:text-white transition-colors">
                        Back to Login
                    </router-link>
                </div>
            </Card>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import Card from '@/vue/components/ui/Card.vue';
import Input from '@/vue/components/ui/Input.vue';
import Button from '@/vue/components/ui/Button.vue';
import axios from 'axios';

const router = useRouter();

const logoUrl = '/RoayatAlMostaqbal.svg';

const isLoading = ref(false);
const error = ref('');
const emailSent = ref(false);

const form = reactive({
    email: '',
});

const errors = reactive({
    email: '',
});

const validateForm = () => {
    errors.email = '';

    if (!form.email) {
        errors.email = 'Email address is required';
        return false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'Please enter a valid email address';
        return false;
    }

    return true;
};

const handleSubmit = async () => {
    if (!validateForm()) return;

    isLoading.value = true;
    error.value = '';

    try {
        const response = await axios.post('/api/v1/auth/password/email', {
            email: form.email,
        });

        if (response.data.status === 'success') {
            emailSent.value = true;
        }
    } catch (err) {
        console.error('Forgot password error:', err);

        if (err.response?.data?.errors?.email) {
            errors.email = err.response.data.errors.email[0];
        } else {
            error.value = err.response?.data?.message || 'An error occurred while processing your request';
        }
    } finally {
        isLoading.value = false;
    }
};

const resendEmail = async () => {
    emailSent.value = false;
    await handleSubmit();
};
</script>
