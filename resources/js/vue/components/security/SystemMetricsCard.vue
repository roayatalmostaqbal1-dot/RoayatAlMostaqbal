<template>
    <Card>
        <template #header>
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M20 3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h3l-1 1v2h12v-2l-1-1h3c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 13H4V5h16v11z" />
                </svg>
                <h3 class="text-lg font-bold text-white">System Metrics</h3>
            </div>
        </template>

        <div v-if="metrics" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- System Status -->
            <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-gray-400 text-sm">System Status</h4>
                        <p class="text-white font-bold text-lg capitalize">{{ metrics.system_status }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-green-400 text-sm">100% Operational</span>
                </div>
            </div>

            <!-- Response Time -->
            <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-gray-400 text-sm">Response Time</h4>
                        <p class="text-white font-bold text-lg">{{ metrics.response_time }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <div class="flex-1 h-2 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 w-3/4"></div>
                    </div>
                </div>
            </div>

            <!-- Protection Status -->
            <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-gray-400 text-sm">Active Protection</h4>
                        <p class="text-white font-bold text-lg capitalize">{{ metrics.protection_status }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <svg class="w-4 h-4 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                    </svg>
                    <span class="text-[#27e9b5] text-sm">All Shields Active</span>
                </div>
            </div>

            <!-- Data Processed -->
            <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-gray-400 text-sm">Data Processed</h4>
                        <p class="text-white font-bold text-lg">{{ metrics.data_processed }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <svg class="w-4 h-4 text-purple-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                    </svg>
                    <span class="text-purple-400 text-sm">Processing...</span>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-8">
            <p class="text-gray-400">Loading system metrics...</p>
        </div>
    </Card>
</template>

<script setup>
import Card from '@/vue/components/ui/Card.vue';

defineProps({
    metrics: {
        type: Object,
        default: null,
    },
});
</script>
