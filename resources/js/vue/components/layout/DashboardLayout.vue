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
          <button @click="toggleSidebar" class="md:hidden text-white hover:text-[#27e9b5] transition-colors"
            aria-label="Toggle sidebar">
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
          <NotificationMenu />

          <!-- User Menu -->
          <div class="flex items-center gap-3 pl-4 border-l border-[#3b5265]">
            <div class="text-right hidden sm:block">
              <p class="text-white text-sm font-semibold">{{ authStore.userName }}</p>
              <p class="text-gray-400 text-xs">{{ formattedRoles }}</p>
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
import { computed, ref, onMounted } from 'vue';
import { useAuthStore } from '@/vue/stores/Auth/auth';
import Sidebar from '@/vue/components/layout/Sidebar.vue';
import NotificationMenu from '@/vue/components/notifications/NotificationMenu.vue';

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
const formattedRoles = computed(() => {
  if (!authStore.userRoles || authStore.userRoles.length === 0) {
    return '';
  }
  // Format roles: capitalize first letter and replace hyphens with spaces
  return authStore.userRoles
    .map(role => role.charAt(0).toUpperCase() + role.slice(1).replace(/-/g, ' '))
    .join(', ');
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

// Fetch user data on mount to ensure data persists across page refreshes
onMounted(async () => {
  // Only fetch if we have a token but no user data
  if (authStore.isAuthenticated && !authStore.user) {
    await authStore.fetchUser();
  }
});
</script>

