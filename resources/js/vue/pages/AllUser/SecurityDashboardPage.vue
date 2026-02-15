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
                <div class="space-y-4">
                    <IdentityCard :identityData="securityStore.identityData" />

                    <!-- Unlock Button -->
                    <button v-if="isIdentityEncrypted && !isIdentityDecrypted" @click="showDecryptModal = true"
                        class="w-full py-2 bg-[#1a2332] border border-[#27e9b5] text-[#27e9b5] rounded-lg font-semibold hover:bg-[#27e9b5]/10 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM8.9 6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2H8.9V6z" />
                        </svg>
                        Unlock Encrypted Identity
                    </button>

                    <!-- Setup Button -->
                    <button v-if="!isIdentityEncrypted" @click="showSetupModal = true"
                        class="w-full py-2 bg-blue-600/20 border border-blue-500 text-blue-400 rounded-lg font-semibold hover:bg-blue-600/30 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                        </svg>
                        Setup Encrypted Identity
                    </button>

                    <div v-if="isIdentityDecrypted"
                        class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-center">
                        <p class="text-green-400 text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                            Data Decrypted Logic On Client-Side (libsodium)
                        </p>
                    </div>
                </div>
                <SecurityLogsCard :logs="securityStore.securityLogs" :totalCount="securityStore.logsTotal"
                    :currentFilter="currentFilter" @refresh="handleRefreshLogs" @filter="handleFilterChange"
                    @generate="handleGenerateLogs" @export="handleExportLogs" />
            </div>

            <!-- Row 2: AI Analysis (Full Width) -->
            <AIAnalysisCard :aiInsight="securityStore.aiInsight" @generateInsight="handleGenerateInsight" />

            <!-- Row 3: System Metrics -->
            <SystemMetricsCard :metrics="securityStore.systemMetrics" />
        </div>

        <!-- Decryption Modal -->
        <div v-if="showDecryptModal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="bg-[#1a2332] rounded-lg shadow-lg max-w-md w-full mx-4 p-6 border border-[#3b5265]">
                <h3 class="text-xl font-bold text-white mb-4">Unlock Encrypted Data</h3>
                <p class="text-gray-400 mb-6">Your identity data is encrypted. Please enter your password to decrypt and
                    view it.</p>

                <div class="space-y-4">
                    <Input v-model="decryptPassword" type="password" label="Password"
                        placeholder="Enter your login password" :error="decryptError"
                        @keyup.enter="handleDecryptIdentity" />

                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="showDecryptModal = false"
                            class="px-4 py-2 text-gray-400 hover:text-white transition">
                            Cancel
                        </button>
                        <button @click="handleDecryptIdentity" :disabled="isDecrypting"
                            class="bg-[#27e9b5] hover:bg-[#1fd4a0] text-[#051824] px-6 py-2 rounded font-bold transition flex items-center gap-2">
                            <span v-if="isDecrypting"
                                class="animate-spin h-4 w-4 border-2 border-[#051824] border-t-transparent rounded-full"></span>
                            {{ isDecrypting ? 'Decrypting...' : 'Decrypt' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Setup Encryption Modal -->
        <div v-if="showSetupModal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="bg-[#1a2332] rounded-lg shadow-lg max-w-md w-full mx-4 p-6 border border-[#3b5265]">
                <h3 class="text-xl font-bold text-white mb-4">Setup Encrypted Identity</h3>
                <p class="text-gray-400 mb-6">Create a secure, encrypted digital identity. Your data will be encrypted
                    on your device using your password before being stored.</p>

                <div class="space-y-4">
                    <Input v-model="setupPassword" type="password" label="Password"
                        placeholder="Enter encryption password" :error="setupError" />
                    <Input v-model="setupConfirmPassword" type="password" label="Confirm Password"
                        placeholder="Re-enter password" @keyup.enter="handleSetupEncryption" />

                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="showSetupModal = false"
                            class="px-4 py-2 text-gray-400 hover:text-white transition">
                            Cancel
                        </button>
                        <button @click="handleSetupEncryption" :disabled="isSettingUp"
                            class="bg-[#27e9b5] hover:bg-[#1fd4a0] text-[#051824] px-6 py-2 rounded font-bold transition flex items-center gap-2">
                            <span v-if="isSettingUp"
                                class="animate-spin h-4 w-4 border-2 border-[#051824] border-t-transparent rounded-full"></span>
                            {{ isSettingUp ? 'Encrypting...' : 'Setup Encryption' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Toast -->
        <Toast v-if="showToast" :message="toastMessage" :type="toastType" @close="showToast = false" />
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import SecurityBanner from '@/vue/components/security/SecurityBanner.vue';
import IdentityCard from '@/vue/components/security/IdentityCard.vue';
import SecurityLogsCard from '@/vue/components/security/SecurityLogsCard.vue';
import AIAnalysisCard from '@/vue/components/security/AIAnalysisCard.vue';
import SystemMetricsCard from '@/vue/components/security/SystemMetricsCard.vue';
import Toast from '@/vue/components/ui/Toast.vue';
import Input from '@/vue/components/ui/Input.vue';
import { useSecurityDashboardStore } from '@/vue/stores/securityDashboardStore';

const securityStore = useSecurityDashboardStore();

const currentFilter = ref('today');
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

// Decryption state
const showDecryptModal = ref(false);
const showSetupModal = ref(false);
const decryptPassword = ref('');
const decryptError = ref('');
const setupPassword = ref('');
const setupConfirmPassword = ref('');
const setupError = ref('');
const isDecrypting = ref(false);
const isSettingUp = ref(false);
const isIdentityEncrypted = ref(false);
const isIdentityDecrypted = ref(false);

// Fetch all dashboard data on mount
onMounted(async () => {
    await securityStore.fetchAllData();

    // Check if identity data contains encrypted payload
    if (securityStore.identityData?.encrypted_identity) {
        isIdentityEncrypted.value = true;
        // Automatically prompt for decryption if encrypted
        showDecryptModal.value = true;
    }
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

// Handle decryption
const handleDecryptIdentity = async () => {
    if (!decryptPassword.value) {
        decryptError.value = 'Password is required';
        return;
    }

    isDecrypting.value = true;
    decryptError.value = '';

    const encryptedData = securityStore.identityData.encrypted_identity;

    const result = await securityStore.decryptData(
        decryptPassword.value,
        encryptedData.dek_salt,
        encryptedData.encrypted_dek,
        encryptedData.dek_nonce,
        encryptedData.ciphertext,
        encryptedData.nonce
    );

    isDecrypting.value = false;

    if (result.success) {
        // Merge decrypted data into identityData
        // We assume decrypted data contains keys like identity_number, nationality, etc.
        securityStore.identityData = {
            ...securityStore.identityData,
            ...result.data
        };

        isIdentityDecrypted.value = true;
        showDecryptModal.value = false;
        decryptPassword.value = '';
        showSuccessToast('Identity data decrypted successfully');
    } else {
        decryptError.value = result.error || 'Incorrect password or decryption failed';
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
