<template>
    <DashboardLayout pageTitle="Telegram Chats" pageDescription="Manage and respond to Telegram messages in real-time">
        <div class="flex flex-col md:flex-row h-[calc(100vh-200px)] gap-6 overflow-hidden">
            <!-- Chat List Sidebar -->
            <div
                class="w-full md:w-80 lg:w-96 flex flex-col bg-[#162936] rounded-xl border border-[#3b5265] overflow-hidden">
                <div class="p-4 border-b border-[#3b5265]">
                    <div class="relative">
                        <component :is="IconSearch"
                            class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input type="text" v-model="searchQuery" placeholder="Search chats..."
                            class="w-full bg-[#051824] border border-[#3b5265] text-white rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-[#27e9b5] focus:border-transparent transition-all"
                            @input="debouncedSearch" />
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar" v-if="!telegramStore.loadingChats">
                    <div v-if="telegramStore.chats.length === 0"
                        class="flex flex-col items-center justify-center h-48 text-gray-500">
                        <component :is="IconComments" class="w-10 h-10 mb-2" />
                        <p>No chats found</p>
                    </div>

                    <ChatListItem v-for="chat in telegramStore.chats" :key="chat.id" :chat="chat"
                        :isActive="telegramStore.selectedChatId === chat.id" @select="selectChat" />
                </div>

                <div v-else class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <component :is="IconSpinner" class="w-8 h-8 mb-2" />
                    <p>Loading chats...</p>
                </div>
            </div>

            <!-- Chat Window -->
            <div class="flex-1 flex flex-col bg-[#162936] rounded-xl border border-[#3b5265] overflow-hidden relative">
                <div v-if="!telegramStore.selectedChatId"
                    class="flex-1 flex flex-col items-center justify-center text-center p-8">
                    <div class="w-20 h-20 bg-[#051824] rounded-full flex items-center justify-center mb-6">
                        <component :is="IconTelegram" class="w-12 h-12 text-[#27e9b5]" />
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Select a conversation</h3>
                    <p class="text-gray-400 max-w-xs">Choose a chat from the sidebar to view current messages and
                        respond.</p>
                </div>

                <template v-else>
                    <!-- Chat Header -->
                    <div class="px-6 py-4 border-b border-[#3b5265] bg-[#051824] flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img v-if="telegramStore.selectedChat?.photo_url"
                                :src="telegramStore.selectedChat.photo_url"
                                class="w-10 h-10 rounded-full border border-[#3b5265]" />
                            <div v-else
                                class="w-10 h-10 rounded-full bg-[#27e9b5] flex items-center justify-center text-[#051824] font-bold">
                                {{ getInitials(telegramStore.selectedChat?.full_name || '') }}
                            </div>
                            <div>
                                <h3 class="text-white font-semibold leading-tight">{{
                                    telegramStore.selectedChat?.full_name }}</h3>
                                <p class="text-xs text-gray-400">
                                    {{ telegramStore.selectedChat?.telegram_username ? '@' +
                                        telegramStore.selectedChat.telegram_username :
                                        telegramStore.selectedChat?.telegram_phone }}
                                </p>
                            </div>
                        </div>

                        <button @click="markAsRead"
                            class="text-xs bg-[#162936] text-gray-300 px-3 py-1.5 rounded-lg border border-[#3b5265] hover:bg-[#27e9b5] hover:text-[#051824] transition-all flex items-center gap-2"
                            v-if="telegramStore.hasUnread">
                            <component :is="IconCheckDouble" class="w-4 h-4" />
                            Mark as Read
                        </button>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-6 bg-[#051824]/30 custom-scrollbar" ref="messagesContainer">
                        <div v-if="telegramStore.loadingMessages"
                            class="flex flex-col items-center justify-center h-full text-gray-400">
                            <component :is="IconSpinner" class="w-8 h-8 mb-2" />
                            <p>Loading history...</p>
                        </div>

                        <div v-else-if="telegramStore.messages.length === 0"
                            class="flex flex-col items-center justify-center h-full text-gray-500">
                            <component :is="IconCommentSlash" class="w-10 h-10 mb-2" />
                            <p>No messages in this chat</p>
                        </div>

                        <div v-else class="space-y-1">
                            <MessageItem v-for="message in telegramStore.messages" :key="message.id"
                                :message="message" />
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-[#3b5265] bg-[#051824]">
                        <form @submit.prevent="sendMessage" class="flex items-end gap-3">
                            <div class="flex-1 relative">
                                <textarea v-model="messageText" placeholder="Type a message..." rows="1"
                                    class="w-full bg-[#162936] border border-[#3b5265] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#27e9b5] focus:border-transparent transition-all resize-none min-h-[50px] max-h-[150px] custom-scrollbar"
                                    @keydown.enter.exact.prevent="sendMessage" @input="autoResize"
                                    ref="messageInput"></textarea>
                            </div>
                            <button type="submit"
                                class="w-12 h-12 rounded-xl bg-[#27e9b5] text-[#051824] flex items-center justify-center hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100"
                                :disabled="!messageText.trim() || telegramStore.sending">
                                <component :is="telegramStore.sending ? IconSpinner : IconPaperPlane"
                                    :class="telegramStore.sending ? 'w-6 h-6 animate-spin' : 'w-6 h-6'" />
                            </button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick, h } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import apiClient from '@/vue/services/api';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import ChatListItem from '@/vue/components/telegram/ChatListItem.vue';
import MessageItem from '@/vue/components/telegram/MessageItem.vue';

// SVG Icons using render functions (Project Style)
const IconSearch = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' })
    ])
};

