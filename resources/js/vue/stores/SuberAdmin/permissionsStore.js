import { defineStore } from "pinia";
import apiClient from "../../services/api";
import { useToastStore } from "../toastStore";

export const usePermissionsStore = defineStore("permissions", {
    // =====================
    // State
    // =====================
    state: () => ({
        permissions: [],
        allPermissions: [],
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
        permissionsList: (state) => state.permissions,
        totalPermissions: (state) => state.pagination.total,
        permissionsError: (state) => state.error,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Fetch Permissions
        // ------------------------------------
        async fetchPermissions(page = 1, perPage = 10) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/SuperAdmin/permissions", {
                    params: { page, per_page: perPage },
                });

                this.permissions = response.data.data;
                this.pagination = {
                    current_page: response.data.meta?.current_page || page,
                    per_page: response.data.meta?.per_page || perPage,
                    total: response.data.meta?.total || 0,
                };
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch permissions";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Fetch All Permissions
        // ------------------------------------
        async fetchAllPermissions() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/SuperAdmin/role-permissions/all");
                this.allPermissions = response.data.data;
                return { success: true, data: response.data.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch all permissions";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Assign Permissions to Role
        // ------------------------------------
        async assignPermissionsToRole(roleId, permissionIds) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post(`/SuperAdmin/roles/${roleId}/permissions`, {
                    permission_ids: permissionIds,
                });
                const toast = useToastStore();
                toast.success("Permissions Assigned", "Permissions have been assigned to the role successfully");
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to assign permissions";
                const toast = useToastStore();
                toast.error("Assignment Failed", this.error);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Fetch Role Permissions
        // ------------------------------------
        async fetchRolePermissions(roleId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get(`/SuperAdmin/roles/${roleId}/permissions`);
                return { success: true, data: response.data.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch role permissions";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },
    },
});

