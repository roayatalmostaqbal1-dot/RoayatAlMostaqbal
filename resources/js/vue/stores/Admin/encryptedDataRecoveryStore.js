import { defineStore } from "pinia";
import apiClient from "@/vue/services/api";
import { useToastStore } from "@/vue/stores/toastStore";

export const useEncryptedDataRecoveryStore = defineStore("encryptedDataRecovery", {
    // =====================
    // State
    // =====================
    state: () => ({
        isLoading: false,
        error: null,
        masterKeyInfo: null,
        recoveryHistory: [],
        selectedUser: null,
        recoveredData: null,
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        isMasterKeyActive: (state) => state.masterKeyInfo !== null,
        hasRecoveredData: (state) => state.recoveredData !== null,
        isUserSelected: (state) => state.selectedUser !== null,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Fetch Master Key Information
        // ------------------------------------
        async fetchMasterKeyInfo() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/SuperAdmin/master-key/public-key");

                if (response.data.success) {
                    this.masterKeyInfo = response.data;
                    return { success: true, data: response.data };
                } else {
                    throw new Error(response.data.message || "Failed to fetch master key info");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to fetch master key information";
                this.error = errorMessage;

                const toast = useToastStore();
                toast.error("Error", errorMessage);

                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Recover Encrypted Data for User
        // ------------------------------------
        async recoverUserData(userId, userPassword, dataType = "profile") {
            this.isLoading = true;
            this.error = null;
            this.recoveredData = null;

            try {
                const response = await apiClient.post(
                    `/SuperAdmin/recover-encrypted-data/${userId}?type=${dataType}`,
                    {
                        user_password: userPassword,
                    }
                );

                if (response.data.success) {
                    this.recoveredData = response.data.data;

                    const toast = useToastStore();
                    toast.success("Success", "Data recovered successfully");

                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(response.data.message || "Failed to recover data");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to recover encrypted data";
                this.error = errorMessage;

                const toast = useToastStore();
                toast.error("Recovery Failed", errorMessage);

                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Set Selected User
        // ------------------------------------
        setSelectedUser(user) {
            this.selectedUser = user;
        },

        // ------------------------------------
        // Clear Selected User
        // ------------------------------------
        clearSelectedUser() {
            this.selectedUser = null;
        },

        // ------------------------------------
        // Clear Recovered Data
        // ------------------------------------
        clearRecoveredData() {
            this.recoveredData = null;
            this.error = null;
        },

        // ------------------------------------
        // Clear All Data
        // ------------------------------------
        clearAllData() {
            this.recoveredData = null;
            this.selectedUser = null;
            this.error = null;
        },

        // ------------------------------------
        // Clear Error
        // ------------------------------------
        clearError() {
            this.error = null;
        },
    },
});

