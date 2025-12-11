import { defineStore } from "pinia";
import apiClient from "../services/api";
import { useToastStore } from "./toastStore";

export const useOAuth2ClientsStore = defineStore("oauth2Clients", {
    // =====================
    // State
    // =====================
    state: () => ({
        clients: [],
        selectedClient: null,
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
        clientsList: (state) => state.clients,
        totalClients: (state) => state.pagination.total,
        clientsError: (state) => state.error,
    },

    // =====================
    // Actions
    // =====================
    actions: {
        // ------------------------------------
        // Fetch OAuth2 Clients
        // ------------------------------------
        async fetchClients(page = 1, perPage = 10) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get("/SuperAdmin/oauth2-clients", {
                    params: { page, per_page: perPage },
                });
                this.clients = response.data.data;
                this.pagination = {
                    current_page: response.data.meta?.current_page || page,
                    per_page: response.data.meta?.per_page || perPage,
                    total: response.data.meta?.total || 0,
                };
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || "Failed to fetch clients";
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Create OAuth2 Client
        // ------------------------------------
        async createClient(clientData) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post("/SuperAdmin/oauth2-clients", clientData);

                // Refresh the clients list to ensure pagination is correct
                // Add the new client to the beginning of the list
                this.clients.unshift(response.data.data);

                // Update pagination total
                this.pagination.total += 1;

                const toast = useToastStore();
                toast.success("Client Created", response.data.message || "OAuth2 client has been created successfully");

                return { success: true, data: response.data.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to create client";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Create Failed", errorMessage);
                return { success: false, error: errorMessage, errors: err.response?.data?.errors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Get Single OAuth2 Client
        // ------------------------------------
        async getClient(clientId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.get(`/SuperAdmin/oauth2-clients/${clientId}`);
                this.selectedClient = response.data.data;
                return { success: true, data: response.data.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to fetch client";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Fetch Failed", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Update OAuth2 Client
        // ------------------------------------
        async updateClient(clientId, clientData) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.put(`/SuperAdmin/oauth2-clients/${clientId}`, clientData);
                const index = this.clients.findIndex(c => c.id === clientId);
                if (index !== -1) {
                    // Update the client in the list
                    this.clients[index] = response.data.data;
                    // Ensure reactivity by replacing the array
                    this.clients = [...this.clients];
                }

                const toast = useToastStore();
                toast.success("Client Updated", response.data.message || "OAuth2 client has been updated successfully");

                return { success: true, data: response.data.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to update client";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Update Failed", errorMessage);
                return { success: false, error: errorMessage, errors: err.response?.data?.errors };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Delete OAuth2 Client
        // ------------------------------------
        async deleteClient(clientId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.delete(`/SuperAdmin/oauth2-clients/${clientId}`);
                this.clients = this.clients.filter(c => c.id !== clientId);

                // Update pagination total
                if (this.pagination.total > 0) {
                    this.pagination.total -= 1;
                }

                const toast = useToastStore();
                toast.success("Client Deleted", response.data?.message || "OAuth2 client has been deleted successfully");

                return { success: true };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to delete client";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Delete Failed", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Regenerate Client Secret
        // ------------------------------------
        async regenerateSecret(clientId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await apiClient.post(`/SuperAdmin/oauth2-clients/${clientId}/regenerate-secret`);
                const index = this.clients.findIndex(c => c.id === clientId);
                if (index !== -1) {
                    // Update the client in the list
                    this.clients[index] = response.data.data;
                    // Ensure reactivity by replacing the array
                    this.clients = [...this.clients];
                }
                this.selectedClient = response.data.data;

                const toast = useToastStore();
                toast.success("Secret Regenerated", response.data.message || "Client secret has been regenerated successfully");

                return { success: true, data: response.data.data };
            } catch (err) {
                const errorMessage = err.response?.data?.message || "Failed to regenerate secret";
                this.error = errorMessage;
                const toast = useToastStore();
                toast.error("Regenerate Failed", errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.isLoading = false;
            }
        },

        // ------------------------------------
        // Set Selected Client
        // ------------------------------------
        setSelectedClient(client) {
            this.selectedClient = client;
        },

        // ------------------------------------
        // Clear Selected Client
        // ------------------------------------
        clearSelectedClient() {
            this.selectedClient = null;
        },
    },
});

