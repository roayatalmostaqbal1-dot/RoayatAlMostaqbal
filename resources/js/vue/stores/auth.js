import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import router from '../router/router';

export const useAuthStore = defineStore('auth', () => {
    // State
    const user = ref(null);
    const token = ref(null);
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
            user.value = data.user_info;

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
            return { success: true, data: user.value };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch user';
            token.value = null;
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const socialAuthRedirect = (provider) => {
        const API_BASE_URL = '/api/v1';
        const callbackUrl = `${window.location.origin}/admin/social-callback`;
        const redirectUrl = `${API_BASE_URL}/social-auth/${provider}?callback=${encodeURIComponent(callbackUrl)}`;
        const width = 550;
        const height = 650;
        const left = window.screenX + (window.outerWidth - width) / 2;
        const top = window.screenY + (window.outerHeight - height) / 2;
        const popup = window.open(
            redirectUrl,
            `${provider}-login`,
            `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
        );

        if (!popup) {
            error.value = '❌ تم حظر النوافذ المنبثقة. الرجاء السماح بالنوافذ لهذا الموقع.';
            return;
        }
        const handleMessage = (event) => {
            if (event.origin !== window.location.origin) return;

            const { type, token: newToken, user: userData, message, user_id, user: socialUser } = event.data || {};

            if (type === 'SOCIAL_AUTH_SUCCESS') {
                token.value = newToken;
                user.value = userData;
                popup.close();
                window.removeEventListener('message', handleMessage);
                window.dispatchEvent(
                    new CustomEvent('social-auth-success', {
                        detail: { token: newToken, user: userData },
                    })
                );
            } else if (type === 'SOCIAL_AUTH_2FA_REQUIRED') {
                // Handle 2FA required for social login
                // Store user data before showing 2FA modal
                if (socialUser) {
                    user.value = socialUser;
                }
                twoFactorRequired.value = true;
                twoFactorUserId.value = user_id;
                popup.close();
                window.removeEventListener('message', handleMessage);
                window.dispatchEvent(
                    new CustomEvent('social-auth-2fa-required', {
                        detail: { user_id, user: socialUser },
                    })
                );
            } else if (type === 'SOCIAL_AUTH_ERROR') {
                error.value = message || 'فشل تسجيل الدخول عبر المنصة الاجتماعية.';
                popup.close();
                window.removeEventListener('message', handleMessage);
            }
        };
        window.addEventListener('message', handleMessage);
        const popupChecker = setInterval(() => {
            if (popup.closed) {
                clearInterval(popupChecker);
                window.removeEventListener('message', handleMessage);
            }
        }, 500);
    };

    const clearError = () => {
        error.value = null;
    };

    const resetTwoFactor = () => {
        twoFactorRequired.value = false;
        twoFactorUserId.value = null;
    };
    const verify = async (code) => {
        isLoading.value = true;
        error.value = null;
        try {
            // Get user_id from the stored user data (set during login)
            const userId = user.value?.id || user.value?.user_id;

            if (!userId) {
                throw new Error('User ID not found. Please login again.');
            }

            const response = await apiClient.post('/auth/two-factor/verify', {
                code: code,
                user_id: userId,
            });

            if (response.data.success) {
                const { token: newToken, user: userData } = response.data;

                // Update store with new token and user data
                token.value = newToken;
                user.value = userData;

                return { success: true, user: userData };
            } else {
                throw new Error(response.data.message || 'Invalid verification code');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Invalid verification code';
            error.value = errorMessage;
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
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
        clearError,
        resetTwoFactor,
        verify
    };
}, {
    persist: true,
});

