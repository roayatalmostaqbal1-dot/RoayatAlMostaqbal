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

        authRole: null,
        authPermissions: null,

        twoFactorRequired: false,
        twoFactorUserId: null,
    }),

    // =====================
    // Getters
    // =====================
    getters: {
        user: (state) => state.authUser,
        errors: (state) => state.authErrors,
        roles: (state) => state.authRole,
        token: (state) => state.authToken,
        permissions: (state) => state.authPermissions,

        isAuthenticated: (state) => !!state.authToken,
        userName: (state) => state.authUser?.name || "",
        userEmail: (state) => state.authUser?.email || "",
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

                this.authToken = response.data.token;
                this.authUser = response.data.data.user_info;

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
                this.authUser = response.data.data;

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
                this.authUser = response.data.data;

                return { success: true, data: this.authUser };
            } catch (err) {
                this.authErrors =
                    err.response?.data?.message ||
                    "Failed to fetch user";

                this.authToken = null;
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
                const userId =
                    this.authUser?.id || this.authUser?.user_id;

                if (!userId) {
                    throw new Error("User ID not found. Please login again.");
                }

                const response = await apiClient.post(
                    "/auth/two-factor/verify",
                    { code, user_id: userId }
                );

                if (response.data.success) {
                    this.authToken = response.data.token;
                    this.authUser = response.data.user;

                    localStorage.setItem("token", this.authToken);

                    return { success: true, user: response.data.user };
                } else {
                    throw new Error(
                        response.data.message ||
                        "Invalid verification code"
                    );
                }
            } catch (err) {
                this.authErrors =
                    err.response?.data?.message || err.message;
                return { success: false, error: this.authErrors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Social Auth Redirect
        // ------------------------------------
        socialAuthRedirect(provider) {
            const API_BASE_URL = "/api/v1";
            const callbackUrl = `${window.location.origin}/admin/social-callback`;

            const redirectUrl = `${API_BASE_URL}/social-auth/${provider}?callback=${encodeURIComponent(callbackUrl)}`;

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
                this.authErrors = "❌ تم حظر النوافذ المنبثقة. الرجاء السماح للنوافذ لهذا الموقع.";
                return;
            }

            // IMPORTANT — bind context
            this.boundHandleMessage = this.handleMessage.bind(this);

            window.addEventListener("message", this.boundHandleMessage);
        },

        handleMessage(event) {
            if (event.origin !== window.location.origin) return;

            const { type, token, user, message, user_id } = event.data || {};

            if (type === "SOCIAL_AUTH_SUCCESS") {
                this.authToken = token;
                this.authUser = user;
                localStorage.setItem("token", token);

                window.removeEventListener("message", this.boundHandleMessage);

                window.dispatchEvent(
                    new CustomEvent("social-auth-success", { detail: { token, user } })
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

                window.removeEventListener("message", this.boundHandleMessage);

                window.dispatchEvent(
                    new CustomEvent("social-auth-2fa-required", { detail: { user_id, user } })
                );
            }
        },

    },
});
