<template>
    <div class="relative" v-click-outside="closeMenu">
        <button @click="toggleMenu"
            class="relative text-gray-400 hover:text-white transition-colors p-2 rounded-full hover:bg-[#1f3647]">
            <component :is="IconBell" class="w-6 h-6" />
            <span v-if="notificationStore.unreadCount > 0"
                class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-[#162936]">
                {{ notificationStore.unreadCount > 99 ? '99+' : notificationStore.unreadCount }}
            </span>
        </button>

        <!-- Dropdown Menu -->
        <div v-if="isOpen"
            class="absolute right-0 mt-3 w-80 md:w-96 bg-[#162936] border border-[#3b5265] rounded-xl shadow-2xl z-50 overflow-hidden transform origin-top-right transition-all">

            <div class="p-4 border-b border-[#3b5265] flex items-center justify-between bg-[#051824]">
                <h3 class="text-white font-bold">Notifications</h3>
                <button @click="markAllAsRead" v-if="notificationStore.unreadCount > 0"
                    class="text-xs text-[#27e9b5] hover:underline">
                    Mark all as read
                </button>
            </div>

            <!-- Filters -->
            <div class="flex border-b border-[#3b5265] bg-[#051824]/50">
                <button v-for="f in ['all', 'unread', 'read']" :key="f" @click="setFilter(f)" :class="[
                    'flex-1 py-2 text-xs font-semibold capitalize transition-colors',
                    notificationStore.filter === f ? 'text-[#27e9b5] border-b-2 border-[#27e9b5]' : 'text-gray-400 hover:text-white'
                ]">
                    {{ f }}
                </button>
            </div>

            <!-- List -->
            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                <div v-if="notificationStore.loading && notificationStore.notifications.length === 0"
                    class="p-8 flex flex-col items-center justify-center text-gray-500">
                    <component :is="IconSpinner" class="w-8 h-8 animate-spin mb-2" />
                    <p>Loading...</p>
                </div>

                <div v-else-if="notificationStore.notifications.length === 0"
                    class="p-8 flex flex-col items-center justify-center text-center text-gray-500">
                    <component :is="IconBellSlash" class="w-10 h-10 mb-2 opacity-20" />
                    <p>No notifications found</p>
                </div>

                <div v-else class="divide-y divide-[#3b5265]">
                    <div v-for="notification in notificationStore.notifications" :key="notification.id"
                        @click="handleNotificationClick(notification)" :class="[
                            'p-4 hover:bg-[#1f3647] cursor-pointer transition-colors relative',
                            !notification.read_at ? 'bg-[#27e9b5]/5' : ''
                        ]">
                        <div class="flex gap-3">
                            <div class="shrink-0">
                                <img v-if="notification.data.photo_url" :src="notification.data.photo_url"
                                    class="w-10 h-10 rounded-full border border-[#3b5265]" />
                                <div v-else
                                    class="w-10 h-10 rounded-full bg-[#27e9b5] flex items-center justify-center text-[#051824] font-bold">
                                    {{ getInitials(notification.data.sender_name) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">
                                    {{ notification.data.sender_name }}
                                </p>
                                <p class="text-xs text-gray-400 line-clamp-2 mt-0.5">
                                    {{ notification.data.text }}
                                </p>
                                <p class="text-[10px] text-gray-500 mt-1">
                                    {{ formatTime(notification.created_at) }}
                                </p>
                            </div>
                            <div v-if="!notification.read_at" class="w-2 h-2 bg-[#27e9b5] rounded-full self-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer / Pagination -->
            <div v-if="notificationStore.lastPage > 1"
                class="p-3 border-t border-[#3b5265] bg-[#051824] flex items-center justify-between">
                <button @click="notificationStore.fetchNotifications(notificationStore.currentPage - 1)"
                    :disabled="notificationStore.currentPage === 1 || notificationStore.loading"
                    class="p-1 text-gray-400 hover:text-white disabled:opacity-20">
                    <component :is="IconChevronLeft" class="w-5 h-5" />
                </button>
                <span class="text-[10px] text-gray-500">
                    Page {{ notificationStore.currentPage }} of {{ notificationStore.lastPage }}
                </span>
                <button @click="notificationStore.fetchNotifications(notificationStore.currentPage + 1)"
                    :disabled="notificationStore.currentPage === notificationStore.lastPage || notificationStore.loading"
                    class="p-1 text-gray-400 hover:text-white disabled:opacity-20">
                    <component :is="IconChevronRight" class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, h } from 'vue';
import { useNotificationStore } from '@/vue/stores/notificationStore';
import { useRouter } from 'vue-router';


// Directives
const vClickOutside = {
    mounted(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        document.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
    },
};

// Icons
const IconBell = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' })
    ])
};

const IconBellSlash = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' })
    ])
};

const IconSpinner = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' })
    ])
};

const IconChevronLeft = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15 19l-7-7 7-7' })
    ])
};

const IconChevronRight = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9 5l7 7-7 7' })
    ])
};

const notificationStore = useNotificationStore();
const router = useRouter();
const isOpen = ref(false);

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        notificationStore.fetchNotifications();
    }
};

const closeMenu = () => {
    isOpen.value = false;
};

const setFilter = (filter) => {
    notificationStore.setFilter(filter);
};

const markAllAsRead = () => {
    notificationStore.markAllAsRead();
};

const handleNotificationClick = (notification) => {
    if (!notification.read_at) {
        notificationStore.markAsRead(notification.id);
    }

    if (notification.data.type === 'telegram_message') {
        router.push({ name: 'TelegramChats', query: { chat: notification.data.chat_id } });
        closeMenu();
    }
};

const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};

const formatTime = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;

    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
    if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
    return date.toLocaleDateString();
};

const setupRealtime = () => {
    if (!window.Echo) return;

    const userId = JSON.parse(localStorage.getItem('user'))?.id;
    if (!userId) return;

    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            notificationStore.addNotification({
                id: notification.id,
                data: notification,
                created_at: new Date().toISOString(),
                read_at: null
            });
        });
};

onMounted(() => {
    notificationStore.fetchNotifications();
    setupRealtime();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
