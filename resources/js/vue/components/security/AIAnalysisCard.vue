<template>
    <Card>
        <template #header>
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M21 10.12h-6.78l2.74-2.82c-2.73-2.7-7.15-2.8-9.88-.1-2.73 2.71-2.73 7.08 0 9.79 2.73 2.71 7.15 2.71 9.88 0C18.32 15.65 19 14.08 19 12.1h2c0 1.98-.88 4.55-2.64 6.29-3.51 3.48-9.21 3.48-12.72 0-3.5-3.47-3.53-9.11-.02-12.58 3.51-3.47 9.14-3.47 12.65 0L21 3v7.12zM12.5 8v4.25l3.5 2.08-.72 1.21L11 13V8h1.5z" />
                </svg>
                <h3 class="text-lg font-bold text-white">AI Security Analysis</h3>
            </div>
            <span :class="[
                'px-3 py-1 rounded-full text-xs font-semibold',
                aiInsight ? 'bg-[#27e9b5]/20 text-[#27e9b5]' : 'bg-gray-500/20 text-gray-400'
            ]">
                {{ aiInsight ? 'Active' : 'Inactive' }}
            </span>
        </template>

        <div v-if="aiInsight" class="space-y-6">
            <!-- Risk Level -->
            <div>
                <h4 class="text-white font-semibold mb-3">Current Risk Level:</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span :class="getRiskLevelClass(aiInsight.risk_level)" class="text-2xl font-bold capitalize">
                            {{ aiInsight.risk_level }}
                        </span>
                        <span class="text-gray-400 text-sm">{{ aiInsight.risk_score }}/100</span>
                    </div>

                    <!-- Risk Bar -->
                    <div class="relative h-3 bg-gray-700 rounded-full overflow-hidden">
                        <div :class="getRiskBarClass(aiInsight.risk_level)"
                            class="h-full transition-all duration-500 ease-out"
                            :style="{ width: `${aiInsight.risk_percentage}%` }"></div>
                    </div>

                    <!-- Risk Labels -->
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Low</span>
                        <span>Medium</span>
                        <span>High</span>
                        <span>Critical</span>
                    </div>
                </div>
            </div>

            <!-- Recommendation -->
            <div>
                <h4 class="text-white font-semibold mb-3">Security Recommendation:</h4>
                <div class="flex items-start gap-3 p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                    <svg class="w-5 h-5 text-yellow-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7zm2.85 11.1l-.85.6V16h-4v-2.3l-.85-.6C7.8 12.16 7 10.63 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.63-.8 3.16-2.15 4.1z" />
                    </svg>
                    <p class="text-gray-300 text-sm leading-relaxed">{{ aiInsight.recommendation }}</p>
                </div>
            </div>

            <!-- Threat Indicators -->
            <div>
                <h4 class="text-white font-semibold mb-3">Threat Indicators:</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-4 bg-[#1f3a4a] rounded-lg border border-green-500/30">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                            <span class="text-gray-400 text-sm">Login Attempts</span>
                        </div>
                        <p class="text-2xl font-bold text-white">{{ aiInsight.threat_indicators?.login_attempts || 0 }}
                        </p>
                    </div>

                    <div class="p-4 bg-[#1f3a4a] rounded-lg border border-yellow-500/30">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                            </svg>
                            <span class="text-gray-400 text-sm">Alerts</span>
                        </div>
                        <p class="text-2xl font-bold text-white">{{ aiInsight.threat_indicators?.alerts || 0 }}</p>
                    </div>

                    <div class="p-4 bg-[#1f3a4a] rounded-lg border border-orange-500/30">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            <span class="text-gray-400 text-sm">Access Attempts</span>
                        </div>
                        <p class="text-2xl font-bold text-white">{{ aiInsight.threat_indicators?.access_attempts || 0 }}
                        </p>
                    </div>

                    <div class="p-4 bg-[#1f3a4a] rounded-lg border border-red-500/30">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                            </svg>
                            <span class="text-gray-400 text-sm">Critical Threats</span>
                        </div>
                        <p class="text-2xl font-bold text-white">{{ aiInsight.threat_indicators?.critical_threats || 0
                            }}</p>
                    </div>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="pt-4 border-t border-[#3b5265]">
                <Button variant="primary" class="w-full" @click="$emit('generateInsight')">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M7 14c-1.66 0-3 1.34-3 3 0 1.31-1.16 2-2 2 .92 1.22 2.49 2 4 2 2.21 0 4-1.79 4-4 0-1.66-1.34-3-3-3zm13.71-9.37l-1.34-1.34c-.39-.39-1.02-.39-1.41 0L9 12.25 11.75 15l8.96-8.96c.39-.39.39-1.02 0-1.41z" />
                    </svg>
                    Generate New AI Insight
                </Button>
                <p class="text-gray-500 text-xs mt-2 text-center">
                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                    </svg>
                    AI analyzes local data only - Generated {{ aiInsight.generated_at }}
                </p>
            </div>
        </div>

        <div v-else class="text-center py-8">
            <p class="text-gray-400">Loading AI analysis...</p>
        </div>
    </Card>
</template>

<script setup>
import Card from '@/vue/components/ui/Card.vue';
import Button from '@/vue/components/ui/Button.vue';

defineProps({
    aiInsight: {
        type: Object,
        default: null,
    },
});

defineEmits(['generateInsight']);

const getRiskLevelClass = (level) => {
    const classes = {
        low: 'text-green-400',
        medium: 'text-yellow-400',
        high: 'text-orange-400',
        critical: 'text-red-400',
    };
    return classes[level] || 'text-gray-400';
};

const getRiskBarClass = (level) => {
    const classes = {
        low: 'bg-green-500',
        medium: 'bg-yellow-500',
        high: 'bg-orange-500',
        critical: 'bg-red-500',
    };
    return classes[level] || 'bg-gray-500';
};
</script>
