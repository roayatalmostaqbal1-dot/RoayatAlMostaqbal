import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import { useToastStore } from './toastStore';
import {
    encryptUserData,
    decryptUserData,
    initSodium,
    clearSensitiveData
} from '../utils/encryption';

export const useEncryptionStore = defineStore('encryption', () => {
    // State
    const isLoading = ref(false);
    const error = ref(null);
    const decryptedData = ref(null);
    const isDecrypted = ref(false);
    const encryptedDataId = ref(null);

    // Toast store
    const toastStore = useToastStore();

    // Computed
    const hasDecryptedData = computed(() => !!decryptedData.value);

    /**
     * Initialize encryption system
     */
    const initializeEncryption = async () => {
        try {
            await initSodium();
            return { success: true };
        } catch (err) {
            error.value = 'Failed to initialize encryption system';
            return { success: false, error: error.value };
        }
    };

    /**
     * Encrypt and store user data during registration
     * @param {string} password - User's password
     * @param {Object} sensitiveData - Data to encrypt
     * @returns {Promise<Object>} Result with encrypted data
     */
    const encryptAndStoreData = async (password, sensitiveData) => {
        isLoading.value = true;
        error.value = null;
        try {
            // Encrypt data on client side
            const encryptedData = await encryptUserData(password, sensitiveData);

            // Send encrypted data to server
            const response = await apiClient.post('/encrypted-data', encryptedData);

            if (response.data.success) {
                encryptedDataId.value = response.data.data.id;
                toastStore.success('Data Encrypted', 'Your sensitive data has been securely encrypted and stored.');
                return { success: true, data: response.data.data };
            } else {
                throw new Error(response.data.message || 'Failed to store encrypted data');
            }
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Encryption failed';
            toastStore.error('Encryption Error', error.value);
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    /**
     * Decrypt user data with password verification
     * @param {string} password - User's password for verification
     * @returns {Promise<Object>} Result with decrypted data
     */
    const decryptData = async (password) => {
        isLoading.value = true;
        error.value = null;
        try {
            // Fetch encrypted data from server
            const response = await apiClient.get('/encrypted-data');

            if (!response.data.success || !response.data.data) {
                throw new Error('No encrypted data found');
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

            decryptedData.value = decrypted;
            isDecrypted.value = true;
            encryptedDataId.value = encData.id;

            toastStore.success('Data Decrypted', 'Your sensitive data has been decrypted successfully.');
            return { success: true, data: decrypted };
        } catch (err) {
            error.value = err.message || 'Decryption failed';
            
            // Provide specific error message for wrong password
            if (error.value.includes('Wrong password')) {
                toastStore.error('Wrong Password', 'The password you entered is incorrect. Please try again.');
            } else {
                toastStore.error('Decryption Error', error.value);
            }
            
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    /**
     * Update encrypted data
     * @param {string} password - User's password
     * @param {Object} sensitiveData - Updated data to encrypt
     * @returns {Promise<Object>} Result
     */
    const updateEncryptedData = async (password, sensitiveData) => {
        isLoading.value = true;
        error.value = null;
        try {
            // Encrypt updated data on client side
            const encryptedData = await encryptUserData(password, sensitiveData);

            // Send to server
            const response = await apiClient.put(`/encrypted-data/${encryptedDataId.value}`, encryptedData);

            if (response.data.success) {
                decryptedData.value = sensitiveData;
                toastStore.success('Data Updated', 'Your encrypted data has been updated successfully.');
                return { success: true, data: response.data.data };
            } else {
                throw new Error(response.data.message || 'Failed to update encrypted data');
            }
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Update failed';
            toastStore.error('Update Error', error.value);
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    /**
     * Clear decrypted data from memory
     */
    const clearDecryptedData = () => {
        if (decryptedData.value) {
            // Clear sensitive data
            Object.keys(decryptedData.value).forEach(key => {
                decryptedData.value[key] = null;
            });
        }
        decryptedData.value = null;
        isDecrypted.value = false;
    };

    /**
     * Clear error message
     */
    const clearError = () => {
        error.value = null;
    };

    return {
        // State
        isLoading,
        error,
        decryptedData,
        isDecrypted,
        encryptedDataId,

        // Computed
        hasDecryptedData,

        // Actions
        initializeEncryption,
        encryptAndStoreData,
        decryptData,
        updateEncryptedData,
        clearDecryptedData,
        clearError
    };
});

