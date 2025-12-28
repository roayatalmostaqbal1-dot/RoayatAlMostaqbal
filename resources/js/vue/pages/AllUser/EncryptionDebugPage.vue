<template>
  <DashboardLayout page-title="Encryption Debug" page-description="View encrypted data in database (Admin only)">
    <Card>
      <template #header>
        <h2 class="text-lg font-bold text-white">Debug Encrypted Data</h2>
      </template>

      <!-- Error Message -->
      <div v-if="error" class="mb-4 p-4 rounded-lg bg-red-500 bg-opacity-10 border border-red-500 text-red-400">
        {{ error }}
      </div>

      <!-- User Selection -->
      <div class="mb-6 space-y-4">
        <div>
          <label class="block text-white font-semibold mb-2">Select User ID (UUID)</label>
          <div class="flex gap-2">
            <Input
              v-model="selectedUserId"
              type="text"
              placeholder="Enter user UUID (e.g., 019b65d8-3f22-7009-8563-475356b5e062)"
            />
            <Button
              variant="primary"
              @click="fetchEncryptedData"
              :disabled="isLoading || !selectedUserId"
            >
              {{ isLoading ? 'Loading...' : '🔍 Fetch' }}
            </Button>
          </div>
        </div>
      </div>

      <!-- Encrypted Data Display -->
      <div v-if="encryptedDataList.length > 0" class="space-y-6">
        <div
          v-for="(data, index) in encryptedDataList"
          :key="data.id"
          class="border border-[#3b5265] rounded-lg overflow-hidden"
        >
          <!-- Card Header -->
          <div class="px-6 py-4 border-b border-[#3b5265] bg-[#1f3a4a]">
            <h3 class="text-white font-semibold">
              Encrypted Data #{{ index + 1 }} (ID: {{ data.id }})
            </h3>
            <p class="text-gray-400 text-sm mt-1">
              Type: <span class="font-mono bg-[#162936] px-2 py-1 rounded text-[#27e9b5]">{{ data.data_type }}</span>
            </p>
          </div>

          <!-- Card Body -->
          <div class="px-6 py-4 space-y-4">
            <!-- Encrypted DEK -->
            <div>
              <p class="text-gray-400 text-sm mb-2">Encrypted DEK (Data Encryption Key)</p>
              <div class="bg-[#162936] p-3 rounded-lg overflow-x-auto border border-[#3b5265]">
                <code class="text-xs text-gray-300 break-all font-mono">
                  {{ data.encrypted_dek }}
                </code>
              </div>
            </div>

            <!-- DEK Salt -->
            <div>
              <p class="text-gray-400 text-sm mb-2">DEK Salt (KDF Salt)</p>
              <div class="bg-[#162936] p-3 rounded-lg overflow-x-auto border border-[#3b5265]">
                <code class="text-xs text-gray-300 break-all font-mono">
                  {{ data.dek_salt }}
                </code>
              </div>
            </div>

            <!-- DEK Nonce -->
            <div>
              <p class="text-gray-400 text-sm mb-2">DEK Nonce (XChaCha20-Poly1305 Nonce)</p>
              <div class="bg-[#162936] p-3 rounded-lg overflow-x-auto border border-[#3b5265]">
                <code class="text-xs text-gray-300 break-all font-mono">
                  {{ data.dek_nonce }}
                </code>
              </div>
            </div>

            <!-- Profile Ciphertext -->
            <div>
              <p class="text-gray-400 text-sm mb-2">Profile Ciphertext (Encrypted Data)</p>
              <div class="bg-[#162936] p-3 rounded-lg overflow-x-auto border border-[#3b5265]">
                <code class="text-xs text-gray-300 break-all font-mono">
                  {{ data.profile_ciphertext }}
                </code>
              </div>
            </div>

            <!-- Profile Nonce -->
            <div>
              <p class="text-gray-400 text-sm mb-2">Profile Nonce (Data Nonce)</p>
              <div class="bg-[#162936] p-3 rounded-lg overflow-x-auto border border-[#3b5265]">
                <code class="text-xs text-gray-300 break-all font-mono">
                  {{ data.profile_nonce }}
                </code>
              </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#3b5265]">
              <div class="p-3 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                <p class="text-gray-400 text-xs mb-1">Created</p>
                <p class="text-white font-mono text-sm">{{ formatDate(data.created_at) }}</p>
              </div>
              <div class="p-3 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
                <p class="text-gray-400 text-xs mb-1">Updated</p>
                <p class="text-white font-mono text-sm">{{ formatDate(data.updated_at) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!isLoading && selectedUserId" class="text-center py-12">
        <p class="text-gray-400">
          No encrypted data found for user ID {{ selectedUserId }}
        </p>
      </div>

      <!-- Info Box -->
      <div class="mt-6 p-4 rounded-lg bg-yellow-500 bg-opacity-10 border border-yellow-500 text-yellow-400 text-sm">
        ⚠️ <strong>Security Note:</strong> This page displays encrypted data exactly as stored in the database. All data shown here is encrypted and cannot be read without the user's password. The server never has access to unencrypted data.
      </div>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import Input from '../../components/ui/Input.vue';
import apiClient from '../../services/api';
import { useToastStore } from '../../stores/toastStore';

const toastStore = useToastStore();

const selectedUserId = ref('');
const isLoading = ref(false);
const error = ref(null);
const encryptedDataList = ref([]);

/**
 * Fetch encrypted data for selected user
 */
const fetchEncryptedData = async () => {
  if (!selectedUserId.value) {
    error.value = 'Please enter a user ID';
    return;
  }

  isLoading.value = true;
  error.value = null;
  encryptedDataList.value = [];

  try {
    const response = await apiClient.get(`/admin/encrypted-data/${selectedUserId.value}`);

    if (response.data.success) {
      encryptedDataList.value = response.data.data;
      toastStore.success('Success', `Found ${response.data.data.length} encrypted record(s)`);
    } else {
      error.value = response.data.message || 'Failed to fetch encrypted data';
    }
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Failed to fetch encrypted data';
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
</script>

