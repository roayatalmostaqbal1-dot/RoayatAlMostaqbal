<template>
    <div
        class="min-h-screen bg-gradient-to-br from-[#051824] via-[#162936] to-[#3b5265] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Loading -->
            <div v-if="isLoading" class="text-center">
                <svg class="animate-spin h-12 w-12 text-[#27e9b5] mx-auto mb-4">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                        fill="none"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="text-gray-400 mt-4">Completing authentication...</p>
            </div>

            <!-- Error -->
            <div v-else-if="error" class="text-center">
                <div class="mb-4 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg">
                    <p class="text-red-400">{{ error }}</p>
                </div>
                <Button variant="primary" size="lg" class="w-full" @click="closeWindow">Close</Button>
            </div>

            <!-- Success -->
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

const closeWindow = () => window.close();

const getQueryParam = (name) => {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
};

onMounted(async () => {
    try {
        console.log('=== Social Callback Started ===');
        console.log('Full URL:', window.location.href);
        console.log('Search params:', window.location.search);

        // Parse all query parameters
        const params = new URLSearchParams(window.location.search);
        const allParams = {};
        for (const [key, value] of params.entries()) {
            allParams[key] = value;
        }
        console.log('All query parameters:', allParams);

        const googleError = getQueryParam('error');
        console.log('Error param:', googleError);

        // USER CANCELLED LOGIN
        if (googleError === 'access_denied') {
            console.log('User cancelled login');
            if (window.opener) {
                window.opener.postMessage(
                    {
                        type: 'SOCIAL_AUTH_CANCELLED',
                        message: 'Login cancelled by user.',
                    },
                    window.location.origin
                );
            }

            isLoading.value = false;
            setTimeout(() => window.close(), 300);
            return;
        }

        const token = getQueryParam('token');
        const userData = getQueryParam('user');
        const errorMessage = getQueryParam('error');
        const twoFactorRequired = getQueryParam('two_factor_required');
        const userId = getQueryParam('user_id');
        const needsPasswordSetup = getQueryParam('needs_password_setup');

        console.log('Parsed parameters:', {
            token: token ? 'EXISTS' : 'NULL',
            userData: userData ? 'EXISTS' : 'NULL',
            errorMessage,
            twoFactorRequired,
            userId,
            needsPasswordSetup
        });

        if (errorMessage) {
            console.log('Error message found:', errorMessage);
            error.value = decodeURIComponent(errorMessage);
            isLoading.value = false;

            // Send error message to parent window
            if (window.opener) {
                window.opener.postMessage(
                    {
                        type: 'SOCIAL_AUTH_ERROR',
                        error: error.value,
                    },
                    window.location.origin
                );
            }

            setTimeout(() => window.close(), 300);
            return;
        }

        // 2FA Required
        if (twoFactorRequired === 'true' || twoFactorRequired === '1') {
            console.log('2FA required, userId:', userId);
            let user = null;
            if (userData) {
                try {
                    user = JSON.parse(decodeURIComponent(userData));
                    console.log('Parsed user data for 2FA:', user);
                } catch (e) {
                    console.error('Failed to parse user data for 2FA:', e);
                }
            }

            window.opener?.postMessage(
                {
                    type: "SOCIAL_AUTH_2FA_REQUIRED",
                    user_id: userId, // UUID7 string - don't parse as integer
                    user
                },
                window.location.origin
            );

            isLoading.value = false;
            setTimeout(() => window.close(), 500);
            return;
        }

        // Password Setup Required
        if (needsPasswordSetup === 'true' || needsPasswordSetup === '1') {
            console.log('Password setup required');

            if (!token || !userData) {
                console.error('Missing token or userData for password setup');
                error.value = "Invalid authentication response - missing data";
                isLoading.value = false;
                return;
            }

            try {
                const user = JSON.parse(decodeURIComponent(userData));
                console.log('Parsed user data for password setup:', user);

                window.opener?.postMessage(
                    {
                        type: "SOCIAL_AUTH_PASSWORD_SETUP_REQUIRED",
                        user,
                        token
                    },
                    window.location.origin
                );

                console.log('Sent SOCIAL_AUTH_PASSWORD_SETUP_REQUIRED message');
                isLoading.value = false;
                setTimeout(() => window.close(), 500);
                return;
            } catch (e) {
                console.error('Failed to parse user data for password setup:', e);
                error.value = "Failed to process user data";
                isLoading.value = false;
                return;
            }
        }

        // Normal Login Flow
        console.log('Normal login flow');

        if (!token || !userData) {
            console.error('Missing token or userData for normal login');
            console.log('Token:', token);
            console.log('UserData:', userData);
            error.value = "Invalid authentication response - missing credentials";
            isLoading.value = false;
            return;
        }

        try {
            const user = JSON.parse(decodeURIComponent(userData));
            console.log('Parsed user data for normal login:', user);

            window.opener?.postMessage(
                {
                    type: "SOCIAL_AUTH_SUCCESS",
                    token,
                    user,
                },
                window.location.origin
            );

            console.log('Sent SOCIAL_AUTH_SUCCESS message');
            isLoading.value = false;
            setTimeout(() => window.close(), 500);
        } catch (e) {
            console.error('Failed to parse user data for normal login:', e);
            error.value = "Failed to process authentication data";
            isLoading.value = false;
        }

    } catch (err) {
        console.error('=== Social Callback Error ===');
        console.error('Error:', err);
        console.error('Stack:', err.stack);
        error.value = "Failed to process authentication response";
        isLoading.value = false;
    }
});
</script>
