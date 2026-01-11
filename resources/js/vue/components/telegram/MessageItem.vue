<template>
    <div class="flex mb-4 group animate-in fade-in slide-in-from-bottom-2 duration-300"
        :class="isFromAdmin ? 'justify-end' : 'justify-start'">
        <!-- User Avatar (Left) -->
        <div v-if="!isFromAdmin" class="mr-3 mt-1 shrink-0">
            <img v-if="message.chat?.photo_url" :src="message.chat.photo_url"
                class="w-8 h-8 rounded-full border border-[#3b5265] object-cover" />
            <div v-else
                class="w-8 h-8 rounded-full bg-[#162936] border border-[#3b5265] flex items-center justify-center text-[#27e9b5] text-[10px] font-bold">
                {{ getInitials(message.chat?.first_name || 'U') }}
            </div>
        </div>

        <!-- Message Content -->
        <div class="max-w-[75%] md:max-w-[60%] flex flex-col" :class="isFromAdmin ? 'items-end' : 'items-start'">
            <!-- Admin name if needed -->
            <span v-if="isFromAdmin" class="text-[10px] text-gray-500 mb-1 mr-2 font-medium">
                {{ message.admin?.name || 'Admin' }}
            </span>

            <!-- Message Bubble -->
            <div class="px-4 py-2.5 rounded-2xl shadow-sm relative transition-all overflow-hidden" :class="[
                isFromAdmin
                    ? 'bg-linear-to-br from-[#27e9b5] to-[#1bbd93] text-[#051824] rounded-tr-none'
                    : 'bg-[#051824] text-gray-100 border border-[#3b5265] rounded-tl-none'
            ]">
                <!-- Media Content -->
                <div v-if="message.file_path" class="mb-2 -mx-4 -mt-2.5 overflow-hidden rounded-t-2xl">
                    <img v-if="message.message_type === 'photo' || message.message_type === 'sticker'"
                        :src="message.file_path"
                        class="max-w-full h-auto cursor-pointer hover:opacity-90 transition-opacity"
                        @click="openMedia(message.file_path)" />

                    <video v-else-if="message.message_type === 'video'" :src="message.file_path" controls
                        class="max-w-full h-auto" />

                    <audio v-else-if="message.message_type === 'voice' || message.message_type === 'audio'"
                        :src="message.file_path" controls class="max-w-full h-auto" />

                    <div v-else-if="message.message_type === 'document'"
                        class="p-4 bg-[#162936] flex items-center gap-3 border-b border-[#3b5265]">
                        <component :is="IconFile" class="w-6 h-6 text-[#27e9b5]" />
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-white truncate">Document</p>
                            <a :href="message.file_path" target="_blank"
                                class="text-[10px] text-[#27e9b5] hover:underline">Download</a>
                        </div>
                    </div>
                </div>

                <p v-if="message.message_text && message.message_text !== '[Photo]' && message.message_text !== '[Video]' && message.message_text !== '[Sticker]'"
                    class="text-sm leading-relaxed wrap-break-word whitespace-pre-wrap">{{ message.message_text }}</p>

                <div class="flex items-center justify-end mt-1 gap-1.5 opacity-60">
                    <span class="text-[9px] font-medium">{{ formatTime(message.sent_at) }}</span>
                    <component v-if="isFromAdmin" :is="IconCheckDouble" class="w-3 h-3" />
                </div>
            </div>
        </div>

        <!-- Admin Avatar (Right) -->
        <div v-if="isFromAdmin" class="ml-3 mt-1 shrink-0">
            <div
                class="w-8 h-8 rounded-full bg-[#27e9b5] flex items-center justify-center text-[#051824] text-[10px] font-extrabold shadow-lg">
                {{ getInitials(message.admin?.name || 'A') }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, h } from 'vue';
import { format } from 'date-fns';

const props = defineProps({
    message: {
        type: Object,
        required: true
    }
});

const isFromAdmin = computed(() => props.message.sender_type === 'admin');

// SVG Icons using render functions (Project Style)
const IconCheckDouble = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M5 13l4 4L19 7' }),
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M5 13l4 4L19 7' }) // FA double check style placeholder
    ])
};

const IconFile = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '2' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z' })
    ])
};

const openMedia = (url) => {
    window.open(url, '_blank');
};

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
    if (!timestamp) return '';
    const date = new Date(timestamp);
    return format(date, 'HH:mm');
};
</script>

<style scoped>
/* Tailwind handles the animations and layout */
</style>
