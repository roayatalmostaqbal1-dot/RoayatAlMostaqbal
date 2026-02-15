import { defineStore } from 'pinia';
import apiClient from '@/vue/services/api';
import { initSodium, encryptUserData, decryptUserData } from '@/vue/utils/encryption';

export const useSecurityDashboardStore = defineStore('securityDashboard', {
    state: () => ({
        identityData: null,
        securityLogs: [],
        logsTotal: 0,
        currentPage: 1,
        lastPage: 1,
        aiInsight: null,
        systemMetrics: null,
        isLoading: false,
        error: null,
        // Encryption-related state
        encryptedData: null,
        encryptedDataList: [],
        encryptionMetadata: null,
        isEncryptionReady: false,
    }),

    getters: {
        hasLogs: (state) => state.securityLogs.length > 0,
        hasInsight: (state) => state.aiInsight !== null,
    },

    actions: {
        /**
         * Fetch all dashboard data
         */
        async fetchAllData() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get('/security-dashboard');

                if (response.data.success) {
                    const { identity, security_logs, ai_insight, system_metrics } = response.data.data;

                    this.identityData = identity;
                    this.securityLogs = security_logs.logs || [];
                    this.logsTotal = security_logs.total || 0;
                    this.currentPage = security_logs.current_page || 1;
                    this.lastPage = security_logs.last_page || 1;
                    this.aiInsight = ai_insight;
                    this.systemMetrics = system_metrics;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch dashboard data';
                console.error('Error fetching dashboard data:', error);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Fetch identity data
         */
        async fetchIdentityData() {
            try {
                const response = await apiClient.get('/security-dashboard/identity');
                if (response.data.success) {
                    this.identityData = response.data.data;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch identity data';
                console.error('Error fetching identity data:', error);
            }
        },

        /**
         * Fetch security logs with filters
         */
        async fetchSecurityLogs(filters = {}) {
            this.isLoading = true;
            this.error = null;

            try {
                const params = {
                    period: filters.period || 'today',
                    severity: filters.severity,
                    event_type: filters.event_type,
                    per_page: filters.per_page || 20,
                };

                const response = await apiClient.get('/security-dashboard/security-logs', { params });

                if (response.data.success) {
                    const { logs, total, current_page, last_page } = response.data.data;
                    this.securityLogs = logs || [];
                    this.logsTotal = total || 0;
                    this.currentPage = current_page || 1;
                    this.lastPage = last_page || 1;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch security logs';
                console.error('Error fetching security logs:', error);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Fetch AI analysis
         */
        async fetchAIAnalysis() {
            try {
                const response = await apiClient.get('/security-dashboard/ai-analysis');
                if (response.data.success) {
                    this.aiInsight = response.data.data;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch AI analysis';
                console.error('Error fetching AI analysis:', error);
            }
        },

        /**
         * Fetch system metrics
         */
        async fetchSystemMetrics() {
            try {
                const response = await apiClient.get('/security-dashboard/system-metrics');
                if (response.data.success) {
                    this.systemMetrics = response.data.data;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch system metrics';
                console.error('Error fetching system metrics:', error);
            }
        },

        /**
         * Generate security logs
         */
        async generateSecurityLogs(count = 10) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post('/security-dashboard/generate-logs', { count });

                if (response.data.success) {
                    // Refresh logs after generation
                    await this.fetchSecurityLogs();
                    return { success: true, message: response.data.message };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to generate logs';
                console.error('Error generating logs:', error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Generate AI insight
         */
        async generateAIInsight() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post('/security-dashboard/generate-insight');

                if (response.data.success) {
                    this.aiInsight = response.data.data;
                    return { success: true, message: response.data.message };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to generate AI insight';
                console.error('Error generating AI insight:', error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Export logs as CSV
         */
        async exportLogs(period = 'today') {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post('/security-dashboard/export-logs', { period });

                if (response.data.success) {
                    const { csv, filename } = response.data.data;

                    // Create blob and download
                    const blob = new Blob([csv], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);

                    return { success: true, message: 'Logs exported successfully' };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to export logs';
                console.error('Error exporting logs:', error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Clear error
         */
        clearError() {
            this.error = null;
        },

        /**
         * Initialize encryption library
         */
        async initializeEncryption() {
            try {
                await initSodium();
                this.isEncryptionReady = true;
                return { success: true };
            } catch (error) {
                this.error = 'Failed to initialize encryption library';
                console.error('Encryption initialization error:', error);
                return { success: false, error: this.error };
            }
        },

        /**
         * Fetch encrypted data (raw format - as stored in database)
         */
        async fetchEncryptedDataRaw(dataType = 'profile') {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get('/security-dashboard/encrypted-raw', {
                    params: { type: dataType },
                });

                if (response.data.success) {
                    this.encryptedData = response.data.data;
                    return { success: true, data: this.encryptedData };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch encrypted data';
                console.error('Error fetching encrypted data:', error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Fetch all encrypted data for the user
         */
        async fetchAllEncryptedData() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get('/security-dashboard/encrypted-all');

                if (response.data.success) {
                    this.encryptedDataList = response.data.data || [];
                    return { success: true, data: this.encryptedDataList };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch encrypted data list';
                console.error('Error fetching encrypted data list:', error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Fetch encryption metadata
         */
        async fetchEncryptionMetadata(dataType = 'profile') {
            try {
                const response = await apiClient.get('/security-dashboard/encryption-metadata', {
                    params: { type: dataType },
                });

                if (response.data.success) {
                    this.encryptionMetadata = response.data.data;
                    return { success: true, data: this.encryptionMetadata };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch encryption metadata';
                console.error('Error fetching encryption metadata:', error);
                return { success: false, error: this.error };
            }
        },

        /**
         * Decrypt user data with password
         */
        async decryptData(password, dekSalt, encryptedDek, dekNonce, profileCiphertext, profileNonce) {
            try {
                if (!this.isEncryptionReady) {
                    await this.initializeEncryption();
                }

                const decryptedData = await decryptUserData(
                    password,
                    dekSalt,
                    encryptedDek,
                    dekNonce,
                    profileCiphertext,
                    profileNonce
                );

                return { success: true, data: decryptedData };
            } catch (error) {
                this.error = 'Failed to decrypt data. Wrong password or corrupted data.';
                console.error('Decryption error:', error);
                return { success: false, error: this.error };
            }
        },

        /**
         * Encrypt user data with password
         */
        async encryptData(password, userData) {
            try {
                if (!this.isEncryptionReady) {
                    await this.initializeEncryption();
                }

                const encryptedData = await encryptUserData(password, userData);
                return { success: true, data: encryptedData };
            } catch (error) {
                this.error = 'Failed to encrypt data';
                console.error('Encryption error:', error);
                return { success: false, error: this.error };
            }
        },

        /**
         * Setup encrypted identity for the first time
         */
        async setupEncryptedIdentity(password) {
            this.isLoading = true;
            this.error = null;

            try {
                // 1. Prepare data to encrypt (using existing identity data)
                const identityDataToEncrypt = {
                    identity_number: this.identityData?.identity_number || '784-1234-1234567-1',
                    nationality: this.identityData?.nationality || 'UAE',
                    full_name: this.identityData?.user_name || '',
                    email: this.identityData?.user_email || '',
                    phone: '+971501234567',
                    address: 'Dubai, UAE',
                    dob: '1990-01-01',
                    passport_number: 'N12345678',
                    expiry_date: '2030-12-31'
                };

                // 2. Encrypt the data
                if (!this.isEncryptionReady) {
                    await this.initializeEncryption();
                }

                const encryptedResult = await encryptUserData(password, identityDataToEncrypt);

                // 3. Send to server
                const payload = {
                    ...encryptedResult,
                    data_type: 'profile',
                    metadata: JSON.stringify({
                        version: '1.0',
                        setup_date: new Date().toISOString(),
                        client: 'web-dashboard'
                    })
                };

                const response = await apiClient.post('/encrypted-data', payload);

                if (response.data.success) {
                    // Update field references to match what's returned/expected
                    const newEncryptedData = response.data.data;

                    // Merge into identity data so UI updates immediately
                    this.identityData = {
                        ...this.identityData,
                        encrypted_identity: {
                            encrypted_dek: newEncryptedData.encrypted_dek,
                            dek_salt: newEncryptedData.dek_salt,
                            dek_nonce: newEncryptedData.dek_nonce,
                            ciphertext: newEncryptedData.profile_ciphertext,
                            nonce: newEncryptedData.profile_nonce,
                        }
                    };

                    return { success: true, message: 'Identity encryption setup complete' };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to setup encrypted identity';
                console.error('Setup encryption error:', error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },
    },
});
