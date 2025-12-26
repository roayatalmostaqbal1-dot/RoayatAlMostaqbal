import { defineStore } from "pinia";
import apiClient from "../../services/api";
import { useToastStore } from "../toastStore";
import {
    encryptUserData,
    decryptUserData,
    initSodium,
    clearSensitiveData
} from "../../utils/encryption";

export const useEncryptionStore = defineStore("encryption", {
    // =====================
    // State
    // =====================
    state: () => ({
        isLoading: false,
        error: null,
        decryptedData: null,
        isDecrypted: false,
        encryptedDataId: null,
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        hasDecryptedData: (state) => !!state.decryptedData,
        isEncrypted: (state) => state.isDecrypted,
        encryptionError: (state) => state.error,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Initialize Encryption
        // ------------------------------------
        async initializeEncryption() {
            try {
                await initSodium();
                return { success: true };
            } catch (err) {
                this.error = "Failed to initialize encryption system";
                return { success: false, error: this.error };
            }
        },

        // ------------------------------------
        // Encrypt and Store Data
        // ------------------------------------
        async encryptAndStoreData(password, sensitiveData) {
            this.isLoading = true;
            this.error = null;

            try {
                // Encrypt data on client side
                const encryptedData = await encryptUserData(password, sensitiveData);

                // Send encrypted data to server
                const response = await apiClient.post("/encrypted-data", encryptedData);

                if (response.data.success) {
                    this.encryptedDataId = response.data.data.id;
                    const toast = useToastStore();
                    toast.success("Data Encrypted", "Your sensitive data has been securely encrypted and stored.");
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(response.data.message || "Failed to store encrypted data");
                }
            } catch (err) {
                this.error = err.response?.data?.message || err.message || "Encryption failed";
                const toast = useToastStore();
                toast.error("Encryption Error", this.error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Decrypt Data
        // ------------------------------------
        async decryptData(password) {
            this.isLoading = true;
            this.error = null;

            try {
                // Fetch encrypted data from server
                const response = await apiClient.get("/encrypted-data");

                if (!response.data.success || !response.data.data) {
                    throw new Error("No encrypted data found");
                }

                const encData = response.data.data;

                // Decrypt on client side
                const decrypted = await decryptUserData(
                    password,
                    encData.dek_salt,
                    encData.encrypted_dek,
                    encData.dek_nonce,
                    encData.profile_ciphertext,
                    encData.profile_nonce
                );

                this.decryptedData = decrypted;
                this.isDecrypted = true;
                this.encryptedDataId = encData.id;

                const toast = useToastStore();
                toast.success("Data Decrypted", "Your sensitive data has been decrypted successfully.");
                return { success: true, data: decrypted };
            } catch (err) {
                this.error = err.message || "Decryption failed";

                const toast = useToastStore();
                // Provide specific error message for wrong password
                if (this.error.includes("Wrong password")) {
                    toast.error("Wrong Password", "The password you entered is incorrect. Please try again.");
                } else {
                    toast.error("Decryption Error", this.error);
                }

                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Update Encrypted Data
        // ------------------------------------
        async updateEncryptedData(password, sensitiveData) {
            this.isLoading = true;
            this.error = null;

            try {
                // Encrypt updated data on client side
                const encryptedData = await encryptUserData(password, sensitiveData);

                // Send to server
                const response = await apiClient.put(`/encrypted-data/${this.encryptedDataId}`, encryptedData);

                if (response.data.success) {
                    this.decryptedData = sensitiveData;
                    const toast = useToastStore();
                    toast.success("Data Updated", "Your encrypted data has been updated successfully.");
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(response.data.message || "Failed to update encrypted data");
                }
            } catch (err) {
                this.error = err.response?.data?.message || err.message || "Update failed";
                const toast = useToastStore();
                toast.error("Update Error", this.error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Clear Decrypted Data
        // ------------------------------------
        clearDecryptedData() {
            if (this.decryptedData) {
                // Clear sensitive data
                Object.keys(this.decryptedData).forEach(key => {
                    this.decryptedData[key] = null;
                });
            }
            this.decryptedData = null;
            this.isDecrypted = false;
        },

        // ------------------------------------
        // Clear Error
        // ------------------------------------
        clearError() {
            this.error = null;
        },
    },
});

