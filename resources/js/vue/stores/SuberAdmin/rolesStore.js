import { defineStore } from "pinia";
import apiClient from "@/vue/services/api";
import { useToastStore } from "@/vue/stores/toastStore";

export const useRolesStore = defineStore("roles", {
    // =====================
    // State
    // =====================
    state: () => ({
        roles: [],
        selectedRole: null,
        isLoading: false,
        error: null,
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
        },
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        rolesList: (state) => state.roles,
        totalRoles: (state) => state.pagination.total,
        rolesError: (state) => state.error,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Fetch Roles
        // ------------------------------------
        async fetchRoles(page = 1, perPage = 10) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/SuperAdmin/roles", {
                    params: { page, per_page: perPage },
                });
                this.roles = response.data.data;
                this.pagination = {
                    current_page: response.data.meta?.current_page || page,
                    per_page: response.data.meta?.per_page || perPage,
                    total: response.data.meta?.total || 0,
                };
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch roles";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Create Role
        // ------------------------------------
        async createRole(roleData) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/SuperAdmin/roles", roleData);
                this.roles.push(response.data.data);

                const toast = useToastStore();
                toast.success("Role Created", response.data.message || "Role has been created successfully");

                return { success: true, data: response.data.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to create role";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Create Failed", errorMessage);
                return { success: false, error: errorMessage, errors: err.response?.data?.errors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Update Role
        // ------------------------------------
        async updateRole(roleId, roleData) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.put(`/SuperAdmin/roles/${roleId}`, roleData);
                const index = this.roles.findIndex(r => r.id === roleId);
                if (index !== -1) {
                    this.roles[index] = response.data.data;
                }

                const toast = useToastStore();
                toast.success("Role Updated", response.data.message || "Role has been updated successfully");

                return { success: true, data: response.data.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to update role";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Update Failed", errorMessage);
                return { success: false, error: errorMessage, errors: err.response?.data?.errors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Delete Role
        // ------------------------------------
        async deleteRole(roleId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.delete(`/SuperAdmin/roles/${roleId}`);
                this.roles = this.roles.filter(r => r.id !== roleId);

                const toast = useToastStore();
                toast.success("Role Deleted", response.data?.message || "Role has been deleted successfully");

                return { success: true };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to delete role";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Delete Failed", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Set Selected Role
        // ------------------------------------
        setSelectedRole(role) {
            this.selectedRole = role;
        },

        // ------------------------------------
        // Clear Selected Role
        // ------------------------------------
        clearSelectedRole() {
            this.selectedRole = null;
        },
    },
});

