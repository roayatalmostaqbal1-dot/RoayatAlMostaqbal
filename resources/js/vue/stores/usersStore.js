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
        roles: [],
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
                const response = await apiClient.get("/admin/users", {
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
        // Fetch Single User by ID
        // ------------------------------------
        async fetchUserById(userId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get(`/admin/users/${userId}`);
                let userData = response.data.data || response.data;

                // Handle nested response structure from UserInfoResource
                if (userData.user_info && userData.roles) {
                    // Flatten the nested structure
                    userData = {
                        ...userData.user_info,
                        roles: userData.roles,
                        role: userData.roles && userData.roles.length > 0 ? userData.roles[0] : null,
                    };
                }

                // Ensure roles is an array of objects with id and name
                if (userData.roles && Array.isArray(userData.roles)) {
                    if (userData.roles.length > 0 && typeof userData.roles[0] === 'string') {
                        // Convert string roles to objects
                        userData.roles = userData.roles.map(roleName => ({
                            id: roleName,
                            name: roleName
                        }));
                    }
                }

                this.selectedUser = userData;
                return { success: true, data: this.selectedUser };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch user";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },
        async fetchRoles() {
            this.isLoading = true;
            this.error = null;
            try {
                const response = await apiClient.get("/admin/users/roles");
                this.roles = response.data.data;
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch roles";
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
                const response = await apiClient.post("/admin/users", userData);

                // Extract and flatten the response data
                let newUserData = response.data.data || response.data;

                // Handle nested response structure from UserInfoResource
                if (newUserData.user_info && newUserData.roles) {
                    // Flatten the nested structure
                    newUserData = {
                        ...newUserData.user_info,
                        roles: newUserData.roles,
                    };
                }

                // Add the new user to the current list if we're on the first page
                if (this.pagination.current_page === 1) {
                    this.users.unshift(newUserData);
                }
                // Update total count
                this.pagination.total += 1;

                const toast = useToastStore();
                toast.success("User Created", response.data.message || "User has been created successfully");

                return { success: true, data: newUserData };
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
                // Remove password if it's empty (optional on edit)
                const updateData = { ...userData };
                if (!updateData.password) {
                    delete updateData.password;
                }

                const response = await apiClient.put(`/admin/users/${userId}`, updateData);

                // Extract and flatten the response data
                let updatedUserData = response.data.data || response.data;

                // Handle nested response structure from UserInfoResource
                if (updatedUserData.user_info && updatedUserData.roles) {
                    // Flatten the nested structure
                    updatedUserData = {
                        ...updatedUserData.user_info,
                        roles: updatedUserData.roles,
                    };
                }

                // Update the user in the users array
                const index = this.users.findIndex(user => user.id === userId);
                if (index !== -1) {
                    this.users[index] = updatedUserData;
                }

                const toast = useToastStore();
                toast.success("User Updated", response.data.message || "User has been updated successfully");

                return { success: true, data: updatedUserData };
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
                const response = await apiClient.delete(`/admin/users/${userId}`);
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
