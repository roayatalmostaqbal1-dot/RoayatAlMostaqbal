import { defineStore } from "pinia";
import apiClient from "../services/api";
import router from "../router/router";

export const useAuthStore = defineStore("auth", {
    // =====================
    // State
    // =====================
    state: () => ({
        authUser: null,
        authToken: localStorage.getItem("token") || null,
        isLoading: false,
        authErrors: null,

        authRoles: [],
        authPermissions: [],

        twoFactorRequired: false,
        twoFactorUserId: null,
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        user: (state) => state.authUser,
        errors: (state) => state.authErrors,
        roles: (state) => state.authRoles,
        token: (state) => state.authToken,
        permissions: (state) => state.authPermissions,

        isAuthenticated: (state) => !!state.authToken,
        userName: (state) => state.authUser?.name || "",
        userEmail: (state) => state.authUser?.email || "",

        // =====================
        // Permission Checkers
        // =====================
        /**
         * Check if user has a specific permission
         */
        hasPermission: (state) => (permission) => {
            return state.authPermissions.includes(permission);
        },

        /**
         * Check if user has any of the given permissions
         */
        hasAnyPermission: (state) => (permissions) => {
            return permissions.some(permission => state.authPermissions.includes(permission));
        },

        /**
         * Check if user has all of the given permissions
         */
        hasAllPermissions: (state) => (permissions) => {
            return permissions.every(permission => state.authPermissions.includes(permission));
        },

        /**
         * Check if user has a specific role
         */
        hasRole: (state) => (role) => {
            return state.authRoles.includes(role);
        },

        /**
         * Check if user has any of the given roles
         */
        hasAnyRole: (state) => (roles) => {
            return roles.some(role => state.authRoles.includes(role));
        },

        /**
         * Check if user has all of the given roles
         */
        hasAllRoles: (state) => (roles) => {
            return roles.every(role => state.authRoles.includes(role));
        },

        /**
         * Check if user is super admin
         */
        isSuperAdmin: (state) => state.authRoles.includes('super-admin'),

        /**
         * Check if user is admin
         */
        isAdmin: (state) => state.authRoles.includes('admin') || state.authRoles.includes('super-admin'),
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Login
        // ------------------------------------
        async login(email, password) {
            this.isLoading = true;
            this.authErrors = null;

            try {
                const response = await apiClient.post("/auth/login", {
                    email,
                    password,
                });
                if (response.data?.data?.two_factor_enabled) {
                    this.twoFactorRequired = true;
                    this.twoFactorUserId = response.data.data.user_id;

                    return {
                        success: true,
                        twoFactor: true,
                        data: response.data.data
                    };
                }
                this.authToken = response.data.token;
                this.authUser = response.data.data.user_info;
                this.authRoles = response.data.data.roles || [];
                this.authPermissions = response.data.data.permissions || [];

                localStorage.setItem("token", this.authToken);

                return { success: true, data: response.data };
            } catch (err) {
                this.authErrors =
                    err.response?.data?.message || "Login failed";
                return { success: false, error: this.authErrors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Register
        // ------------------------------------
        async register(userData) {
            this.isLoading = true;
            this.authErrors = null;

            try {
                const response = await apiClient.post(
                    "/auth/register",
                    userData
                );

                this.authToken = response.data.token;
                this.authUser = response.data.data.user_info;
                this.authRoles = response.data.data.roles || [];
                this.authPermissions = response.data.data.permissions || [];

                localStorage.setItem("token", this.authToken);

                return { success: true, data: response.data };
            } catch (err) {
                this.authErrors =
                    err.response?.data?.message || "Registration failed";
                return { success: false, error: this.authErrors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Logout
        // ------------------------------------
        async logout() {
            this.isLoading = true;
            this.authErrors = null;

            try {
                await apiClient.post("/logout");
            } catch (err) {
                this.authErrors =
                    err.response?.data?.message || "Logout failed";
            } finally {
                this.authToken = null;
                this.authUser = null;
                this.authRoles = [];
                this.authPermissions = [];
                this.isLoading = false;
                localStorage.removeItem("token");
            }
        },

        // ------------------------------------
        // Fetch User
        // ------------------------------------
        async fetchUser() {
            if (!this.authToken) {
                return { success: false, error: "No token available" };
            }

            this.isLoading = true;
            this.authErrors = null;

            try {
                const response = await apiClient.get("/user");
                this.authUser = response.data.data.user_info || response.data.data;
                this.authRoles = response.data.data.roles || [];
                this.authPermissions = response.data.data.permissions || [];

                return { success: true, data: this.authUser };
            } catch (err) {
                this.authErrors =
                    err.response?.data?.message ||
                    "Failed to fetch user";

                this.authToken = null;
                this.authRoles = [];
                this.authPermissions = [];
                localStorage.removeItem("token");

                return { success: false, error: this.authErrors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Clear Error
        // ------------------------------------
        clearError() {
            this.authErrors = null;
        },

        // ------------------------------------
        // Two Factor Reset
        // ------------------------------------
        resetTwoFactor() {
            this.twoFactorRequired = false;
            this.twoFactorUserId = null;
        },

        // ------------------------------------
        // Verify 2FA
        // ------------------------------------
        async verify(code) {
            this.isLoading = true;
            this.authErrors = null;

            try {
                const response = await apiClient.post(
                    "/auth/two-factor/verify",
                    {
                        code,
                        user_id: this.twoFactorUserId
                    }
                );

                if (response.data.success) {
                    this.authToken = response.data.token;
                    this.authUser = response.data.user;
                    this.authRoles = response.data.roles || [];
                    this.authPermissions = response.data.permissions || [];

                    localStorage.setItem("token", this.authToken);

                    return { success: true };
                } else {
                    throw new Error(response.data.message || "Invalid verification code");
                }
            } catch (err) {
                this.authErrors = err.response?.data?.message || err.message;
                return { success: false, error: this.authErrors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Change Password
        // ------------------------------------
        async changePassword(currentPassword, newPassword, newPasswordConfirmation) {
            this.isLoading = true;
            this.authErrors = null;

            try {
                const response = await apiClient.post("/change-password", {
                    current_password: currentPassword,
                    new_password: newPassword,
                    new_password_confirmation: newPasswordConfirmation,
                });

                if (response.data.success || response.status === 200) {
                    return { success: true, data: response.data };
                } else {
                    throw new Error(response.data.message || "Failed to change password");
                }
            } catch (err) {
                const errorMessage = err.response?.data?.message || err.message || "Failed to change password";
                this.authErrors = errorMessage;

                // Handle validation errors
                if (err.response?.status === 422) {
                    const validationErrors = err.response?.data?.errors || {};
                    return { success: false, errors: validationErrors, error: errorMessage };
                }

                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Social Auth Redirect
        // ------------------------------------
        socialAuthRedirect(provider) {
            const callbackUrl = `${window.location.origin}/admin/social-callback`;

            const redirectUrl =
                `/api/v1/social-auth/${provider}?callback=${encodeURIComponent(callbackUrl)}`;

            const width = 550;
            const height = 650;
            const left = window.screenX + (window.outerWidth - width) / 2;
            const top = window.screenY + (window.outerHeight - height) / 2;

            const popup = window.open(
                redirectUrl,
                `${provider}-login`,
                `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
            );

            if (!popup) {
                this.authErrors = "❌ الرجاء السماح للنوافذ المنبثقة.";
                return;
            }

            this.boundHandleMessage = this.handleMessage.bind(this);
            window.addEventListener("message", this.boundHandleMessage);
        },


        handleMessage(event) {
            if (event.origin !== window.location.origin) return;

            const { type, token, user, message, user_id, roles, permissions } = event.data || {};

            if (type === "SOCIAL_AUTH_SUCCESS") {
                this.authToken = token;
                this.authUser = user;
                this.authRoles = roles || [];
                this.authPermissions = permissions || [];
                localStorage.setItem("token", token);

                window.removeEventListener("message", this.boundHandleMessage);

                window.dispatchEvent(
                    new CustomEvent("social-auth-success", { detail: { token, user, roles, permissions } })
                );
            }

            else if (type === "SOCIAL_AUTH_CANCELLED") {
                this.authErrors = null;
                window.removeEventListener("message", this.boundHandleMessage);
            }

            else if (type === "SOCIAL_AUTH_2FA_REQUIRED") {
                this.twoFactorRequired = true;
                this.twoFactorUserId = user_id;
                this.authUser = user;
                this.authRoles = roles || [];
                this.authPermissions = permissions || [];

                window.removeEventListener("message", this.boundHandleMessage);

                window.dispatchEvent(
                    new CustomEvent("social-auth-2fa-required", { detail: { user_id, user, roles, permissions } })
                );
            }
        },

    },
});
