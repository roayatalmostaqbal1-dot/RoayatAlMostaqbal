<template>
    <div class="telegram-chats-page">
        <div class="chats-container">
            <!-- Chat List Sidebar -->
            <div class="chat-list-panel">
                <div class="chat-list-header">
                    <h2>Telegram Chats</h2>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" v-model="searchQuery" placeholder="Search chats..."
                            @input="debouncedSearch" />
                    </div>
                </div>

                <div class="chat-list-body" v-if="!loadingChats">
                    <div v-if="chats.length === 0" class="empty-state">
                        <i class="fas fa-comments"></i>
                        <p>No chats yet</p>
                    </div>

                    <ChatListItem v-for="chat in chats" :key="chat.id" :chat="chat"
                        :isActive="selectedChatId === chat.id" @select="selectChat" />
                </div>

                <div v-else class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading chats...</p>
                </div>
            </div>

            <!-- Chat Window -->
            <div class="chat-window-panel">
                <div v-if="!selectedChatId" class="no-chat-selected">
                    <i class="fab fa-telegram-plane"></i>
                    <h3>Select a chat to start messaging</h3>
                    <p>Choose a conversation from the list to view messages</p>
                </div>

                <div v-else class="chat-window">
                    <!-- Chat Header -->
                    <div class="chat-window-header">
                        <div class="chat-header-info">
                            <div class="avatar-circle">
                                {{ getInitials(selectedChat?.full_name || '') }}
                            </div>
                            <div>
                                <h3>{{ selectedChat?.full_name }}</h3>
                                <p class="chat-meta">
                                    {{ selectedChat?.telegram_username ? '@' + selectedChat.telegram_username :
                                        selectedChat?.telegram_phone }}
                                </p>
                            </div>
                        </div>

                        <button @click="markAsRead" class="btn-mark-read" v-if="hasUnread">
                            <i class="fas fa-check-double"></i>
                            Mark as Read
                        </button>
                    </div>

                    <!-- Messages Area -->
                    <div class="messages-area" ref="messagesContainer">
                        <div v-if="loadingMessages" class="loading-state">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Loading messages...</p>
                        </div>

                        <div v-else-if="messages.length === 0" class="empty-messages">
                            <i class="fas fa-comment-slash"></i>
                            <p>No messages yet</p>
                        </div>

                        <div v-else class="messages-list">
                            <MessageItem v-for="message in messages" :key="message.id" :message="message" />
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="message-input-area">
                        <form @submit.prevent="sendMessage" class="message-form">
                            <textarea v-model="messageText" placeholder="Type your message..." rows="1"
                                @keydown.enter.exact.prevent="sendMessage" @input="autoResize"
                                ref="messageInput"></textarea>
                            <button type="submit" class="btn-send" :disabled="!messageText.trim() || sending">
                                <i class="fas" :class="sending ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import apiClient from '@/vue/services/api';
import ChatListItem from '@/vue/components/telegram/ChatListItem.vue';
import MessageItem from '@/vue/components/telegram/MessageItem.vue';

const route = useRoute();
const router = useRouter();

const chats = ref([]);
const messages = ref([]);
const selectedChatId = ref(null);
const searchQuery = ref('');
const messageText = ref('');
const loadingChats = ref(false);
const loadingMessages = ref(false);
const sending = ref(false);
const messagesContainer = ref(null);
const messageInput = ref(null);

const selectedChat = computed(() => {
    return chats.value.find(chat => chat.id === selectedChatId.value);
});

const hasUnread = computed(() => {
    return selectedChat.value?.unread_count > 0;
});

const getInitials = (name) => {
    if (!name) return '';
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
};

const loadChats = async () => {
    loadingChats.value = true;
    try {
        const response = await apiClient.get('/admin/telegram/chats', {
            params: {
                search: searchQuery.value,
                per_page: 50
            }
        });
        chats.value = response.data.data.data;
    } catch (error) {
        console.error('Error loading chats:', error);
    } finally {
        loadingChats.value = false;
    }
};

const loadMessages = async (chatId) => {
    if (!chatId) return;

    loadingMessages.value = true;
    try {
        const response = await apiClient.get(`/admin/telegram/chats/${chatId}`);
        messages.value = response.data.data.messages.data;
        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Error loading messages:', error);
    } finally {
        loadingMessages.value = false;
    }
};

const selectChat = (chatId) => {
    selectedChatId.value = chatId;
    loadMessages(chatId);
    router.push({ query: { chat: chatId } });
};

const sendMessage = async () => {
    if (!messageText.value.trim() || !selectedChatId.value || sending.value) return;

    sending.value = true;
    const text = messageText.value;
    messageText.value = '';

    try {
        const response = await apiClient.post(
            `/admin/telegram/chats/${selectedChatId.value}/send`,
            { message: text }
        );

        messages.value.push(response.data.data);
        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Error sending message:', error);
        messageText.value = text; // Restore message on error
        alert('Failed to send message. Please try again.');
    } finally {
        sending.value = false;
    }
};

