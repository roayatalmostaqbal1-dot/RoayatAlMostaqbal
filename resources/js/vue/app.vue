<template>
  <div class="min-h-screen">
    <router-view />
    <Toast />
  </div>
</template>

<script setup>
import Toast from './components/ui/Toast.vue';
</script>
