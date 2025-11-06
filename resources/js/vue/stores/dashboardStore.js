import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import { useToastStore } from './toastStore';

export const useDashboardStore = defineStore('dashboard', () => {
    const toastStore = useToastStore();

    // State
    const statistics = ref([]);
    const recentActivity = ref([]);
    const registrationTrends = ref([]);
    const rolesDistribution = ref([]);
    const isLoading = ref(false);
    const error = ref(null);

    // Computed
    const totalUsers = computed(() => {
        const stat = statistics.value.find(s => s.title === 'Total Users');
        return stat ? stat.value : 0;
    });

    const totalRoles = computed(() => {
        const stat = statistics.value.find(s => s.title === 'Total Roles');
        return stat ? stat.value : 0;
    });

    const totalPermissions = computed(() => {
        const stat = statistics.value.find(s => s.title === 'Total Permissions');
        return stat ? stat.value : 0;
    });


    // Actions
    const fetchStatistics = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/dashboard/statistics');

            if (response.data.success) {
                statistics.value = response.data.data.statistics;
                recentActivity.value = response.data.data.recent_activity;
                registrationTrends.value = response.data.data.registration_trends;
                rolesDistribution.value = response.data.data.roles_distribution;

                return { success: true };
            } else {
                throw new Error(response.data.message || 'Failed to fetch statistics');
            }
        } catch (err) {
            const errorMessage = err.response?.data?.message || err.message || 'Failed to fetch dashboard statistics';
            error.value = errorMessage;
            toastStore.error('Dashboard Error', errorMessage);
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const clearError = () => {
        error.value = null;
    };

    return {
        // State
        statistics,
        recentActivity,
        registrationTrends,
        rolesDistribution,
        isLoading,
        error,

        // Computed
        totalUsers,
        totalRoles,
        totalPermissions,

        // Actions
        fetchStatistics,
        clearError,
    };
});

