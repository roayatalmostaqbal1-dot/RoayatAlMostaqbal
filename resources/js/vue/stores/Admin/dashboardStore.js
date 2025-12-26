import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../../services/api';
import { useToastStore } from '../toastStore';

export const useDashboardStore = defineStore('dashboard', {

    state: () => ({
        statistics: [],
        recentActivity: [],
        registrationTrends: [],
        rolesDistribution: [],
        usersStats: [],
        isLoading: false,
        error: null,
    }),

    getters: {
        totalUsers: (state) => {
            const stat = state.statistics.find(s => s.title === 'Total Users');
            return stat ? stat.value : 0;
        },
        totalRoles: (state) => {
            const stat = state.statistics.find(s => s.title === 'Total Roles');
            return stat ? stat.value : 0;
        },
        totalPermissions: (state) => {
            const stat = state.statistics.find(s => s.title === 'Total Permissions');
            return stat ? stat.value : 0;
        },
        userGrowth: (state) => {
            const stat = state.statistics.find(s => s.title === 'Total Users');
            return stat ? stat.trend : 0;
        },
    },

    // Actions
    actions: {

        async fetchStatistics() {
            const toast = useToastStore(); // ← مهم جداً
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get('/admin/dashboard/statistics');

                if (response.data.success) {
                    this.statistics = response.data.data.statistics;
                    this.recentActivity = response.data.data.recent_activity;
                    this.registrationTrends = response.data.data.registration_trends;
                    this.rolesDistribution = response.data.data.roles_distribution;
                    return { success: true };
                } else {
                    throw new Error(response.data.message || 'Failed to fetch statistics');
                }

            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || 'Failed to fetch dashboard statistics';
                this.error = errorMessage;

                toast.error('Dashboard Error', errorMessage); // Toast يعمل الآن

                return { success: false, error: errorMessage };

            } finally {
                this.isLoading = false;
            }
        },


        async fetchUsersStats() {
            const toast = useToastStore(); // ← مهم جداً
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get('/admin/dashboard/users-stats');

                if (response.data.success) {
                    this.usersStats = response.data.data;
                    return { success: true };
                } else {
                    throw new Error(response.data.message || 'Failed to fetch users stats');
                }

            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || 'Failed to fetch users stats';
                this.error = errorMessage;

                toast.error('Dashboard Error', errorMessage); // Toast يعمل الآن

                return { success: false, error: errorMessage };

            } finally {
                this.isLoading = false;
            }
        },
    },
});
