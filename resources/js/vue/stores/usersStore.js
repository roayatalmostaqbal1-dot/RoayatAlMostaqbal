import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../services/api';
import { useToastStore } from './toastStore';

export const useUsersStore = defineStore('users', () => {
    // State
    const users = ref([]);
    const selectedUser = ref(null);
    const isLoading = ref(false);
    const error = ref(null);
    const pagination = ref({
        current_page: 1,
        per_page: 10,
        total: 0,
        last_page: 1,
    });

    // Toast store
    const toastStore = useToastStore();

    // Computed
    const usersList = computed(() => users.value);
    const totalUsers = computed(() => pagination.value.total);
    const currentPage = computed(() => pagination.value.current_page);
    const lastPage = computed(() => pagination.value.last_page);
    const hasNextPage = computed(() => pagination.value.current_page < pagination.value.last_page);
    const hasPrevPage = computed(() => pagination.value.current_page > 1);

    // Actions
    const fetchUsers = async (page = 1, perPage = 10) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.get('/SuperAdmin/users', {
                params: { page, per_page: perPage },
            });
            users.value = response.data.data;
            pagination.value = {
                current_page: response.data.meta?.current_page || page,
                per_page: response.data.meta?.per_page || perPage,
                total: response.data.meta?.total || 0,
                last_page: response.data.meta?.last_page || 1,
            };
            return { success: true, data: response.data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch users';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const createUser = async (userData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.post('/SuperAdmin/users', userData);
            // Add the new user to the current list if we're on the first page
            if (pagination.value.current_page === 1) {
                users.value.unshift(response.data.data || response.data);
            }
            // Update total count
            pagination.value.total += 1;

            // Show success toast
            toastStore.success(
                'User Created',
                response.data.message || 'User has been created successfully'
            );

            return { success: true, data: response.data.data || response.data };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to create user';
            error.value = errorMessage;

            // Show error toast
            toastStore.error('Create Failed', errorMessage);

            return { success: false, error: errorMessage, errors: err.response?.data?.errors };
        } finally {
            isLoading.value = false;
        }
    };

    const updateUser = async (userId, userData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.put(`/SuperAdmin/users/${userId}`, userData);
            const index = users.value.findIndex(user => user.id === userId);
            if (index !== -1) {
                users.value[index] = response.data.data || response.data;
            }

            // Show success toast
            toastStore.success(
                'User Updated',
                response.data.message || 'User has been updated successfully'
            );

            return { success: true, data: response.data.data || response.data };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to update user';
            error.value = errorMessage;

            // Show error toast
            toastStore.error('Update Failed', errorMessage);

            return { success: false, error: errorMessage, errors: err.response?.data?.errors };
        } finally {
            isLoading.value = false;
        }
    };

    const deleteUser = async (userId) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await apiClient.delete(`/SuperAdmin/users/${userId}`);
            users.value = users.value.filter(user => user.id !== userId);
            pagination.value.total -= 1;

            // Show success toast
            toastStore.success(
                'User Deleted',
                response.data?.message || 'User has been deleted successfully'
            );

            return { success: true };
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to delete user';
            error.value = errorMessage;

            // Show error toast
            toastStore.error('Delete Failed', errorMessage);

            return { success: false, error: errorMessage };
        } finally {
            isLoading.value = false;
        }
    };

    const setSelectedUser = (user) => {
        selectedUser.value = user;
    };

    const clearSelectedUser = () => {
        selectedUser.value = null;
    };

    const goToPage = async (page) => {
        if (page >= 1 && page <= pagination.value.last_page) {
            await fetchUsers(page, pagination.value.per_page);
        }
    };

    const nextPage = async () => {
        if (hasNextPage.value) {
            await goToPage(pagination.value.current_page + 1);
        }
    };

    const prevPage = async () => {
        if (hasPrevPage.value) {
            await goToPage(pagination.value.current_page - 1);
        }
    };

    const clearError = () => {
        error.value = null;
    };

    return {
        // State
        users,
        selectedUser,
        isLoading,
        error,
        pagination,

        // Computed
        usersList,
        totalUsers,
        currentPage,
        lastPage,
        hasNextPage,
        hasPrevPage,

        // Actions
        fetchUsers,
        createUser,
        updateUser,
        deleteUser,
        setSelectedUser,
        clearSelectedUser,
        goToPage,
        nextPage,
        prevPage,
        clearError,
    };
});
