<template>
  <DashboardLayout page-title="Encrypted Data Demo" page-description="View encrypted data as stored in the database">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-12">
      <div class="text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#27e9b5]"></div>
        <p class="text-gray-400 mt-4">Loading encrypted data...</p>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else class="space-y-6">
      <!-- Info Banner -->
      <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
        <div class="flex gap-3">
          <svg class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <div>
            <h3 class="text-blue-400 font-semibold">Encryption Demo</h3>
            <p class="text-blue-300 text-sm mt-1">
              This page displays encrypted data exactly as stored in the database.
              Data is encrypted on the client-side and never decrypted on the server.
            </p>
          </div>
        </div>
      </div>

      <!-- Encryption Metadata Card -->
      <Card v-if="encryptionMetadata">
        <template #header>
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18 8h-1V6c0-2.76-2.24-5-5-5s-5 2.24-5 5v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Encryption Metadata</h3>
          </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-[#1f3a4a] rounded p-3">
            <p class="text-gray-400 text-sm">Encryption Method</p>
            <p class="text-white font-semibold">{{ encryptionMetadata.encryption_method }}</p>
          </div>
          <div class="bg-[#1f3a4a] rounded p-3">
            <p class="text-gray-400 text-sm">Key Derivation</p>
            <p class="text-white font-semibold">{{ encryptionMetadata.key_derivation }}</p>
          </div>
          <div class="bg-[#1f3a4a] rounded p-3">
            <p class="text-gray-400 text-sm">Has DEK Encryption</p>
            <p class="text-[#27e9b5] font-semibold">{{ encryptionMetadata.has_dek_encryption ? 'Yes' : 'No' }}</p>
          </div>
          <div class="bg-[#1f3a4a] rounded p-3">
            <p class="text-gray-400 text-sm">Has Recovery Encryption</p>
            <p class="text-[#27e9b5] font-semibold">{{ encryptionMetadata.has_recovery_encryption ? 'Yes' : 'No' }}</p>
          </div>
        </div>
      </Card>

      <!-- Encrypted Data Display -->
      <Card v-if="encryptedData">
        <template #header>
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-2.16-2.66c-.23-.29-.61-.37-.92-.15-.31.22-.38.61-.15.92l2.85 3.5c.23.29.61.37.92.15l3.54-4.39c.23-.29.15-.68-.15-.92-.29-.23-.68-.15-.92.15z"/>
            </svg>
            <h3 class="text-lg font-bold text-white">Encrypted Data (Raw Format)</h3>
          </div>
        </template>

        <div class="space-y-4">
          <!-- DEK Encryption -->
          <div class="border-b border-[#3b5265] pb-4">
            <h4 class="text-white font-semibold mb-3">DEK Encryption</h4>
            <EncryptedField label="Encrypted DEK" :value="encryptedData.encrypted_dek" />
            <EncryptedField label="DEK Salt" :value="encryptedData.dek_salt" />
            <EncryptedField label="DEK Nonce" :value="encryptedData.dek_nonce" />
          </div>

          <!-- Profile Data Encryption -->
          <div class="border-b border-[#3b5265] pb-4">
            <h4 class="text-white font-semibold mb-3">Profile Data Encryption</h4>
            <EncryptedField label="Profile Ciphertext" :value="encryptedData.profile_ciphertext" />
            <EncryptedField label="Profile Nonce" :value="encryptedData.profile_nonce" />
          </div>

          <!-- Server-Side Encryption (if available) -->
          <div v-if="encryptedData.encrypted_dek_server" class="border-b border-[#3b5265] pb-4">
            <h4 class="text-white font-semibold mb-3">Server-Side Encryption</h4>
            <EncryptedField label="Encrypted DEK (Server)" :value="encryptedData.encrypted_dek_server" />
            <EncryptedField v-if="encryptedData.dek_salt_recovery" label="DEK Salt (Recovery)" :value="encryptedData.dek_salt_recovery" />
            <EncryptedField v-if="encryptedData.dek_nonce_recovery" label="DEK Nonce (Recovery)" :value="encryptedData.dek_nonce_recovery" />
          </div>

          <!-- Metadata -->
          <div>
            <h4 class="text-white font-semibold mb-3">Metadata</h4>
            <div class="bg-[#1f3a4a] rounded p-3 text-gray-300 text-sm font-mono break-all">
              {{ encryptedData.metadata || 'No metadata' }}
            </div>
          </div>
        </div>
      </Card>

      <!-- Error State -->
      <div v-if="error" class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
        <div class="flex gap-3">
          <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <div>
            <h3 class="text-red-400 font-semibold">Error</h3>
            <p class="text-red-300 text-sm mt-1">{{ error }}</p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardLayout from '@/vue/components/layout/DashboardLayout.vue';
import Card from '@/vue/components/ui/Card.vue';
import EncryptedField from '@/vue/components/security/EncryptedField.vue';
import { useSecurityDashboardStore } from '@/vue/stores/securityDashboardStore';

const securityStore = useSecurityDashboardStore();

const isLoading = ref(false);
const error = ref(null);

const encryptedData = ref(null);
const encryptionMetadata = ref(null);

onMounted(async () => {
  isLoading.value = true;
  error.value = null;

  try {
    // Fetch encrypted data
    const dataResult = await securityStore.fetchEncryptedDataRaw('profile');
    if (dataResult.success) {
      encryptedData.value = dataResult.data;
    } else {
      error.value = dataResult.error || 'Failed to load encrypted data';
    }

    // Fetch encryption metadata
    const metadataResult = await securityStore.fetchEncryptionMetadata('profile');
    if (metadataResult.success) {
      encryptionMetadata.value = metadataResult.data;
    }
  } catch (err) {
    error.value = 'An error occurred while loading data';
    console.error('Error:', err);
  } finally {
    isLoading.value = false;
  }
});
</script>

