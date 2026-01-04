<template>
    <DashboardLayout page-title="Encrypted Data Recovery"
        page-description="Recover encrypted user data in emergency situations">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Master Key Status -->
                <Card>
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-white">Master Key Status</h2>
                            <Button variant="secondary" size="sm" @click="fetchMasterKeyInfo"
                                :disabled="recoveryStore.isLoading">
                                🔄 Refresh
                            </Button>
                        </div>
                    </template>

                    <!-- Loading State -->
                    <div v-if="recoveryStore.isLoading && !recoveryStore.masterKeyInfo" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#27e9b5]"></div>
                        <p class="text-gray-400 mt-4">Loading...</p>
                    </div>

                    <!-- Master Key Info -->
                    <div v-else-if="recoveryStore.masterKeyInfo" class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-[#1f3a4a] rounded-lg">
                            <span class="text-gray-400">Master Key Status</span>
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-green-400 text-sm font-semibold">Active</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-[#1f3a4a] rounded-lg">
                            <span class="text-gray-400">Key ID</span>
                            <span class="text-white font-mono text-sm">{{ recoveryStore.masterKeyInfo.key_id }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-[#1f3a4a] rounded-lg">
                            <span class="text-gray-400">Server-Side Encryption</span>
                            <span class="text-green-400 text-sm font-semibold">Available</span>
                        </div>
                    </div>

                    <!-- No Master Key -->
                    <div v-else class="text-center py-8">
                        <p class="text-gray-400 mb-4">No active Master Key</p>
                        <p class="text-gray-500 text-sm">Run: php artisan encryption:generate-master-key</p>
                    </div>
                </Card>

                <!-- Recovery Form -->
                <Card>
                    <template #header>
                        <h2 class="text-lg font-bold text-white">Recover Encrypted Data</h2>
                    </template>

                    <div class="space-y-4">
                        <!-- User Selection -->
                        <div>
                            <label class="block text-gray-400 text-sm font-semibold mb-2">User</label>
                            <div class="relative flex gap-2">
                                <input v-model="searchQuery" type="text" placeholder="Search for user (name or email)"
                                    class="flex-1 px-4 py-2 bg-[#1f3a4a] border border-[#3b5265] rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-[#27e9b5]" />
                                <!-- Search Loading Indicator -->
                                <div v-if="isSearching" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-[#27e9b5]"></div>
                                </div>
                            </div>
                            <p v-if="searchQuery.length > 0 && searchQuery.length < 2"
                                class="text-gray-500 text-xs mt-1">
                                Type at least 2 characters to search
                            </p>

                            <!-- Search Results Dropdown -->
                            <div v-if="searchResults.length > 0"
                                class="mt-2 max-h-48 overflow-y-auto border border-[#3b5265] rounded-lg bg-[#1f3a4a]">
                                <div v-for="user in searchResults" :key="user.id" @click="selectUser(user)"
                                    class="p-3 hover:bg-[#2a4a5a] cursor-pointer border-b border-[#3b5265] last:border-0">
                                    <p class="text-white font-semibold">{{ user.name }}</p>
                                    <p class="text-gray-400 text-sm">{{ user.email }}</p>
                                </div>
                            </div>

                            <!-- Selected User Display -->
                            <div v-if="recoveryStore.selectedUser"
                                class="mt-2 p-3 bg-[#27e9b5] bg-opacity-10 border border-[#27e9b5] rounded-lg">
                                <p class="text-white font-semibold">Selected User: {{ recoveryStore.selectedUser.name }}
                                </p>
                                <p class="text-gray-400 text-sm">{{ recoveryStore.selectedUser.email }}</p>
                                <Button variant="secondary" size="sm" @click="clearSelection" class="mt-2">
                                    Clear Selection
                                </Button>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div v-if="recoveryStore.selectedUser">
                            <label class="block text-gray-400 text-sm font-semibold mb-2">User Password</label>
                            <input v-model="userPassword" type="password" placeholder="Enter user password"
                                class="w-full px-4 py-2 bg-[#1f3a4a] border border-[#3b5265] rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-[#27e9b5]"
                                @keyup.enter="confirmRecovery" />
                            <p class="text-gray-500 text-xs mt-1">⚠️ User password is required to recover data</p>
                        </div>

                        <!-- Data Type -->
                        <div v-if="recoveryStore.selectedUser">
                            <label class="block text-gray-400 text-sm font-semibold mb-2">Data Type</label>
                            <select v-model="dataType"
                                class="w-full px-4 py-2 bg-[#1f3a4a] border border-[#3b5265] rounded-lg text-white focus:outline-none focus:border-[#27e9b5]">
                                <option value="user_text">User Text</option>
                                <option value="profile">Profile</option>
                                <option value="settings">Settings</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Error Message -->
                        <div v-if="recoveryStore.error"
                            class="p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
                            {{ recoveryStore.error }}
                        </div>

                        <!-- Recover Button -->
                        <Button v-if="recoveryStore.selectedUser && userPassword" variant="primary" class="w-full"
                            @click="confirmRecovery" :disabled="recoveryStore.isLoading">
                            <span v-if="recoveryStore.isLoading">Recovering...</span>
                            <span v-else>🔓 Recover Data</span>
                        </Button>
                    </div>
                </Card>

                <!-- Recovered Data Display -->
                <Card v-if="recoveryStore.recoveredData">
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-white">Recovered Data</h2>
                            <Button variant="secondary" size="sm" @click="clearRecoveredData">
                                ✕ Close
                            </Button>
                        </div>
                    </template>

                    <div class="space-y-4">
                        <div class="p-4 bg-green-500 bg-opacity-10 border border-green-500 rounded-lg">
                            <p class="text-green-400 font-semibold mb-2">✅ Data recovered successfully</p>
                            <p class="text-gray-400 text-sm">Recovered at: {{ new Date().toLocaleString('en-US') }}</p>
                        </div>
                        <div class="p-4 bg-[#1f3a4a] rounded-lg">
                            <pre class="text-white text-sm overflow-x-auto">{{ JSON.stringify(recoveryStore.recoveredData, null, 2) }}
            </pre>
                        </div>
                        <div class="flex gap-2">
                            <Button variant="secondary" @click="copyToClipboard">
                                📋 Copy Data
                            </Button>
                            <Button variant="secondary" @click="downloadData">
                                💾 Download as JSON
                            </Button>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Card -->
                <Card>
                    <template #header>
                        <h2 class="text-lg font-bold text-white">Information</h2>
                    </template>
                    <div class="space-y-3 text-sm text-gray-400">
                        <p>• Data recovery requires Admin privileges</p>
                        <p>• User password must be provided</p>
                        <p>• All recovery operations are logged</p>
                        <p>• Recovered data is sensitive - protect carefully</p>
                    </div>
                </Card>

                <!-- Security Notice -->
                <Card>
                    <template #header>
                        <h2 class="text-lg font-bold text-white">⚠️ Security Warning</h2>
                    </template>
                    <div class="space-y-3 text-sm text-gray-400">
                        <p>This feature should only be used in authorized emergency situations.</p>
                        <p>Ensure you:</p>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Have obtained user consent</li>
                            <li>Document the reason for recovery</li>
                            <li>Protect the recovered data</li>
                        </ul>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Recovery Confirmation Modal -->
        <ConfirmationModal :isOpen="isConfirmModalOpen" title="Confirm Data Recovery"
            :message="`Are you sure you want to recover encrypted data for ${recoveryStore.selectedUser?.name}? This action will be logged.`"
            confirmButtonText="Recover Data" cancelButtonText="Cancel" buttonType="primary" @confirm="recoverData"
            @cancel="cancelRecovery" />
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import Card from '@/vue/components/ui/Card.vue';
import Button from '@/vue/components/ui/Button.vue';
import ConfirmationModal from '@/vue/components/ui/ConfirmationModal.vue';
import { useEncryptedDataRecoveryStore } from '@/vue/stores/Admin/encryptedDataRecoveryStore';
import { useToastStore } from '@/vue/stores/toastStore';
import apiClient from '@/vue/services/api';

const recoveryStore = useEncryptedDataRecoveryStore();
const toast = useToastStore();

// Component State
const searchQuery = ref('');
const searchResults = ref([]);
const userPassword = ref('');
const dataType = ref('user_text');
const isConfirmModalOpen = ref(false);
const isSearching = ref(false);
let searchTimeout = null;

// ------------------------------------
// User Search with Debounce
// ------------------------------------
const searchUsers = async () => {
    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    // Clear results if query is too short
    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        isSearching.value = false;
        return;
    }

    // Set searching state
    isSearching.value = true;

    // Debounce: wait 1 second after user stops typing
    searchTimeout = setTimeout(async () => {
        try {
            console.log('Searching for:', searchQuery.value);

            const response = await apiClient.get('/admin/users', {
                params: {
                    search: searchQuery.value,
                    per_page: 10,
                },
            });


            console.log('Search response:', response.data.success);

            if (response.data.status === 'success') {
                searchResults.value = response.data.data || [];

                if (searchResults.value.length === 0) {
                    toast.info('No Results', 'No users found matching your search');
                }
            } else {
                searchResults.value = [];
                console.log('Search failed:', response.data);
                toast.error('Search Failed', response.data.message || 'Failed to search users');
            }
        } catch (error) {
            console.error('Error searching users:', error);
            searchResults.value = [];

            const errorMessage = error.response?.data?.message || 'Failed to search users';
            toast.error('Search Error', errorMessage);
        } finally {
            isSearching.value = false;
        }
    }, 1000); // 1 second delay
};

// Watch searchQuery for changes
watch(searchQuery, () => {
    searchUsers();
});

// ------------------------------------
// Select User
// ------------------------------------
const selectUser = (user) => {
    console.log('Selected user:', user);
    recoveryStore.setSelectedUser(user);
    searchResults.value = [];
    searchQuery.value = user.name;
    isSearching.value = false;
};

// ------------------------------------
// Clear Selection
// ------------------------------------
const clearSelection = () => {
    recoveryStore.clearSelectedUser();
    searchQuery.value = '';
    userPassword.value = '';
    searchResults.value = [];
};

// ------------------------------------
// Confirm Recovery (Open Modal)
// ------------------------------------
const confirmRecovery = () => {
    if (!recoveryStore.selectedUser || !userPassword.value) {
        toast.error('Error', 'Please select a user and enter the password');
        return;
    }
    isConfirmModalOpen.value = true;
};

// ------------------------------------
// Cancel Recovery
// ------------------------------------
const cancelRecovery = () => {
    isConfirmModalOpen.value = false;
};

// ------------------------------------
// Recover Data
// ------------------------------------
const recoverData = async () => {
    isConfirmModalOpen.value = false;

    if (!recoveryStore.selectedUser || !userPassword.value) {
        toast.error('Error', 'Please select a user and enter the password');
        return;
    }

    await recoveryStore.recoverUserData(
        recoveryStore.selectedUser.id,
        userPassword.value,
        dataType.value
    );
};

// ------------------------------------
// Clear Recovered Data
// ------------------------------------
const clearRecoveredData = () => {
    recoveryStore.clearRecoveredData();
    userPassword.value = '';
};

// ------------------------------------
// Copy to Clipboard
// ------------------------------------
const copyToClipboard = () => {
    if (recoveryStore.recoveredData) {
        navigator.clipboard.writeText(JSON.stringify(recoveryStore.recoveredData, null, 2));
        toast.success('Success', 'Data copied to clipboard');
    }
};

// ------------------------------------
// Download Data
// ------------------------------------
const downloadData = () => {
    if (recoveryStore.recoveredData) {
        const dataStr = JSON.stringify(recoveryStore.recoveredData, null, 2);
        const dataBlob = new Blob([dataStr], { type: 'application/json' });
        const url = URL.createObjectURL(dataBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `recovered-data-${recoveryStore.selectedUser?.id}-${Date.now()}.json`;
        link.click();
        URL.revokeObjectURL(url);
        toast.success('Success', 'Data downloaded successfully');
    }
};

// ------------------------------------
// Fetch Master Key Info
// ------------------------------------
const fetchMasterKeyInfo = async () => {
    await recoveryStore.fetchMasterKeyInfo();
};

// ------------------------------------
// Lifecycle Hooks
// ------------------------------------
onMounted(() => {
    recoveryStore.fetchMasterKeyInfo();
});
</script>
