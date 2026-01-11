import { defineStore } from 'pinia';
import apiClient from '@/vue/services/api';
import { useToastStore } from '@/vue/stores/toastStore';

export const useTelegramStore = defineStore('telegram', {
    state: () => ({
        chats: [],
        messages: [],
        selectedChatId: null,
        loadingChats: false,
        loadingMessages: false,
        sending: false,
        error: null,
        searchQuery: '',
    }),

    getters: {
        selectedChat: (state) => {
            return state.chats.find(chat => chat.id === state.selectedChatId);
        },
        hasUnread: (state) => {
            const chat = state.chats.find(c => c.id === state.selectedChatId);
            return chat ? chat.unread_count > 0 : false;
        }
    },

    actions: {
        async fetchChats(search = '') {
            this.loadingChats = true;
            this.searchQuery = search;
            try {
                const response = await apiClient.get('/admin/telegram/chats', {
                    params: {
                        search: this.searchQuery,
                        per_page: 50
                    }
                });
                this.chats = response.data.data.data;
                return { success: true };
            } catch (error) {
                console.error('Error loading chats:', error);
                return { success: false, error };
            } finally {
                this.loadingChats = false;
            }
        },

        async fetchMessages(chatId) {
            if (!chatId) return;
            this.loadingMessages = true;
            this.selectedChatId = chatId;
            try {
                const response = await apiClient.get(`/admin/telegram/chats/${chatId}`);
                this.messages = response.data.data.messages.data;
                return { success: true };
            } catch (error) {
                console.error('Error loading messages:', error);
                return { success: false, error };
            } finally {
                this.loadingMessages = false;
            }
        },

        async sendMessage(chatId, text) {
            if (!text.trim() || !chatId || this.sending) return;

            const toast = useToastStore();
            this.sending = true;
            try {
                const response = await apiClient.post(
                    `/admin/telegram/chats/${chatId}/send`,
                    { message: text }
                );

                this.messages.push(response.data.data);

                // Update last message in the chat list
                const chat = this.chats.find(c => c.id === chatId);
                if (chat) {
                    chat.last_message_at = response.data.data.sent_at;
                }

                return { success: true, data: response.data.data };
            } catch (error) {
                const errorMessage = error.response?.data?.message || 'Failed to send message';
                toast.error('Telegram Error', errorMessage);
                return { success: false, error: errorMessage };
            } finally {
                this.sending = false;
            }
        },

        async markAsRead(chatId) {
            if (!chatId) return;

            try {
                await apiClient.post(`/admin/telegram/chats/${chatId}/read`);

                // Update local chat unread count
                const chat = this.chats.find(c => c.id === chatId);
                if (chat) {
                    chat.unread_count = 0;
                }

                // Mark messages as read
                this.messages.forEach(msg => {
                    if (msg.sender_type === 'user') {
                        msg.is_read = true;
                    }
                });
                return { success: true };
            } catch (error) {
                console.error('Error marking as read:', error);
                return { success: false, error };
            }
        },

        addReceivedMessage(message) {
            const chatIndex = this.chats.findIndex(c => c.id === message.telegram_chat_id);
            if (chatIndex !== -1) {
                const chat = this.chats[chatIndex];
                chat.last_message_at = message.sent_at;
                if (this.selectedChatId !== message.telegram_chat_id) {
                    chat.unread_count = (chat.unread_count || 0) + 1;
                }

                // Move to top
                this.chats.splice(chatIndex, 1);
                this.chats.unshift(chat);
            } else if (message.chat) {
                // New chat session
                this.chats.unshift(message.chat);
            }

            // Add to current message list if it belongs there
            if (this.selectedChatId === message.telegram_chat_id) {
                const exists = this.messages.find(m => m.id === message.id);
                if (!exists) {
                    this.messages.push(message);
                }
            }
        }
    }
});
