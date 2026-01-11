import { defineStore } from 'pinia';
import apiClient from '@/vue/services/api';
import { useToastStore } from '@/vue/stores/toastStore';


export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        unreadCount: 0,
        loading: false,
        currentPage: 1,
        lastPage: 1,
        total: 0,
        perPage: 10,
        filter: 'all', // all, read, unread
    }),

    actions: {
        async fetchNotifications(page = 1) {
            this.loading = true;
            try {
                const params = {
                    page,
                    per_page: this.perPage,
                };
                if (this.filter === 'read') params.read = 'true';
                if (this.filter === 'unread') params.read = 'false';

                const response = await apiClient.get('/notifications', { params });

                if (response.data.success) {
                    this.notifications = response.data.data;
                    this.currentPage = response.data.meta.current_page;
                    this.lastPage = response.data.meta.last_page;
                    this.total = response.data.meta.total;
                    this.unreadCount = response.data.meta.unread_count;
                }
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async markAsRead(id) {
            try {
                const response = await apiClient.post(`/notifications/${id}/read`);
                if (response.data.success) {
                    const index = this.notifications.findIndex(n => n.id === id);
                    if (index !== -1) {
                        this.notifications[index].read_at = new Date().toISOString();
                    }
                    this.unreadCount = response.data.unread_count;
                }
            } catch (error) {
                console.error('Failed to mark notification as read:', error);
            }
        },

        async markAllAsRead() {
            const toast = useToastStore();
            try {
                const response = await apiClient.post('/notifications/read-all');
                if (response.data.success) {
                    this.notifications.forEach(n => {
                        if (!n.read_at) n.read_at = new Date().toISOString();
                    });
                    this.unreadCount = 0;
                    toast.success('Success', 'All notifications marked as read');
                }
            } catch (error) {
                console.error('Failed to mark all as read:', error);
            }
        },

        async deleteNotification(id) {
            try {
                const response = await apiClient.delete(`/notifications/${id}`);
                if (response.data.success) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                    this.unreadCount = response.data.unread_count;
                    this.total--;
                }
            } catch (error) {
                console.error('Failed to delete notification:', error);
            }
        },

        setFilter(filter) {
            this.filter = filter;
            this.fetchNotifications(1);
        },

        addNotification(notification) {
            // Check if notification already exists
            if (this.notifications.find(n => n.id === notification.id)) return;

            this.notifications.unshift(notification);
            this.unreadCount++;
            this.total++;

            // Keep only perPage items
            if (this.notifications.length > this.perPage) {
                this.notifications.pop();
            }
        }
    }
});
