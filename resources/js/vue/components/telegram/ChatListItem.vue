<template>
    <div @click="$emit('select', chat.id)"
        class="flex gap-4 p-4 cursor-pointer transition-all border-l-4 border-transparent hover:bg-[#051824]" :class="[
            isActive ? 'bg-[#051824] border-l-[#27e9b5]' : 'border-l-transparent',
            chat.unread_count > 0 ? 'bg-[#27e9b5]/5' : ''
        ]">
        <!-- Avatar Section -->
        <div class="relative shrink-0">
            <div
                class="w-12 h-12 rounded-full bg-[#051824] border border-[#3b5265] flex items-center justify-center text-[#27e9b5] font-extrabold text-sm shadow-inner">
                {{ getInitials(chat.full_name) }}
            </div>
            <div v-if="chat.unread_count > 0"
                class="absolute -top-1 -right-1 w-5 h-5 bg-[#27e9b5] text-[#051824] rounded-full flex items-center justify-center text-[10px] font-bold border-2 border-[#162936]">
                {{ chat.unread_count }}
            </div>
        </div>

        <!-- Chat Info -->
        <div class="flex-1 min-w-0 flex flex-col justify-center">
            <div class="flex items-center justify-between mb-0.5">
                <h4 class="text-sm font-semibold text-white truncate">{{ chat.full_name }}</h4>
                <span v-if="chat.last_message_at" class="text-[10px] text-gray-400 whitespace-nowrap ml-2">
                    {{ formatTime(chat.last_message_at) }}
                </span>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 truncate">
                    {{ chat.telegram_username ? '@' + chat.telegram_username : chat.telegram_phone }}
                </p>
                <!-- Indicator for unread if badge is not enough -->
                <div v-if="chat.unread_count > 0" class="w-2 h-2 rounded-full bg-[#27e9b5] animate-pulse"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { format, isToday, isYesterday } from 'date-fns';

const props = defineProps({
    chat: {
        type: Object,
        required: true
    },
    isActive: {
        type: Boolean,
        default: false
    }
});

defineEmits(['select']);

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

const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    if (isToday(date)) {
        return format(date, 'HH:mm');
    } else if (isYesterday(date)) {
        return 'Yesterday';
    } else {
        return format(date, 'dd/MM/yyyy');
    }
};
</script>

<style scoped>
/* No additional styles needed - all handled by Tailwind */
</style>
