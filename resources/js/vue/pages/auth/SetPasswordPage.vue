<template>
    <div
        class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <img :src="logoUrl" alt="Logo" class="h-16 w-auto mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white mb-2">Set Your Password</h1>
                <p class="text-gray-400">Create a secure password for your account</p>
            </div>

            <!-- User Info Card -->
            <Card class="mb-4">
                <div class="flex items-center gap-3 p-2">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-[#27e9b5] to-[#20c997] flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ userInitial }}</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ userData.name }}</p>
                        <p class="text-gray-400 text-sm">{{ userData.email }}</p>
                    </div>
                </div>
            </Card>

            <!-- Set Password Card -->
            <Card>
                <form @submit.prevent="handleSetPassword">
                    <!-- Error Alert -->
                    <div v-if="error" class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
                        <p class="text-white text-sm">{{ error }}</p>
                    </div>

                    <!-- Success Alert -->
                    <div v-if="success" class="mb-4 p-4 bg-green-500 bg-opacity-20 border border-green-500 rounded-lg">
                        <p class="text-white text-sm">{{ success }}</p>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4">
                        <Input v-model="form.password" type="password" label="New Password" placeholder="••••••••"
                            :error="errors.password" required />

                        <!-- Password Strength Indicator -->
                        <div v-if="form.password" class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div v-for="i in 4" :key="i" class="h-1 flex-1 rounded-full transition-colors"
                                    :class="i <= passwordStrength ? strengthColors[passwordStrength] : 'bg-gray-600'">
                                </div>
                            </div>
                            <p class="text-xs" :class="strengthTextColors[passwordStrength]">
                                {{ strengthLabels[passwordStrength] }}
                            </p>
                        </div>

                        <!-- Password Requirements -->
                        <div class="mt-3 space-y-1">
                            <p class="text-xs" :class="requirements.minLength ? 'text-green-400' : 'text-gray-400'">
                                ✓ At least 8 characters
                            </p>
                            <p class="text-xs" :class="requirements.hasLower ? 'text-green-400' : 'text-gray-400'">
                                ✓ At least one lowercase letter
                            </p>
                            <p class="text-xs" :class="requirements.hasUpper ? 'text-green-400' : 'text-gray-400'">
                                ✓ At least one uppercase letter
                            </p>
                            <p class="text-xs" :class="requirements.hasNumber ? 'text-green-400' : 'text-gray-400'">
                                ✓ At least one number
                            </p>
                            <p class="text-xs" :class="requirements.hasSpecial ? 'text-green-400' : 'text-gray-400'">
                                ✓ At least one special character
                            </p>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-6">
                        <Input v-model="form.password_confirmation" type="password" label="Confirm Password"
                            placeholder="••••••••" :error="errors.password_confirmation" required />
                    </div>

                    <!-- Submit Button -->
                    <Button type="submit" variant="primary" size="lg" class="w-full" :is-loading="isLoading"
                        :disabled="!isFormValid">
                        Set Password & Continue
                    </Button>
                </form>
            </Card>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/Auth/auth';
import Card from '../../components/ui/Card.vue';
import Input from '../../components/ui/Input.vue';
import Button from '../../components/ui/Button.vue';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const logoUrl = '/RoayatAlMostaqbal.svg';

const userData = ref({
    id: null,
    name: '',
    email: '',
});

const token = ref('');
const isLoading = ref(false);
const error = ref('');
const success = ref('');

const form = reactive({
    password: '',
    password_confirmation: '',
});

const errors = reactive({
    password: '',
    password_confirmation: '',
});

// Password requirements
const requirements = computed(() => ({
    minLength: form.password.length >= 8,
    hasLower: /[a-z]/.test(form.password),
    hasUpper: /[A-Z]/.test(form.password),
    hasNumber: /[0-9]/.test(form.password),
    hasSpecial: /[^a-zA-Z0-9]/.test(form.password),
}));

// Password strength calculation
const passwordStrength = computed(() => {
    const reqs = requirements.value;
    const count = Object.values(reqs).filter(Boolean).length;

    if (count <= 2) return 1;
    if (count === 3) return 2;
    if (count === 4) return 3;
    return 4;
});

const strengthColors = {
    1: 'bg-red-500',
    2: 'bg-orange-500',
    3: 'bg-yellow-500',
    4: 'bg-green-500',
};

const strengthTextColors = {
    1: 'text-red-400',
    2: 'text-orange-400',
    3: 'text-yellow-400',
    4: 'text-green-400',
};

const strengthLabels = {
    1: 'Very Weak',
    2: 'Weak',
    3: 'Medium',
    4: 'Strong',
};

const userInitial = computed(() => {
    return userData.value.name ? userData.value.name.charAt(0).toUpperCase() : '?';
});

const isFormValid = computed(() => {
    return Object.values(requirements.value).every(Boolean) &&
        form.password === form.password_confirmation &&
        form.password.length > 0;
});

const validateForm = () => {
    errors.password = '';
    errors.password_confirmation = '';

    if (!form.password) {
        errors.password = 'Password is required';
        return false;
    }

    if (!Object.values(requirements.value).every(Boolean)) {
        errors.password = 'Password does not meet all requirements';
        return false;
    }

    if (form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Passwords do not match';
        return false;
    }

    return true;
};

const handleSetPassword = async () => {
    if (!validateForm()) return;

    isLoading.value = true;
    error.value = '';
    success.value = '';

    try {
        const response = await axios.post('/api/v1/auth/password/setup', {
            user_id: userData.value.id,
            password: form.password,
            password_confirmation: form.password_confirmation,
        });

        if (response.data.status === 'success') {
            success.value = response.data.message;

            // Store the new token and user data
            const newToken = response.data.token;

            // Update auth store directly
            authStore.authToken = newToken;
            authStore.authUser = userData.value;
            localStorage.setItem('token', newToken);

            // Clear password setup data from localStorage
            localStorage.removeItem('password_setup_user');
            localStorage.removeItem('password_setup_token');

            // Redirect to dashboard after a short delay
            setTimeout(() => {
                router.push('/dashboard');
            }, 1000);
        }
    } catch (err) {
        console.error('Password setup error:', err);

        if (err.response?.data?.errors) {
            const apiErrors = err.response.data.errors;
            if (apiErrors.password) {
                errors.password = apiErrors.password[0];
            }
            if (apiErrors.password_confirmation) {
                errors.password_confirmation = apiErrors.password_confirmation[0];
            }
        } else {
            error.value = err.response?.data?.message || 'An error occurred while setting up your password';
        }
    } finally {
        isLoading.value = false;
    }
};


const extractUserInfo = (data) => {
    if (data.user_info) {
        return {
            id: data.user_info.id,
            name: data.user_info.name,
            email: data.user_info.email,
        };
    }
    return {
        id: data.id,
        name: data.name,
        email: data.email,
    };
};

onMounted(() => {
    // Get user data and token from route query or localStorage
    const queryUser = route.query.user;
    const queryToken = route.query.token;

    if (queryUser && queryToken) {
        try {
            const parsedData = JSON.parse(decodeURIComponent(queryUser));
            userData.value = extractUserInfo(parsedData);
            token.value = queryToken;

            // Store in localStorage for page refresh
            localStorage.setItem('password_setup_user', queryUser);
            localStorage.setItem('password_setup_token', queryToken);
        } catch (e) {
            console.error('Failed to parse user data:', e);
            router.push('/login');
        }
    } else {
        // Try to get from localStorage
        const storedUser = localStorage.getItem('password_setup_user');
        const storedToken = localStorage.getItem('password_setup_token');

        if (storedUser && storedToken) {
            try {
                const parsedData = JSON.parse(decodeURIComponent(storedUser));
                userData.value = extractUserInfo(parsedData);
                token.value = storedToken;
            } catch (e) {
                console.error('Failed to parse stored user data:', e);
                router.push('/login');
            }
        } else {
            // No data available, redirect to login
            router.push('/login');
        }
    }
});
</script>
