<template>
  <Card>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <svg class="w-6 h-6 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
          </svg>
          <h3 class="text-lg font-bold text-white">Digital Identity</h3>
        </div>
        <span :class="[
          'px-3 py-1 rounded-full text-xs font-semibold',
          identityData?.digital_identity_status === 'active'
            ? 'bg-green-500/20 text-green-400'
            : 'bg-gray-500/20 text-gray-400'
        ]">
          {{ identityData?.digital_identity_status === 'active' ? 'Active' : 'Inactive' }}
        </span>
      </div>
    </template>

    <div v-if="identityData" class="space-y-4">
      <!-- Identity Fields -->
      <div class="space-y-3">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-[#27e9b5] mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <div class="flex-1">
            <p class="text-gray-400 text-sm">Full Name</p>
            <p class="text-white font-semibold">{{ identityData.user_name }}</p>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-[#27e9b5] mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/>
          </svg>
          <div class="flex-1">
            <p class="text-gray-400 text-sm">Email</p>
            <p class="text-white font-semibold">{{ identityData.user_email }}</p>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-[#27e9b5] mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12zM6 10h2v2H6zm0 4h8v2H6zm10-4h2v6h-2z"/>
          </svg>
          <div class="flex-1">
            <p class="text-gray-400 text-sm">Identity Number</p>
            <p class="text-white font-semibold">{{ identityData.identity_number }}</p>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-[#27e9b5] mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3z"/>
          </svg>
          <div class="flex-1">
            <p class="text-gray-400 text-sm">Security Level</p>
            <p :class="[
              'font-semibold capitalize',
              identityData.security_level === 'high' || identityData.security_level === 'critical'
                ? 'text-green-400'
                : 'text-yellow-400'
            ]">
              {{ identityData.security_level }}
            </p>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-[#27e9b5] mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
          </svg>
          <div class="flex-1">
            <p class="text-gray-400 text-sm">Expiry Date</p>
            <p class="text-green-400 font-semibold">{{ identityData.expiry_date }}</p>
          </div>
        </div>
      </div>

      <!-- UAE PASS Integration -->
      <div class="border-t border-[#3b5265] pt-4">
        <div class="flex items-center gap-2 mb-3">
          <svg class="w-5 h-5 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
          </svg>
          <h4 class="text-white font-semibold">UAE PASS Integration</h4>
        </div>

        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <svg :class="[
              'w-4 h-4',
              identityData.uae_pass_connected ? 'text-green-400' : 'text-gray-500'
            ]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
            </svg>
            <span :class="identityData.uae_pass_connected ? 'text-green-400' : 'text-gray-400'">
              {{ identityData.uae_pass_connected ? 'Connected' : 'Not Connected' }}
            </span>
          </div>

          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
            </svg>
            <span class="text-gray-400">Encryption: AES-256</span>
          </div>

          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
            </svg>
            <span class="text-gray-400">Last Sync: {{ identityData.last_sync }}</span>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-8">
      <p class="text-gray-400">Loading identity data...</p>
    </div>
  </Card>
</template>

<script setup>
import Card from '@/vue/components/ui/Card.vue';

defineProps({
  identityData: {
    type: Object,
    default: null,
  },
});
</script>
