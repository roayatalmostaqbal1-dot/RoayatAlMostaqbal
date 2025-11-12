import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import { useToastStore } from './toastStore';

export const usePermissionsStore = defineStore('permissions', () => {
    // State
    const permissions = ref([]);
    const allPermissions = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const pagination = ref({
        current_page: 1,
        per_page: 10,
        total: 0,
    });

    // Toast store
    const toastStore = useToastStore();

    // Computed
    const permissionsList = computed(() => permissions.value);
    const totalPermissions = computed(() => pagination.value.total);

    // Actions
    const fetchPermissions = async (page = 1, perPage = 10) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/permissions', {
                params: { page, per_page: perPage },
            });

            permissions.value = response.data.data;
            pagination.value = {
                current_page: response.data.meta?.current_page || page,
                per_page: response.data.meta?.per_page || perPage,
                total: response.data.meta?.total || 0,
            };
            return { success: true, data: response.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch permissions';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const fetchAllPermissions = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/role-permissions/all');
            allPermissions.value = response.data.data;
            return { success: true, data: response.data.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch all permissions';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const createPermission = async (permissionData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/permissions', permissionData);
            permissions.value.push(response.data.data);

            toastStore.success('Permission Created', response.data.message || 'Permission has been created successfully');

            return { success: true, data: response.data.data };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to create permission';
            error.value = errorMessage;
            toastStore.error('Create Failed', errorMessage);
            return { success: false, error: errorMessage, errors: err.response?.data?.errors };
        } finally {
            isLoading.value = false;
        }
    };

    const updatePermission = async (permissionId, permissionData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.put(`/SuperAdmin/permissions/${permissionId}`, permissionData);
            const index = permissions.value.findIndex(p => p.id === permissionId);
            if (index !== -1) {
                permissions.value[index] = response.data.data;
            }

            toastStore.success('Permission Updated', response.data.message || 'Permission has been updated successfully');

            return { success: true, data: response.data.data };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to update permission';
            error.value = errorMessage;
            toastStore.error('Update Failed', errorMessage);
            return { success: false, error: errorMessage, errors: err.response?.data?.errors };
        } finally {
            isLoading.value = false;
        }
    };

    const deletePermission = async (permissionId) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.delete(`/SuperAdmin/permissions/${permissionId}`);
            permissions.value = permissions.value.filter(p => p.id !== permissionId);

            toastStore.success('Permission Deleted', response.data?.message || 'Permission has been deleted successfully');

            return { success: true };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to delete permission';
            error.value = errorMessage;
            toastStore.error('Delete Failed', errorMessage);
            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const assignPermissionsToRole = async (roleId, permissionIds) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post(`/SuperAdmin/roles/${roleId}/permissions`, {
                permission_ids: permissionIds,
            });
            return { success: true, data: response.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to assign permissions';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const fetchRolePermissions = async (roleId) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get(`/SuperAdmin/roles/${roleId}/permissions`);
            return { success: true, data: response.data.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch role permissions';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    return {
        // State
        permissions,
        allPermissions,
        isLoading,
        error,
        pagination,

        // Computed
        permissionsList,
        totalPermissions,

        // Actions
        fetchPermissions,
        fetchAllPermissions,
        // createPermission,
        // updatePermission,
        // deletePermission,
        assignPermissionsToRole,
        fetchRolePermissions,
    };
});