const IconComments = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' })
    ])
};

const IconSpinner = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2', class: 'animate-spin' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' })
    ])
};

const IconTelegram = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.93 1.23-5.46 3.62-.51.35-.98.52-1.4.51-.46-.01-1.35-.26-2.01-.48-.81-.27-1.45-.42-1.39-.88.03-.24.36-.49.98-.75 3.84-1.67 6.4-2.77 7.68-3.3 3.66-1.5 4.42-1.76 4.91-1.77.11 0 .35.03.5.15.13.11.17.26.19.38.01.07.01.22.01.26z' })
    ])
};

const IconCheckDouble = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M5 13l4 4L19 7' }),
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M5 13l4 4L19 7' }) // Placeholder for double check if needed, FA style
    ])
};

const IconCommentSlash = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M3 3l18 18M10 4.5V4a1 1 0 011-1h2a1 1 0 011 1v.5M8.5 7h7M7.5 10h9M6.5 13h11M5.5 16h13M4.5 19H20' }) // Custom slash comment style
    ])
};

const IconPaperPlane = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8' })
    ])
};

import { useTelegramStore } from '@/vue/stores/Admin/telegramStore';

const route = useRoute();
const router = useRouter();
const telegramStore = useTelegramStore();

const messageText = ref('');
const messagesContainer = ref(null);
const messageInput = ref(null);

const getInitials = (name) => {
    if (!name || typeof name !== 'string') return '?';
    try {
        return name
            .split(' ')
            .filter(n => n.length > 0)
            .map(n => n[0])
            .join('')
            .toUpperCase()
            .substring(0, 2);
    } catch (e) {
        return name.charAt(0).toUpperCase() || '?';
    }
};

const selectChat = (chatId) => {
    telegramStore.fetchMessages(chatId);
    router.push({ query: { chat: chatId } });
};

const sendMessage = async () => {
    if (!messageText.value.trim() || !telegramStore.selectedChatId || telegramStore.sending) return;

    const text = messageText.value;
    messageText.value = '';

    const result = await telegramStore.sendMessage(telegramStore.selectedChatId, text);

    if (result.success) {
        await nextTick();
        scrollToBottom();
    } else {
        messageText.value = text;
    }
};

const markAsRead = async () => {
    await telegramStore.markAsRead(telegramStore.selectedChatId);
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
        telegramStore.fetchChats(telegramStore.searchQuery);
    }, 300);
};

// Watch for route query changes
watch(() => route.query.chat, (chatId) => {
    if (chatId) {
        telegramStore.fetchMessages(parseInt(chatId));
    }
});

const setupListeners = () => {
    if (!window.Echo) return;

    // Listen for new messages
    window.Echo.private('telegram.chats.list')
        .listen('TelegramMessageReceived', (e) => {
            telegramStore.addReceivedMessage(e);
            if (telegramStore.selectedChatId === e.telegram_chat_id) {
                nextTick(() => scrollToBottom());
            }
        });

    // We'll also listen on the specific chat channel if one is selected
    watch(() => telegramStore.selectedChatId, (newId, oldId) => {
        if (oldId) {
            window.Echo.leave(`telegram.chat.${oldId}`);
        }
        if (newId) {
            window.Echo.private(`telegram.chat.${newId}`)
                .listen('TelegramMessageReceived', (e) => {
                    telegramStore.addReceivedMessage(e);
                    nextTick(() => scrollToBottom());
                })
                .listen('TelegramMessageSent', (e) => {
                    // This handles messages sent from other admin sessions
                    const exists = telegramStore.messages.find(m => m.id === e.id);
                    if (!exists) {
                        telegramStore.messages.push(e);
                        nextTick(() => scrollToBottom());
                    }
                });
        }
    }, { immediate: true });
};

onMounted(() => {
    telegramStore.fetchChats();
    setupListeners();

    // Load chat from query if exists
    if (route.query.chat) {
        telegramStore.fetchMessages(parseInt(route.query.chat));
    }
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #3b5265;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #4a6a8a;
}
</style>
