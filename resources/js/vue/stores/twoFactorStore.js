import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import { useToastStore } from './toastStore';

export const useTwoFactorStore = defineStore('twoFactor', () => {
    const toastStore = useToastStore();

    // State
    const isEnabled = ref(false);
    const secret = ref(null);
    const qrCode = ref(null);
    const recoveryCodes = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const twoFactorUserId = ref(null);

    // Computed
    const hasRecoveryCodes = computed(() => recoveryCodes.value.length > 0);

    // Actions
    const getStatus = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/two-factor/status');

            if (response.data.success) {
                isEnabled.value = response.data.data.two_factor_enabled;
                return { success: true };
            } else {
                throw new Error(response.data.message || 'Failed to get 2FA status');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Failed to get 2FA status';
            error.value = errorMessage;
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const enableSetup = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/two-factor/enable');

            if (response.data.success) {
                secret.value = response.data.data.secret;
                qrCode.value = response.data.data.qr_code;
                return { success: true, data: response.data.data };
            } else {
                throw new Error(response.data.message || 'Failed to generate 2FA setup');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Failed to generate 2FA setup';
            error.value = errorMessage;
            toastStore.error('2FA Setup Error', errorMessage);
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const confirmSetup = async (code) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/two-factor/confirm', {
                secret: secret.value,
                code: code,
            });

            if (response.data.success) {
                isEnabled.value = true;
                recoveryCodes.value = response.data.data.recovery_codes;
                toastStore.success('2FA Enabled', 'Two-factor authentication has been enabled successfully');
                return { success: true, data: response.data.data };
            } else {
                throw new Error(response.data.message || 'Failed to confirm 2FA setup');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Failed to confirm 2FA setup';
            error.value = errorMessage;
            toastStore.error('2FA Confirmation Error', errorMessage);
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const disable = async (password) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/two-factor/disable', {
                password: password,
            });

            if (response.data.success) {
                isEnabled.value = false;
                secret.value = null;
                qrCode.value = null;
                recoveryCodes.value = [];
                toastStore.success('2FA Disabled', 'Two-factor authentication has been disabled');
                return { success: true };
            } else {
                throw new Error(response.data.message || 'Failed to disable 2FA');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Failed to disable 2FA';
            error.value = errorMessage;
            toastStore.error('2FA Disable Error', errorMessage);
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };



    const generateRecoveryCodes = async (password) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/two-factor/recovery-codes', {
                password: password,
            });

            if (response.data.success) {
                recoveryCodes.value = response.data.data.recovery_codes;
                toastStore.success('Recovery Codes Generated', 'New recovery codes have been generated');
                return { success: true, data: response.data.data };
            } else {
                throw new Error(response.data.message || 'Failed to generate recovery codes');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Failed to generate recovery codes';
            error.value = errorMessage;
            toastStore.error('Recovery Codes Error', errorMessage);
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const clearError = () => {
        error.value = null;
    };

    const reset = () => {
        secret.value = null;
        qrCode.value = null;
        recoveryCodes.value = [];
        error.value = null;
    };

    return {
        // State
        isEnabled,
        secret,
        qrCode,
        recoveryCodes,
        isLoading,
        error,
        twoFactorUserId,

        // Computed
        hasRecoveryCodes,

        // Actions
        getStatus,
        enableSetup,
        confirmSetup,
        disable,
        generateRecoveryCodes,
        clearError,
        reset,
    };
});