const markAsRead = async () => {
    if (!selectedChatId.value) return;

    try {
        await apiClient.post(`/admin/telegram/chats/${selectedChatId.value}/read`);

        // Update local chat unread count
        const chat = chats.value.find(c => c.id === selectedChatId.value);
        if (chat) {
            chat.unread_count = 0;
        }

        // Mark messages as read
        messages.value.forEach(msg => {
            if (msg.sender_type === 'user') {
                msg.is_read = true;
            }
        });
    } catch (error) {
        console.error('Error marking as read:', error);
    }
};

const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

const autoResize = (event) => {
    event.target.style.height = 'auto';
    event.target.style.height = Math.min(event.target.scrollHeight, 120) + 'px';
};

let searchTimeout;
const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadChats();
    }, 300);
};

// Watch for route query changes
watch(() => route.query.chat, (chatId) => {
    if (chatId) {
        selectedChatId.value = parseInt(chatId);
        loadMessages(selectedChatId.value);
    }
});

const setupListeners = () => {
    if (!window.Echo) return;

    // Listen for new messages in the general list (to update unread counts and sorting)
    window.Echo.private('telegram.chats.list')
        .listen('TelegramMessageReceived', (e) => {
            const chatIndex = chats.value.findIndex(c => c.id === e.telegram_chat_id);
            if (chatIndex !== -1) {
                // Update existing chat
                const chat = chats.value[chatIndex];
                chat.last_message_at = e.sent_at;
                if (selectedChatId.value !== e.telegram_chat_id) {
                    chat.unread_count = (chat.unread_count || 0) + 1;
                }

                // Move to top
                chats.value.splice(chatIndex, 1);
                chats.value.unshift(chat);
            } else {
                // New chat session
                chats.value.unshift(e.chat);
            }
        });

    // We'll also listen on the specific chat channel if one is selected
    watch(selectedChatId, (newId, oldId) => {
        if (oldId) {
            window.Echo.leave(`telegram.chat.${oldId}`);
        }
        if (newId) {
            window.Echo.private(`telegram.chat.${newId}`)
                .listen('TelegramMessageReceived', (e) => {
                    messages.value.push(e);
                    nextTick(() => scrollToBottom());
                })
                .listen('TelegramMessageSent', (e) => {
                    // This handles messages sent from other admin sessions
                    const exists = messages.value.find(m => m.id === e.id);
                    if (!exists) {
                        messages.value.push(e);
                        nextTick(() => scrollToBottom());
                    }
                });
        }
    }, { immediate: true });
};

onMounted(() => {
    loadChats();
    setupListeners();

    // Load chat from query if exists
    if (route.query.chat) {
        selectedChatId.value = parseInt(route.query.chat);
        loadMessages(selectedChatId.value);
    }
});
</script>

<style scoped>
.telegram-chats-page {
    padding: 24px;
    height: calc(100vh - 100px);
}

.chats-container {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 24px;
    height: 100%;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.chat-list-panel {
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.chat-list-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.chat-list-header h2 {
    margin: 0 0 16px 0;
    font-size: 20px;
    color: #111827;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.search-box input {
    width: 100%;
    padding: 10px 10px 10px 36px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.search-box input:focus {
    outline: none;
    border-color: #3b82f6;
}

.chat-list-body {
    flex: 1;
    overflow-y: auto;
}

.chat-window-panel {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.no-chat-selected {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.no-chat-selected i {
    font-size: 64px;
    margin-bottom: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.no-chat-selected h3 {
    margin: 0 0 8px 0;
    color: #4b5563;
}

.no-chat-selected p {
    margin: 0;
    font-size: 14px;
}

.chat-window {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.chat-window-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-header-info .avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
}

.chat-header-info h3 {
    margin: 0;
    font-size: 16px;
    color: #111827;
}

.chat-meta {
    margin: 4px 0 0 0;
    font-size: 13px;
    color: #6b7280;
}

.btn-mark-read {
    padding: 8px 16px;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-mark-read:hover {
    background-color: #2563eb;
}

.messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background-color: #f9fafb;
}

.empty-state,
.empty-messages,
.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
}

.empty-state i,
.empty-messages i,
.loading-state i {
    font-size: 48px;
    margin-bottom: 12px;
}

.messages-list {
    display: flex;
    flex-direction: column;
}

.message-input-area {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    background-color: white;
}

.message-form {
    display: flex;
    gap: 12px;
    align-items: flex-end;
}

.message-form textarea {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    font-size: 14px;
    resize: none;
    font-family: inherit;
    transition: border-color 0.2s;
    max-height: 120px;
}

.message-form textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.btn-send {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    cursor: pointer;
    transition: transform 0.2s;
    flex-shrink: 0;
}

.btn-send:not(:disabled):hover {
    transform: scale(1.05);
}

.btn-send:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
