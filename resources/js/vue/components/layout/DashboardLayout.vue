<template>
  <div class="flex h-screen bg-gray-900">
    <!-- Sidebar -->
    <Sidebar ref="sidebarRef" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64">
      <!-- Header -->
      <header class="bg-[#162936] border-b border-[#3b5265] px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <!-- Mobile Hamburger Button -->
          <button
            @click="toggleSidebar"
            class="md:hidden text-white hover:text-[#27e9b5] transition-colors"
            aria-label="Toggle sidebar"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
          <div>
            <h1 class="text-2xl font-bold text-white">{{ pageTitle }}</h1>
            <p class="text-gray-400 text-sm">{{ pageDescription }}</p>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <!-- Notifications -->
          <button class="relative text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>

          <!-- User Menu -->
          <div class="flex items-center gap-3 pl-4 border-l border-[#3b5265]">
            <div class="text-right hidden sm:block">
              <p class="text-white text-sm font-semibold">{{ authStore.userName }}</p>
              <p class="text-gray-400 text-xs">Admin</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-[#27e9b5] flex items-center justify-center text-[#051824] font-bold">
              {{ userInitial }}
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-auto bg-gray-900">
        <div class="p-6">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useAuthStore } from '../../stores/auth';
import Sidebar from './Sidebar.vue';

const props = defineProps({
  pageTitle: {
    type: String,
    default: 'Dashboard',
  },
  pageDescription: {
    type: String,
    default: 'Welcome to your admin dashboard',
  },
});

const authStore = useAuthStore();
const sidebarRef = ref(null);

const userInitial = computed(() => {
  return authStore.userName.charAt(0).toUpperCase();
});

const toggleSidebar = () => {
  if (sidebarRef.value) {
    sidebarRef.value.toggleSidebar();
  }
};
</script>

