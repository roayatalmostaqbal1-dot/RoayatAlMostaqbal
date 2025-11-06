import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import { useToastStore } from './toastStore';

export const useRolesStore = defineStore('roles', () => {
    // State
    const roles = ref([]);
    const selectedRole = ref(null);
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
    const rolesList = computed(() => roles.value);
    const totalRoles = computed(() => pagination.value.total);

    // Actions
    const fetchRoles = async (page = 1, perPage = 10) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/roles', {
                params: { page, per_page: perPage },
            });
            roles.value = response.data.data;
            pagination.value = {
                current_page: response.data.meta?.current_page || page,
                per_page: response.data.meta?.per_page || perPage,
                total: response.data.meta?.total || 0,
            };
            return { success: true, data: response.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch roles';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const createRole = async (roleData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/roles', roleData);
            roles.value.push(response.data.data);

            // Show success toast
            toastStore.success(
                'Role Created',
                response.data.message || 'Role has been created successfully'
            );

            return { success: true, data: response.data.data };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to create role';
            error.value = errorMessage;

            // Show error toast
            toastStore.error('Create Failed', errorMessage);

            return { success: false, error: errorMessage, errors: err.response?.data?.errors };
        } finally {
            isLoading.value = false;
        }
    };

    const updateRole = async (roleId, roleData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.put(`/SuperAdmin/roles/${roleId}`, roleData);
            const index = roles.value.findIndex(r => r.id === roleId);
            if (index !== -1) {
                roles.value[index] = response.data.data;
            }

            // Show success toast
            toastStore.success(
                'Role Updated',
                response.data.message || 'Role has been updated successfully'
            );

            return { success: true, data: response.data.data };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to update role';
            error.value = errorMessage;

            // Show error toast
            toastStore.error('Update Failed', errorMessage);

            return { success: false, error: errorMessage, errors: err.response?.data?.errors };
        } finally {
            isLoading.value = false;
        }
    };

    const deleteRole = async (roleId) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.delete(`/SuperAdmin/roles/${roleId}`);
            roles.value = roles.value.filter(r => r.id !== roleId);

            // Show success toast
            toastStore.success(
                'Role Deleted',
                response.data?.message || 'Role has been deleted successfully'
            );

            return { success: true };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to delete role';
            error.value = errorMessage;

            // Show error toast
            toastStore.error('Delete Failed', errorMessage);

            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const setSelectedRole = (role) => {
        selectedRole.value = role;
    };

    const clearSelectedRole = () => {
        selectedRole.value = null;
    };

    return {
        // State
        roles,
        selectedRole,
        isLoading,
        error,
        pagination,

        // Computed
        rolesList,
        totalRoles,

        // Actions
        fetchRoles,
        createRole,
        updateRole,
        deleteRole,
        setSelectedRole,
        clearSelectedRole,
    };
});

