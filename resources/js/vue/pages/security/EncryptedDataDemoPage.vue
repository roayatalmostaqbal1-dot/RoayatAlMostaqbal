<template>
    <DashboardLayout page-title="Encrypted Data Demo"
        page-description="View raw encrypted data exactly as stored in the database (Client-Side Encryption Proof)">

        <div v-if="isLoading" class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#27e9b5]"></div>
                <p class="text-gray-400 mt-4">Loading encrypted data...</p>
            </div>
        </div>

        <div v-else-if="error" class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-red-400">
            {{ error }}
        </div>

        <div v-else class="space-y-6">
            <div class="bg-[#1a2332] border border-[#3b5265] rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                        </svg>
                        Database Storage View
                    </h3>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-semibold">
                        Encrypted At Rest
                    </span>
                </div>

                <p class="text-gray-400 mb-6 text-sm">
                    This page demonstrates that sensitive user data is stored in the database in a fully encrypted
                    format.
                    The server does not verify the content as it does not have the DEK (Data Encryption Key).
                    Decryption only happens on the client-side using the user's password.
                </p>

                <div v-if="encryptedData" class="space-y-6">
                    <!-- DEK Section -->
                    <div class="space-y-2">
                        <h4 class="text-white font-semibold text-sm uppercase tracking-wider">Data Encryption Key (DEK)
                            wrapper</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-[#051824] rounded border border-[#3b5265]">
                                <label class="block text-gray-500 text-xs mb-1">Encrypted DEK</label>
                                <code
                                    class="text-[#27e9b5] text-xs break-all leading-relaxed">{{ encryptedData.encrypted_dek }}</code>
                            </div>
                            <div class="p-4 bg-[#051824] rounded border border-[#3b5265]">
                                <label class="block text-gray-500 text-xs mb-1">DEK Salt</label>
                                <code
                                    class="text-yellow-400 text-xs break-all leading-relaxed">{{ encryptedData.dek_salt }}</code>
                            </div>
                            <div class="p-4 bg-[#051824] rounded border border-[#3b5265]">
                                <label class="block text-gray-500 text-xs mb-1">DEK Nonce</label>
                                <code
                                    class="text-purple-400 text-xs break-all leading-relaxed">{{ encryptedData.dek_nonce }}</code>
                            </div>
                        </div>
                    </div>

                    <!-- Payload Section -->
                    <div class="space-y-2">
                        <h4 class="text-white font-semibold text-sm uppercase tracking-wider">Encrypted Payload
                            (Sensitive Data)</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-4 bg-[#051824] rounded border border-[#3b5265]">
                                <label class="block text-gray-500 text-xs mb-1">Profile Ciphertext</label>
                                <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                    <code
                                        class="text-red-400 text-xs break-all leading-relaxed">{{ encryptedData.profile_ciphertext }}</code>
                                </div>
                            </div>
                            <div class="p-4 bg-[#051824] rounded border border-[#3b5265]">
                                <label class="block text-gray-500 text-xs mb-1">Profile Nonce</label>
                                <code
                                    class="text-purple-400 text-xs break-all leading-relaxed">{{ encryptedData.profile_nonce }}</code>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-8 text-gray-400">
                    No encrypted data found for this user.
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import { useSecurityDashboardStore } from '@/vue/stores/securityDashboardStore';

const securityStore = useSecurityDashboardStore();
const isLoading = ref(true);
const encryptedData = ref(null);
const error = ref(null);

onMounted(async () => {
    const result = await securityStore.fetchEncryptedDataRaw();
    isLoading.value = false;

    if (result.success) {
        encryptedData.value = result.data;
    } else {
        error.value = result.error || 'Failed to load encrypted data';
    }
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #051824;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #3b5265;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #4a6580;
}
</style>
