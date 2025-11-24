import { defineStore } from "pinia";
import apiClient from "../services/api";
import { useToastStore } from "./toastStore";

export const useUsersStore = defineStore("users", {
    // =====================
    // State
    // =====================
    state: () => ({
        users: [],
        selectedUser: null,
        isLoading: false,
        error: null,
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
        },
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        usersList: (state) => state.users,
        totalUsers: (state) => state.pagination.total,
        currentPage: (state) => state.pagination.current_page,
        lastPage: (state) => state.pagination.last_page,
        hasNextPage: (state) => state.pagination.current_page < state.pagination.last_page,
        hasPrevPage: (state) => state.pagination.current_page > 1,
        usersError: (state) => state.error,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Fetch Users
        // ------------------------------------
        async fetchUsers(page = 1, perPage = 10) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/SuperAdmin/users", {
                    params: { page, per_page: perPage },
                });
                this.users = response.data.data;
                this.pagination = {
                    current_page: response.data.meta?.current_page || page,
                    per_page: response.data.meta?.per_page || perPage,
                    total: response.data.meta?.total || 0,
                    last_page: response.data.meta?.last_page || 1,
                };
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch users";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Create User
        // ------------------------------------
        async createUser(userData) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/SuperAdmin/users", userData);
                // Add the new user to the current list if we're on the first page
                if (this.pagination.current_page === 1) {
                    this.users.unshift(response.data.data || response.data);
                }
                // Update total count
                this.pagination.total += 1;

                const toast = useToastStore();
                toast.success("User Created", response.data.message || "User has been created successfully");

                return { success: true, data: response.data.data || response.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to create user";
                this.error = errorMessage;

                const toast = useToastStore();
                toast.error("Create Failed", errorMessage);

                return { success: false, error: errorMessage, errors: err.response?.data?.errors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Update User
        // ------------------------------------
        async updateUser(userId, userData) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.put(`/SuperAdmin/users/${userId}`, userData);
                const index = this.users.findIndex(user => user.id === userId);
                if (index !== -1) {
                    this.users[index] = response.data.data || response.data;
                }

                const toast = useToastStore();
                toast.success("User Updated", response.data.message || "User has been updated successfully");

                return { success: true, data: response.data.data || response.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to update user";
                this.error = errorMessage;

                const toast = useToastStore();
                toast.error("Update Failed", errorMessage);

                return { success: false, error: errorMessage, errors: err.response?.data?.errors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Delete User
        // ------------------------------------
        async deleteUser(userId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.delete(`/SuperAdmin/users/${userId}`);
                this.users = this.users.filter(user => user.id !== userId);
                this.pagination.total -= 1;

                const toast = useToastStore();
                toast.success("User Deleted", response.data?.message || "User has been deleted successfully");

                return { success: true };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to delete user";
                this.error = errorMessage;

                const toast = useToastStore();
                toast.error("Delete Failed", errorMessage);

                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Set Selected User
        // ------------------------------------
        setSelectedUser(user) {
            this.selectedUser = user;
        },

        // ------------------------------------
        // Clear Selected User
        // ------------------------------------
        clearSelectedUser() {
            this.selectedUser = null;
        },

        // ------------------------------------
        // Go to Page
        // ------------------------------------
        async goToPage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                await this.fetchUsers(page, this.pagination.per_page);
            }
        },

        // ------------------------------------
        // Next Page
        // ------------------------------------
        async nextPage() {
            if (this.hasNextPage) {
                await this.goToPage(this.pagination.current_page + 1);
            }
        },

        // ------------------------------------
        // Previous Page
        // ------------------------------------
        async prevPage() {
            if (this.hasPrevPage) {
                await this.goToPage(this.pagination.current_page - 1);
            }
        },

        // ------------------------------------
        // Clear Error
        // ------------------------------------
        clearError() {
            this.error = null;
        },
    },
});
