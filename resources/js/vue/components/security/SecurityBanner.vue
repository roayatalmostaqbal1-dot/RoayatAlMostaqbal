<template>
    <div class="bg-linear-to-r from-[#162936] to-[#1f3a4a] rounded-lg border border-[#3b5265] p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <!-- Title Section -->
            <div class="flex items-center gap-4">
                <svg class="w-12 h-12 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                </svg>
                <div>
                    <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 10.12h-6.78l2.74-2.82c-2.73-2.7-7.15-2.8-9.88-.1-2.73 2.71-2.73 7.08 0 9.79s7.15 2.71 9.88 0c1.36-1.34 2.04-2.91 2.04-4.9h2c0 1.98-.88 4.55-2.64 6.29-3.51 3.48-9.21 3.48-12.72 0-3.5-3.47-3.53-9.11-.02-12.58 3.51-3.47 9.14-3.47 12.65 0L21 3v7.12zM12.5 8v4.25l3.5 2.08-.72 1.21L11 13V8h1.5z" />
                        </svg>
                        Security Dashboard
                    </h2>
                    <p class="text-gray-400 mt-1">Real-time security monitoring and AI-powered threat analysis</p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="flex flex-wrap gap-4">
                <div class="px-4 py-3 bg-[#27e9b5]/10 rounded-lg border border-[#27e9b5]/30">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#27e9b5]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" />
                        </svg>
                        <div>
                            <h3 class="text-2xl font-bold text-white">100%</h3>
                            <p class="text-[#27e9b5] text-xs">Data Sovereignty</p>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-yellow-500/10 rounded-lg border border-yellow-500/30">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                        </svg>
                        <div>
                            <h3 class="text-2xl font-bold text-white">40%</h3>
                            <p class="text-yellow-400 text-xs">Cost Savings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// No props needed for static banner
</script>
