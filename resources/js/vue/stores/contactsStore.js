import { defineStore } from 'pinia';
import axios from 'axios';

export const useContactsStore = defineStore('contacts', {
  state: () => ({
    contacts: [],
    pagination: {
      total: 0,
      per_page: 15,
      current_page: 1,
      last_page: 1,
      from: 1,
      to: 0,
    },
    loading: false,
    error: null,
  }),

  getters: {
    getContactById: (state) => (id) => {
      return state.contacts.find(contact => contact.id === id);
    },

    getNewContactsCount: (state) => {
      return state.contacts.filter(contact => contact.status === 'new').length;
    },
  },

  actions: {
    async fetchContacts(params = {}) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.get('/api/v1/SuperAdmin/contacts', {
          params: {
            per_page: params.per_page || 15,
            page: params.page || 1,
            search: params.search || '',
            status: params.status || '',
          },
        });

        this.contacts = response.data.data;
        this.pagination = response.data.pagination;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch contacts';
        console.error('Error fetching contacts:', error);
      } finally {
        this.loading = false;
      }
    },

    async getContact(id) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.get(`/api/v1/SuperAdmin/contacts/${id}`);
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch contact';
        console.error('Error fetching contact:', error);
      } finally {
        this.loading = false;
      }
    },

    async updateContact(id, data) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.put(`/api/v1/SuperAdmin/contacts/${id}`, data);
        
        // Update contact in local state
        const index = this.contacts.findIndex(c => c.id === id);
        if (index !== -1) {
          this.contacts[index] = response.data.data;
        }

        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update contact';
        console.error('Error updating contact:', error);
      } finally {
        this.loading = false;
      }
    },

    async deleteContact(id) {
      this.loading = true;
      this.error = null;

      try {
        await axios.delete(`/api/v1/SuperAdmin/contacts/${id}`);
        
        // Remove contact from local state
        this.contacts = this.contacts.filter(c => c.id !== id);
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete contact';
        console.error('Error deleting contact:', error);
      } finally {
        this.loading = false;
      }
    },

    clearError() {
      this.error = null;
    },
  },
});

