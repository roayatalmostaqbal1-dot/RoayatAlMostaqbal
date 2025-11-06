import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';

export const useAuthStore = defineStore('auth', () => {
    // State
    const user = ref(null);
    const token = ref(localStorage.getItem('auth_token') || null);
    const isLoading = ref(false);
    const error = ref(null);
    const twoFactorRequired = ref(false);
    const twoFactorUserId = ref(null);

    // Computed
    const isAuthenticated = computed(() => !!token.value);
    const userName = computed(() => user.value?.name || '');
    const userEmail = computed(() => user.value?.email || '');

    // Actions
    const login = async (email, password) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/auth/login', { email, password });
            const { token: newToken, data } = response.data;

            token.value = newToken;
            user.value = data;

            localStorage.setItem('auth_token', newToken);
            localStorage.setItem('user', JSON.stringify(data));

            return { success: true, data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Login failed';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const register = async (userData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/auth/register', userData);
            const { token: newToken, data } = response.data;

            token.value = newToken;
            user.value = data;

            localStorage.setItem('auth_token', newToken);
            localStorage.setItem('user', JSON.stringify(data));

            return { success: true, data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Registration failed';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const logout = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            await apiClient.post('/logout');
            return { success: true };
        } catch (err) {
            error.value = err.response?.data?.message || 'Logout failed';
            return { success: false, error: error.value };
        } finally {
            token.value = null;
            user.value = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            isLoading.value = false;
        }
    };

    const fetchUser = async () => {
        if (!token.value) {
            return { success: false, error: 'No token available' };
        }

        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/user');
            user.value = response.data.data;
            localStorage.setItem('user', JSON.stringify(user.value));
            return { success: true, data: user.value };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch user';
            token.value = null;
            localStorage.removeItem('auth_token');
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const socialAuthRedirect = (provider) => {
        const API_BASE_URL = '/api/v1';
        const callbackUrl = `${window.location.origin}/admin/social-callback`;
        const redirectUrl = `${API_BASE_URL}/social-auth/${provider}?callback=${encodeURIComponent(callbackUrl)}`;

        // Open in popup window
        const width = 500;
        const height = 600;
        const left = window.screenX + (window.outerWidth - width) / 2;
        const top = window.screenY + (window.outerHeight - height) / 2;

        const popup = window.open(
            redirectUrl,
            `${provider}-login`,
            `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
        );

        if (!popup) {
            error.value = 'Popup blocked. Please allow popups for this site.';
            return;
        }

        // Listen for message from popup
        const handleMessage = (event) => {
            if (event.origin !== window.location.origin) return;

            if (event.data.type === 'SOCIAL_AUTH_SUCCESS') {
                const { token: newToken, data } = event.data;

                token.value = newToken;
                user.value = data;

                localStorage.setItem('auth_token', newToken);
                localStorage.setItem('user', JSON.stringify(data));

                popup.close();
                window.removeEventListener('message', handleMessage);

                // Emit success event or redirect
                window.dispatchEvent(new CustomEvent('social-auth-success', { detail: { token: newToken, user: data } }));
            } else if (event.data.type === 'SOCIAL_AUTH_ERROR') {
                error.value = event.data.message || 'Social authentication failed';
                popup.close();
                window.removeEventListener('message', handleMessage);
            }
        };

        window.addEventListener('message', handleMessage);
    };

    const restoreSession = () => {
        const savedToken = localStorage.getItem('auth_token');
        const savedUser = localStorage.getItem('user');

        if (savedToken) {
            token.value = savedToken;
        }
        if (savedUser) {
            try {
                user.value = JSON.parse(savedUser);
            } catch (err) {
                console.error('Error parsing saved user:', err);
            }
        }
    };

    const clearError = () => {
        error.value = null;
    };

    const resetTwoFactor = () => {
        twoFactorRequired.value = false;
        twoFactorUserId.value = null;
    };

    return {
        // State
        user,
        token,
        isLoading,
        error,
        twoFactorRequired,
        twoFactorUserId,

        // Computed
        isAuthenticated,
        userName,
        userEmail,

        // Actions
        login,
        register,
        logout,
        fetchUser,
        socialAuthRedirect,
        restoreSession,
        clearError,
        resetTwoFactor,
    };
}, {
    persist: true,
});

