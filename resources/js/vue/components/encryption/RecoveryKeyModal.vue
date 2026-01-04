<template>
    <div v-if="isOpen"
        class="fixed inset-0  bg-opacity-60 flex items-center justify-center z-60 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Header with Gradient -->
            <div class="px-8 py-6 bg-[#27e9b5] text-black">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-[#051824] rounded-lg backdrop-blur-md text-white ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Your Recovery Key</h2>
                </div>
                <p class="text-black mt-2 text-sm leading-relaxed">
                    This key is the ONLY way to recover your data if you forget your passphrase.
                </p>
            </div>

            <!-- Body -->
            <div class="px-8 py-6">
                <!-- Warning Box -->
                <div
                    class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl flex gap-3">
                    <div class="text-amber-600 dark:text-amber-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Important Warning</h4>
                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">
                            If you lose this key, your data cannot be recovered by anyone. Store it in a safe place,
                            like a password
                            manager or a printed sheet.
                        </p>
                    </div>
                </div>

                <!-- Recovery Key Display -->
                <div class="relative group">
                    <label
                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Recovery Key
                    </label>
                    <div class="flex items-center gap-2">
                        <div
                            class="flex-1 font-mono text-lg font-bold tracking-widest bg-gray-50 dark:bg-gray-900 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl p-4 text-center text-gray-800 dark:text-gray-200 break-all select-all shadow-inner">
                            {{ recoveryKey }}
                        </div>
                        <button @click="copyToClipboard"
                            class="p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-all active:scale-95 group/btn"
                            title="Copy to clipboard">
                            <svg v-if="!isCopied"
                                class="w-6 h-6 text-gray-500 dark:text-gray-400 group-hover/btn:text-blue-600"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <svg v-else class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="mt-8">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" v-model="hasSaved"
                            class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                        <span
                            class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-all font-medium">
                            I have saved my recovery key in a safe place.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
                <button @click="handleClose" :disabled="!hasSaved"
                    class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-bold text-lg hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed disabled:hover:scale-100 shadow-xl">
                    Confirm & Continue
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useToastStore } from "@/vue/stores/toastStore";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    recoveryKey: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['close']);

const hasSaved = ref(false);
const isCopied = ref(false);

const copyToClipboard = () => {
    navigator.clipboard.writeText(props.recoveryKey).then(() => {
        isCopied.value = true;
        const toast = useToastStore();
        toast.success("Copied", "Recovery key copied to clipboard");
        setTimeout(() => {
            isCopied.value = false;
        }, 2000);
    });
};

const handleClose = () => {
    if (hasSaved.value) {
        emit('close');
    }
};
</script>

<style scoped>
.font-mono {
    font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
}
</style>
