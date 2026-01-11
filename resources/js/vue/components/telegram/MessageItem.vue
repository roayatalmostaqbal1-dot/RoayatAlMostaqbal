<template>
    <div class="message-item" :class="messageClass">
        <div class="message-avatar" v-if="!isFromAdmin">
            <div class="avatar-circle">
                {{ getInitials(message.chat?.first_name || 'U') }}
            </div>
        </div>

        <div class="message-content">
            <div class="message-header" v-if="isFromAdmin">
                <span class="sender-name">{{ message.admin?.name || 'Admin' }}</span>
            </div>

            <div class="message-bubble">
                <p class="message-text">{{ message.message_text }}</p>
                <div class="message-footer">
                    <span class="message-time">{{ formatTime(message.sent_at) }}</span>
                    <span v-if="isFromAdmin" class="message-status">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="message-avatar" v-if="isFromAdmin">
            <div class="avatar-circle admin-avatar">
                {{ getInitials(message.admin?.name || 'A') }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { format } from 'date-fns';

const props = defineProps({
    message: {
        type: Object,
        required: true
    }
});

const isFromAdmin = computed(() => props.message.sender_type === 'admin');

const messageClass = computed(() => ({
    'from-admin': isFromAdmin.value,
    'from-user': !isFromAdmin.value
}));

const getInitials = (name) => {
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
};

const formatTime = (timestamp) => {
    if (!timestamp) return '';
    const date = new Date(timestamp);
    return format(date, 'HH:mm');
};
</script>

<style scoped>
.message-item {
    display: flex;
    margin-bottom: 16px;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-item.from-admin {
    justify-content: flex-end;
}

.message-item.from-user {
    justify-content: flex-start;
}

.message-avatar {
    margin: 0 8px;
}

.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 12px;
}

.avatar-circle.admin-avatar {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.message-content {
    max-width: 70%;
    min-width: 100px;
}

.message-header {
    margin-bottom: 4px;
    padding: 0 8px;
}

.sender-name {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.message-bubble {
    padding: 10px 14px;
    border-radius: 18px;
    position: relative;
}

.from-admin .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.from-user .message-bubble {
    background-color: #f3f4f6;
    color: #111827;
    border-bottom-left-radius: 4px;
}

.message-text {
    margin: 0 0 4px 0;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
}

.message-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
}

.message-time {
    font-size: 11px;
    opacity: 0.7;
}

.from-user .message-time {
    color: #6b7280;
    opacity: 1;
}

.message-status {
    font-size: 12px;
    opacity: 0.8;
}
</style>
