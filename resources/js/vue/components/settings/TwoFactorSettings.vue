<template>
  <div class="space-y-6">
    <!-- 2FA Status -->
    <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-white font-semibold mb-1">Two-Factor Authentication</h3>
          <p class="text-gray-400 text-sm">
            {{ twoFactorStore.isEnabled ? '✅ Enabled' : '❌ Disabled' }}
          </p>
        </div>
        <Button
          v-if="!twoFactorStore.isEnabled"
          variant="primary"
          @click="startEnableFlow"
          :disabled="twoFactorStore.isLoading"
        >
          Enable 2FA
        </Button>
        <Button
          v-else
          variant="danger"
          @click="showDisableModal = true"
          :disabled="twoFactorStore.isLoading"
        >
          Disable 2FA
        </Button>
      </div>
    </div>

    <!-- Enable 2FA Flow -->
    <div v-if="showEnableFlow && !twoFactorStore.isEnabled" class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265] space-y-4">
      <h3 class="text-white font-semibold">Setup Two-Factor Authentication</h3>

      <!-- Step 1: QR Code -->
      <div v-if="step === 1" class="space-y-4">
        <p class="text-gray-400 text-sm">
          Step 1: Scan this QR code with your authenticator app (Google Authenticator, Authy, Microsoft Authenticator, etc.)
        </p>
        <div v-if="twoFactorStore.qrCode" class="flex justify-center p-4 bg-white rounded-lg">
          <div >
            <img :src="twoFactorStore.qrCode" alt="QR Code" class="w-48 h-auto">
          </div>
        </div>
        <div v-else class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#27e9b5]"></div>
        </div>

        <!-- Manual Entry -->
        <div class="p-3 bg-[#051824] rounded-lg border border-[#3b5265]">
          <p class="text-gray-400 text-xs mb-2">Or enter this key manually:</p>
          <div class="flex items-center gap-2">
            <code class="text-[#27e9b5] font-mono text-sm flex-1 break-all">{{ twoFactorStore.secret }}</code>
            <button
              @click="copySecret"
              class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded text-sm font-semibold hover:bg-[#1fc9a0] transition-colors"
            >
              Copy
            </button>
          </div>
        </div>

        <Button variant="primary" class="w-full" @click="step = 2">
          Next: Verify Code
        </Button>
      </div>

      <!-- Step 2: Verify Code -->
      <div v-if="step === 2" class="space-y-4">
        <p class="text-gray-400 text-sm">
          Step 2: Enter the 6-digit code from your authenticator app
        </p>
        <Input
          v-model="verificationCode"
          placeholder="000000"
          maxlength="6"
          class="text-center text-2xl tracking-widest"
          @keyup.enter="confirmSetup"
        />
        <div class="flex gap-2">
          <Button variant="secondary" class="flex-1" @click="step = 1">
            Back
          </Button>
          <Button
            variant="primary"
            class="flex-1"
            @click="confirmSetup"
            :disabled="verificationCode.length !== 6 || twoFactorStore.isLoading"
          >
            Verify & Enable
          </Button>
        </div>
      </div>

      <!-- Step 3: Recovery Codes -->
      <div v-if="step === 3" class="space-y-4">
        <p class="text-gray-400 text-sm">
          Step 3: Save your recovery codes in a safe place. You can use these if you lose access to your authenticator app.
        </p>
        <div class="p-3 bg-[#051824] rounded-lg border border-[#3b5265] space-y-2">
          <div v-for="(code, index) in twoFactorStore.recoveryCodes" :key="index" class="text-[#27e9b5] font-mono text-sm">
            {{ code }}
          </div>
        </div>
        <div class="flex gap-2">
          <Button variant="secondary" class="flex-1" @click="downloadRecoveryCodes">
            📥 Download
          </Button>
          <Button variant="secondary" class="flex-1" @click="copyRecoveryCodes">
            📋 Copy
          </Button>
        </div>
        <Button variant="primary" class="w-full" @click="completeSetup">
          Done
        </Button>
      </div>
    </div>

    <!-- Disable 2FA Modal -->
    <div v-if="showDisableModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-[#162936] rounded-lg p-6 max-w-md w-full mx-4 border border-[#3b5265]">
        <h3 class="text-white font-semibold mb-4">Disable Two-Factor Authentication</h3>
        <p class="text-gray-400 mb-6">
          Enter your password to confirm disabling 2FA. This will reduce your account security.
        </p>
        <Input
          v-model="disablePassword"
          type="password"
          placeholder="Enter your password"
          @keyup.enter="confirmDisable"
        />
        <div class="flex gap-2 mt-6">
          <Button variant="secondary" class="flex-1" @click="showDisableModal = false">
            Cancel
          </Button>
          <Button
            variant="danger"
            class="flex-1"
            @click="confirmDisable"
            :disabled="!disablePassword || twoFactorStore.isLoading"
          >
            Disable
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Button from '../ui/Button.vue';
import Input from '../ui/Input.vue';
import { useTwoFactorStore } from '../../stores/AllUser/twoFactorStore';

const twoFactorStore = useTwoFactorStore();

const showEnableFlow = ref(false);
const showDisableModal = ref(false);
const step = ref(1);
const verificationCode = ref('');
const disablePassword = ref('');

onMounted(() => {
  twoFactorStore.getStatus();
});

const startEnableFlow = async () => {
  twoFactorStore.reset();
  step.value = 1;
  showEnableFlow.value = true;
  await twoFactorStore.enableSetup();
};

const confirmSetup = async () => {
  const result = await twoFactorStore.confirmSetup(verificationCode.value);
  if (result.success) {
    step.value = 3;
    verificationCode.value = '';
  }
};

const completeSetup = () => {
  showEnableFlow.value = false;
  step.value = 1;
  verificationCode.value = '';
};

const confirmDisable = async () => {
  const result = await twoFactorStore.disable(disablePassword.value);
  if (result.success) {
    showDisableModal.value = false;
    disablePassword.value = '';
  }
};

const copySecret = () => {
  navigator.clipboard.writeText(twoFactorStore.secret);
  alert('Secret copied to clipboard');
};

const copyRecoveryCodes = () => {
  const codes = twoFactorStore.recoveryCodes.join('\n');
  navigator.clipboard.writeText(codes);
  alert('Recovery codes copied to clipboard');
};

const downloadRecoveryCodes = () => {
  const codes = twoFactorStore.recoveryCodes.join('\n');
  const element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(codes));
  element.setAttribute('download', '2fa-recovery-codes.txt');
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
};
</script>

