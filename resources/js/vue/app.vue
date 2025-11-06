<template>
  <div class="min-h-screen">
    <router-view />
    <Toast />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './stores/auth';
import Toast from './components/ui/Toast.vue';

const authStore = useAuthStore();

onMounted(() => {
  // Restore session on app mount
  authStore.restoreSession();
});
</script>
