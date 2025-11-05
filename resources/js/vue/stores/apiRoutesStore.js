import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';

export const useApiRoutesStore = defineStore('apiRoutes', () => {
    // State
    const apiRoutes = ref([]);
    const isLoading = ref(false);
    const isSyncing = ref(false);
    const error = ref(null);
    const syncMessage = ref('');
    const pagination = ref({
        current_page: 1,
        per_page: 10,
        total: 0,
    });

    // Computed
    const apiRoutesList = computed(() => apiRoutes.value);
    const totalApiRoutes = computed(() => pagination.value.total);

    // Actions
    const fetchApiRoutes = async (page = 1, perPage = 10) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/api-routes', {
                params: { page, per_page: perPage },
            });
            apiRoutes.value = response.data.data;
            pagination.value = {
                current_page: response.data.meta?.current_page || page,
                per_page: response.data.meta?.per_page || perPage,
                total: response.data.meta?.total || 0,
            };
            return { success: true, data: response.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch API routes';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const createApiRoute = async (routeData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/api-routes', routeData);
            apiRoutes.value.push(response.data.data);
            return { success: true, data: response.data.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to create API route';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const updateApiRoute = async (routeId, routeData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.put(`/SuperAdmin/api-routes/${routeId}`, routeData);
            const index = apiRoutes.value.findIndex(r => r.id === routeId);
            if (index !== -1) {
                apiRoutes.value[index] = response.data.data;
            }
            return { success: true, data: response.data.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to update API route';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const deleteApiRoute = async (routeId) => {
        isLoading.value = true;
        error.value = null;
        try {
            await apiClient.delete(`/SuperAdmin/api-routes/${routeId}`);
            apiRoutes.value = apiRoutes.value.filter(r => r.id !== routeId);
            return { success: true };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to delete API route';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const syncRoutes = async () => {
        isSyncing.value = true;
        syncMessage.value = '';
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/sync-routes');
            syncMessage.value = response.data.message || 'Routes synced successfully';
            // Refresh the routes list after syncing
            await fetchApiRoutes();
            return { success: true, data: response.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to sync routes';
            syncMessage.value = error.value;
            return { success: false, error: error.value };
        } finally {
            isSyncing.value = false;
        }
    };

    const clearSyncMessage = () => {
        syncMessage.value = '';
    };

    return {
        // State
        apiRoutes,
        isLoading,
        isSyncing,
        error,
        syncMessage,
        pagination,

        // Computed
        apiRoutesList,
        totalApiRoutes,

        // Actions
        fetchApiRoutes,
        createApiRoute,
        updateApiRoute,
        deleteApiRoute,
        syncRoutes,
        clearSyncMessage,
    };
});

