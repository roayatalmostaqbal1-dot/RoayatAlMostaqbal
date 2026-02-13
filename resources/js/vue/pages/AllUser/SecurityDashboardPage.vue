<template>
    <DashboardLayout page-title="Security Dashboard"
        page-description="Real-time security monitoring and AI-powered threat analysis">
        <!-- Banner -->
        <SecurityBanner class="mb-6" />

        <!-- Loading State -->
        <div v-if="securityStore.isLoading && !securityStore.identityData"
            class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#27e9b5]"></div>
                <p class="text-gray-400 mt-4">Loading dashboard data...</p>
            </div>
        </div>

        <!-- Error State -->
        <div v-else-if="securityStore.error" class="mb-6">
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                <div class="flex-1">
                    <h4 class="text-red-400 font-semibold mb-1">Error Loading Dashboard</h4>
                    <p class="text-gray-300 text-sm">{{ securityStore.error }}</p>
                </div>
                <button @click="securityStore.clearError()" class="text-red-400 hover:text-red-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div v-else class="space-y-6">
            <!-- Row 1: Identity and Security Logs -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <IdentityCard :identityData="securityStore.identityData" />
                <SecurityLogsCard :logs="securityStore.securityLogs" :totalCount="securityStore.logsTotal"
                    :currentFilter="currentFilter" @refresh="handleRefreshLogs" @filter="handleFilterChange"
                    @generate="handleGenerateLogs" @export="handleExportLogs" />
            </div>

            <!-- Row 2: AI Analysis (Full Width) -->
            <AIAnalysisCard :aiInsight="securityStore.aiInsight" @generateInsight="handleGenerateInsight" />

            <!-- Row 3: System Metrics -->
            <SystemMetricsCard :metrics="securityStore.systemMetrics" />
        </div>

        <!-- Success Toast -->
        <Toast v-if="showToast" :message="toastMessage" :type="toastType" @close="showToast = false" />
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import SecurityBanner from '@/vue/components/security/SecurityBanner.vue';
import IdentityCard from '@/vue/components/security/IdentityCard.vue';
import SecurityLogsCard from '@/vue/components/security/SecurityLogsCard.vue';
import AIAnalysisCard from '@/vue/components/security/AIAnalysisCard.vue';
import SystemMetricsCard from '@/vue/components/security/SystemMetricsCard.vue';
import Toast from '@/vue/components/ui/Toast.vue';
import { useSecurityDashboardStore } from '@/vue/stores/securityDashboardStore';

const securityStore = useSecurityDashboardStore();

const currentFilter = ref('today');
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

// Fetch all dashboard data on mount
onMounted(async () => {
    await securityStore.fetchAllData();
});

// Handle refresh logs
const handleRefreshLogs = async () => {
    await securityStore.fetchSecurityLogs({ period: currentFilter.value });
    showSuccessToast('Security logs refreshed');
};

// Handle filter change
const handleFilterChange = async (period) => {
    currentFilter.value = period;
    await securityStore.fetchSecurityLogs({ period });
};

// Handle generate logs
const handleGenerateLogs = async () => {
    const result = await securityStore.generateSecurityLogs(10);
    if (result.success) {
        showSuccessToast('Generated 10 security logs successfully');
    } else {
        showErrorToast(result.error || 'Failed to generate logs');
    }
};

// Handle export logs
const handleExportLogs = async () => {
    const result = await securityStore.exportLogs(currentFilter.value);
    if (result.success) {
        showSuccessToast('Logs exported successfully');
    } else {
        showErrorToast(result.error || 'Failed to export logs');
    }
};

// Handle generate AI insight
const handleGenerateInsight = async () => {
    const result = await securityStore.generateAIInsight();
    if (result.success) {
        showSuccessToast('AI insight generated successfully');
    } else {
        showErrorToast(result.error || 'Failed to generate AI insight');
    }
};

// Toast helpers
const showSuccessToast = (message) => {
    toastMessage.value = message;
    toastType.value = 'success';
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

const showErrorToast = (message) => {
    toastMessage.value = message;
    toastType.value = 'error';
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};
</script>
