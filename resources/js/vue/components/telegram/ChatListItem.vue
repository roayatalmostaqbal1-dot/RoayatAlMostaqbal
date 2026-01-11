<template>
    <div class="chat-item" :class="{ 'active': isActive, 'unread': chat.unread_count > 0 }"
        @click="$emit('select', chat.id)">
        <div class="chat-avatar">
            <div class="avatar-circle">
                {{ getInitials(chat.full_name) }}
            </div>
        </div>

        <div class="chat-info">
            <div class="chat-header">
                <h4 class="chat-name">{{ chat.full_name }}</h4>
                <span class="chat-time" v-if="chat.last_message_at">
                    {{ formatTime(chat.last_message_at) }}
                </span>
            </div>

            <div class="chat-preview">
                <p class="chat-username">
                    {{ chat.telegram_username ? '@' + chat.telegram_username : chat.telegram_phone }}
                </p>
                <span v-if="chat.unread_count > 0" class="unread-badge">
                    {{ chat.unread_count }}
                </span>
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
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
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
.chat-item {
    display: flex;
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.2s;
}

.chat-item:hover {
    background-color: #f9fafb;
}

.chat-item.active {
    background-color: #eff6ff;
    border-left: 3px solid #3b82f6;
}

.chat-item.unread {
    background-color: #f9fafb;
}

.chat-avatar {
    margin-right: 12px;
}

.avatar-circle {
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

.chat-info {
    flex: 1;
    min-width: 0;
}

.chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.chat-name {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-time {
    font-size: 12px;
    color: #6b7280;
    white-space: nowrap;
}

.chat-preview {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-username {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.unread-badge {
    background-color: #3b82f6;
    color: white;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}
</style>
