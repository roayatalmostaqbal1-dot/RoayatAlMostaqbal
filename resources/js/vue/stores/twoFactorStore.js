import { defineStore } from "pinia";
import apiClient from "../services/api";
import { useToastStore } from "./toastStore";

export const useTwoFactorStore = defineStore("twoFactor", {
    // =====================
    // State
    // =====================
    state: () => ({
        isEnabled: false,
        secret: null,
        qrCode: null,
        recoveryCodes: [],
        isLoading: false,
        error: null,
        twoFactorUserId: null,
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        hasRecoveryCodes: (state) => state.recoveryCodes.length > 0,
        twoFactorError: (state) => state.error,
        isTwoFactorEnabled: (state) => state.isEnabled,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Get 2FA Status
        // ------------------------------------
        async getStatus() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/two-factor/status");

                if (response.data.success) {
                    this.isEnabled = response.data.data.two_factor_enabled;
                    return { success: true };
                } else {
                    throw new Error(response.data.message || "Failed to get 2FA status");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to get 2FA status";
                this.error = errorMessage;
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Enable 2FA Setup
        // ------------------------------------
        async enableSetup() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/two-factor/enable");

                if (response.data.success) {
                    this.secret = response.data.data.secret;
                    this.qrCode = response.data.data.qr_code;
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(response.data.message || "Failed to generate 2FA setup");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to generate 2FA setup";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("2FA Setup Error", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Confirm 2FA Setup
        // ------------------------------------
        async confirmSetup(code) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/two-factor/confirm", {
                    secret: this.secret,
                    code: code,
                });

                if (response.data.success) {
                    this.isEnabled = true;
                    this.recoveryCodes = response.data.data.recovery_codes;
                    const toast = useToastStore();
                    toast.success("2FA Enabled", "Two-factor authentication has been enabled successfully");
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(response.data.message || "Failed to confirm 2FA setup");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to confirm 2FA setup";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("2FA Confirmation Error", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Disable 2FA
        // ------------------------------------
        async disable(password) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/two-factor/disable", {
                    password: password,
                });

                if (response.data.success) {
                    this.isEnabled = false;
                    this.secret = null;
                    this.qrCode = null;
                    this.recoveryCodes = [];
                    const toast = useToastStore();
                    toast.success("2FA Disabled", "Two-factor authentication has been disabled");
                    return { success: true };
                } else {
                    throw new Error(response.data.message || "Failed to disable 2FA");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to disable 2FA";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("2FA Disable Error", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Generate Recovery Codes
        // ------------------------------------
        async generateRecoveryCodes(password) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/two-factor/recovery-codes", {
                    password: password,
                });

                if (response.data.success) {
                    this.recoveryCodes = response.data.data.recovery_codes;
                    const toast = useToastStore();
                    toast.success("Recovery Codes Generated", "New recovery codes have been generated");
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(response.data.message || "Failed to generate recovery codes");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to generate recovery codes";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Recovery Codes Error", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Clear Error
        // ------------------------------------
        clearError() {
            this.error = null;
        },

        // ------------------------------------
        // Reset 2FA State
        // ------------------------------------
        reset() {
            this.secret = null;
            this.qrCode = null;
            this.recoveryCodes = [];
            this.error = null;
        },
    },
});

