import { defineStore } from 'pinia';
import apiClient from '@/vue/services/api';

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
    },
});
