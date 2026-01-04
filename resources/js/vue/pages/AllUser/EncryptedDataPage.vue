<template>
    <DashboardLayout page-title="Encrypted Data" page-description="Encrypt and decrypt your sensitive data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar Menu -->
            <div class="lg:col-span-1">
                <Card>
                    <nav class="space-y-2">
                        <button v-for="item in menuItems" :key="item.id" @click="activeTab = item.id" :class="[
                            'w-full text-left px-4 py-3 rounded-lg transition-all duration-300',
                            'text-gray-400 hover:text-white hover:bg-[#1f3a4a]',
                            activeTab === item.id && 'bg-[#27e9b5] text-[#051824] font-semibold',
                        ]">
                            {{ item.label }}
                        </button>
                    </nav>
                </Card>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2">
                <Card>
                    <template #header>
                        <h2 class="text-lg font-bold text-white">{{ activeTabLabel }}</h2>
                    </template>

                    <!-- Error Message -->
                    <div v-if="error"
                        class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
                        {{ error }}
                    </div>

                    <!-- Encrypt Data Tab -->
                    <div v-if="activeTab === 'encrypt'" class="space-y-6">
                        <div>
                            <label class="block text-white font-semibold mb-2">Text to Encrypt</label>
                            <textarea v-model="textToEncrypt" placeholder="Enter the text you want to encrypt..."
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-[#3b5265] bg-[#162936] text-white placeholder-gray-500 focus:outline-none focus:border-[#27e9b5]"
                                rows="6"></textarea>
                            <p class="text-gray-400 text-xs mt-2">Maximum 5000 characters</p>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Password</label>
                            <Input v-model="encryptPassword" type="password" placeholder="Enter a strong password" />
                            <p class="text-gray-400 text-xs mt-2">Use a strong password for better security</p>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Confirm Password</label>
                            <Input v-model="encryptPasswordConfirm" type="password"
                                placeholder="Confirm your password" />
                        </div>

                        <Button variant="primary" @click="handleEncrypt"
                            :disabled="isLoading || !textToEncrypt || !encryptPassword || !encryptPasswordConfirm">
                            {{ isLoading ? 'Encrypting...' : '🔒 Encrypt & Store' }}
                        </Button>

                        <!-- Success Message -->
                        <div v-if="encryptSuccess"
                            class="p-4 rounded-lg bg-green-500 bg-opacity-10 border border-green-500 text-green-400">
                            ✅ Data encrypted and stored successfully!
                        </div>
                    </div>

                    <!-- Decrypt Data Tab -->
                    <div v-if="activeTab === 'decrypt'" class="space-y-6">
                        <div v-if="!isDecrypted">
                            <p class="text-gray-400 mb-6 font-medium">
                                {{ isRecoveryMode ? `Enter your Recovery Key to restore access to your data.` : `Enter
                                your password to
                                decrypt
                                your stored data.` }}
                            </p>

                            <div v-if="!isRecoveryMode">
                                <label class="block text-white font-semibold mb-2">Password</label>
                                <Input v-model="decryptPassword" type="password" placeholder="Enter your password"
                                    @keyup.enter="handleDecrypt" />
                            </div>

                            <!-- Recovery Key Input -->
                            <div v-else>
                                <label class="block text-white font-semibold mb-2">Recovery Key</label>
                                <Input v-model="recoveryKeyInput" placeholder="REC-XXXX-XXXX-XXXX-XXXX-XXXX"
                                    @keyup.enter="handleRecover" />
                                <p class="text-gray-400 text-xs mt-2">Format: REC-XXXX-XXXX-XXXX-XXXX-XXXX</p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                                <Button variant="primary" @click="isRecoveryMode ? handleRecover() : handleDecrypt()"
                                    :disabled="isLoading || (!decryptPassword && !isRecoveryMode) || (isRecoveryMode && !recoveryKeyInput)"
                                    class="flex-1">
                                    {{ isLoading ? (isRecoveryMode ? `Recovering...` : `Decrypting...`) :
                                        (isRecoveryMode ? `🔓 Recover
                                    Data` : `🔓
                                    Decrypt Data`) }}
                                </Button>

                                <Button variant="secondary" @click="isRecoveryMode = !isRecoveryMode" class="flex-1">
                                    {{ isRecoveryMode ? `Go Back to Password` : `🔑 Forgot Password?` }}
                                </Button>
                            </div>
                        </div>

                        <!-- Decrypted Data Display -->
                        <div v-else>
                            <div class="mb-6 flex justify-end">
                                <Button variant="secondary" @click="handleClearData">
                                    Clear Data
                                </Button>
                            </div>

                            <div class="p-4 rounded-lg bg-[#1f3a4a] border border-[#3b5265]">
                                <h3 class="text-white font-semibold mb-3">Decrypted Text:</h3>
                                <div
                                    class="bg-[#162936] p-4 rounded-lg border border-[#3b5265] text-white whitespace-pre-wrap wrap-break-word max-h-96 overflow-y-auto">
                                    {{ decryptedData }}
                                </div>
                            </div>

                            <div
                                class="mt-4 p-4 rounded-lg bg-blue-500 bg-opacity-10 border border-blue-500 text-blue-400 text-sm">
                                ℹ️ Your decrypted data is displayed here. It will be cleared from memory when you
                                navigate away.
                            </div>
                        </div>
                    </div>

                    <!-- View Stored Data Tab -->
                    <div v-if="activeTab === 'view'" class="space-y-6">
                        <p class="text-gray-400 mb-6">View information about your encrypted data stored on the server.
                        </p>

                        <Button variant="primary" @click="handleFetchStoredData" :disabled="isLoading">
                            {{ isLoading ? 'Loading...' : '📊 Fetch Stored Data Info' }}
                        </Button>

                        <div v-if="storedDataInfo" class="space-y-4">
                            <div class="p-4 rounded-lg bg-[#1f3a4a] border border-[#3b5265]">
                                <p class="text-gray-400 text-sm">Data Type</p>
                                <p class="text-white font-semibold">{{ storedDataInfo.data_type }}</p>
                            </div>

                            <div class="p-4 rounded-lg bg-[#1f3a4a] border border-[#3b5265]">
                                <p class="text-gray-400 text-sm">Created At</p>
                                <p class="text-white font-semibold">{{ formatDate(storedDataInfo.created_at) }}</p>
                            </div>

                            <div class="p-4 rounded-lg bg-[#1f3a4a] border border-[#3b5265]">
                                <p class="text-gray-400 text-sm">Last Updated</p>
                                <p class="text-white font-semibold">{{ formatDate(storedDataInfo.updated_at) }}</p>
                            </div>

                            <div
                                class="p-4 rounded-lg bg-yellow-500 bg-opacity-10 border border-yellow-500 text-yellow-400 text-sm">
                                ⚠️ Your data is encrypted on the server. Only you can decrypt it with your password.
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Recovery Key Modal (Shown after encryption) -->
        <RecoveryKeyModal :is-open="showRecoveryModal" :recovery-key="generatedRecoveryKey"
            @close="showRecoveryModal = false" />
    </DashboardLayout>
</template>

<script setup>
import { ref, computed, onBeforeUnmount } from 'vue';
import { useEncryptionStore } from '@/vue/stores/AllUser/encryptionStore';
import { useToastStore } from '@/vue/stores/toastStore';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import Card from '@/vue/components/ui/Card.vue';
import Button from '@/vue/components/ui/Button.vue';
import Input from '@/vue/components/ui/Input.vue';
import RecoveryKeyModal from '@/vue/components/encryption/RecoveryKeyModal.vue';
import apiClient from '@/vue/services/api';

const encryptionStore = useEncryptionStore();
const toastStore = useToastStore();

// Menu items
const menuItems = [
    { id: 'encrypt', label: '🔒 Encrypt Data' },
    { id: 'decrypt', label: '🔓 Decrypt Data' },
    { id: 'view', label: '📊 View Stored Data' },
];

const activeTab = ref('encrypt');
const activeTabLabel = computed(() => {
    return menuItems.find(item => item.id === activeTab.value)?.label || '';
});

// Encrypt tab state
const textToEncrypt = ref('');
const encryptPassword = ref('');
const encryptPasswordConfirm = ref('');
const encryptSuccess = ref(false);

// Decrypt tab state
const decryptPassword = ref('');
const isDecrypted = ref(false);
const decryptedData = ref(null);
const isRecoveryMode = ref(false);
const recoveryKeyInput = ref('');

// Recovery Flow
const showRecoveryModal = ref(false);
const generatedRecoveryKey = ref('');

// View tab state
const storedDataInfo = ref(null);

// Common state
const isLoading = ref(false);
const error = ref(null);

/**
 * Handle encryption
 */

const handleEncrypt = async () => {
    error.value = null;
    encryptSuccess.value = false;

    // Validate inputs
    if (!textToEncrypt.value.trim()) {
        error.value = 'Please enter text to encrypt';
        return;
    }

    if (textToEncrypt.value.length > 5000) {
        error.value = 'Text cannot exceed 5000 characters';
        return;
    }

    if (!encryptPassword.value) {
        error.value = 'Please enter a password';
        return;
    }

    if (encryptPassword.value !== encryptPasswordConfirm.value) {
        error.value = 'Passwords do not match';
        return;
    }

    if (encryptPassword.value.length < 6) {
        error.value = 'Password must be at least 6 characters';
        return;
    }

    isLoading.value = true;

    try {
        const result = await encryptionStore.encryptAndStoreData(encryptPassword.value, {
            text: textToEncrypt.value,
            timestamp: new Date().toISOString(),
        });

        if (result.success) {
            if (result.recoveryKey) {
                generatedRecoveryKey.value = result.recoveryKey;
                showRecoveryModal.value = true;
            }

            encryptSuccess.value = true;

            // Reset form
            textToEncrypt.value = '';
            encryptPassword.value = '';
            encryptPasswordConfirm.value = '';

            setTimeout(() => {
                encryptSuccess.value = false;
            }, 3000);
        }
    } catch (err) {
        error.value = err.response?.data?.message || err.message || 'Failed to encrypt and store data';
        toastStore.error('Error', error.value);
    } finally {
        isLoading.value = false;
    }
};

/**
 * Handle decryption
 */
const handleDecrypt = async () => {
    error.value = null;

    if (!decryptPassword.value) {
        error.value = 'Please enter your password';
        return;
    }

    isLoading.value = true;

    try {
        const result = await encryptionStore.decryptData(decryptPassword.value);

        if (result.success) {
            decryptedData.value = result.data.text;
            isDecrypted.value = true;
        }
    } catch (err) {
        error.value = 'Failed to decrypt data. Wrong password?';
        toastStore.error('Error', error.value);
    } finally {
        isLoading.value = false;
    }
};

/**
 * Handle recovery with key
 */
const handleRecover = async () => {
    error.value = null;

    if (!recoveryKeyInput.value) {
        error.value = 'Please enter your recovery key';
        return;
    }

    isLoading.value = true;

    try {
        const result = await encryptionStore.recoverDataWithKey(recoveryKeyInput.value);

        if (result.success) {
            decryptedData.value = result.data.text;
            isDecrypted.value = true;
            isRecoveryMode.value = false;
            recoveryKeyInput.value = '';
        }
    } catch (err) {
        error.value = 'Failed to recover data. Invalid recovery key?';
        toastStore.error('Error', error.value);
    } finally {
        isLoading.value = false;
    }
};

/**
 * Clear decrypted data
 */
const handleClearData = () => {
    decryptedData.value = null;
    isDecrypted.value = false;
    decryptPassword.value = '';
    toastStore.success('Success', 'Data cleared from memory');
};

/**
 * Fetch stored data info
 */
const handleFetchStoredData = async () => {
    error.value = null;
    isLoading.value = true;

    try {
        const response = await apiClient.get('/encrypted-data?type=user_text');

        if (response.data.success && response.data.data) {
            storedDataInfo.value = response.data.data;
            toastStore.success('Success', 'Data info loaded');
        } else {
            error.value = 'No encrypted data found';
        }
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to fetch data info';
        toastStore.error('Error', error.value);
    } finally {
        isLoading.value = false;
    }
};

/**
 * Format date for display
 */
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
};

/**
 * Clear data when component is unmounted
 */
onBeforeUnmount(() => {
    if (isDecrypted.value) {
        handleClearData();
    }
});
</script>
