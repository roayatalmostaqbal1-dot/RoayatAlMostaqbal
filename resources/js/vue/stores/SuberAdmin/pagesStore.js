import { defineStore } from 'pinia';
import apiClient from '@/vue/services/api';

export const usePagesStore = defineStore('pages', {
    state: () => ({
        pages: [],
        roles: [],
        isLoading: false,
        error: null,
        pagination: {
            total: 0,
            per_page: 15,
            current_page: 1,
            last_page: 1,
        },
    }),

    getters: {
        /**
         * Get pages grouped by role
         */
        pagesByRole: (state) => {
            return state.roles.map(role => ({
                ...role,
                pages: state.pages.filter(page =>
                    page.roles?.some(r => r.id === role.id)
                ),
            }));
        },

        /**
         * Get a specific page by key
         */
        getPageByKey: (state) => (pageKey) => {
            return state.pages.find(page => page.key === pageKey);
        },

        /**
         * Get pages for a specific role
         */
        getPagesForRole: (state) => (roleId) => {
            return state.pages.filter(page =>
                page.roles?.some(r => r.id === roleId)
            );
        },
    },

    actions: {
        /**
         * Fetch all pages with their role assignments
         */
        async fetchPages() {
            this.isLoading = true;
            this.error = null;
            try {
                const response = await apiClient.get('/SuperAdmin/pages-with-roles/all');
                this.pages = response.data.data;
            } catch (err) {
                this.error = err.response?.data?.message || err.message;
                console.error('Error fetching pages:', err);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Fetch pages with pagination
         */
        async fetchPagesPaginated(page = 1, perPage = 15) {
            this.isLoading = true;
            this.error = null;
            try {
                const response = await apiClient.get('/SuperAdmin/pages', {
                    params: { page, per_page: perPage },
                });
                this.pages = response.data.data;
                this.pagination = response.data.pagination;
            } catch (err) {
                this.error = err.response?.data?.message || err.message;
                console.error('Error fetching pages:', err);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Assign pages to a role
         */
        async assignPagesToRole(roleId, pageKeys) {
            this.isLoading = true;
            this.error = null;
            try {
                const response = await apiClient.post(
                    `/SuperAdmin/roles/${roleId}/pages`,
                    { page_keys: pageKeys }
                );
                // Refresh pages after assignment
                await this.fetchPages();
                return { success: true, data: response.data };
            } catch (err) {
                this.error = err.response?.data?.message || err.message;
                console.error('Error assigning pages:', err);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Remove a page from a role
         */
        async removePageFromRole(roleId, pageKey) {
            this.isLoading = true;
            this.error = null;
            try {
                await apiClient.delete(
                    `/SuperAdmin/roles/${roleId}/pages/${pageKey}`
                );
                // Refresh pages after removal
                await this.fetchPages();
                return { success: true };
            } catch (err) {
                this.error = err.response?.data?.message || err.message;
                console.error('Error removing page:', err);
                return { success: false, error: this.error };
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Clear error message
         */
        clearError() {
            this.error = null;
        },
    },
});

