<template>
  <!-- Mobile Overlay -->
  <div
    v-if="isExpanded && isMobile"
    class="fixed inset-0  bg-opacity-50 z-30 md:hidden"
    @click="closeSidebar"
  ></div>

  <aside
    :class="[
      'fixed left-0 top-0 h-screen w-64 bg-[#051824] border-r border-[#3b5265] transition-all duration-300 z-40',
      'overflow-y-auto',
      !isExpanded && '-translate-x-full md:translate-x-0 md:w-20',
    ]"
  >
    <!-- Logo Section -->
    <div class="p-4 border-b border-[#3b5265] flex items-center justify-between">
      <div v-if="isExpanded" class="flex items-center gap-3">
        <img :src="logoUrl" alt="Logo" class="h-10 w-auto">
        <span class="text-white font-bold text-lg">Admin</span>
      </div>
      <button
        @click="toggleSidebar"
        class="md:hidden text-white hover:text-[#27e9b5] transition-colors"
        aria-label="Close sidebar"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        @click="handleNavClick"
        :class="[
          'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300',
          'text-gray-400 hover:text-white hover:bg-[#162936]',
          isActive(item.path) && 'bg-[#27e9b5] text-[#051824] font-semibold',
        ]"
      >
        <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
        <span v-if="isExpanded" class="text-sm">{{ item.label }}</span>
      </router-link>
    </nav>

    <!-- User Section -->
    <div v-if="isExpanded" class="absolute bottom-0 left-0 right-0 p-4 border-t border-[#3b5265] bg-[#051824]">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-[#27e9b5] flex items-center justify-center text-[#051824] font-bold">
          {{ userInitial }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-white text-sm font-semibold truncate">{{ authStore.userName }}</p>
          <p class="text-gray-400 text-xs truncate">{{ authStore.userEmail }}</p>
        </div>
      </div>
      <Button
        variant="secondary"
        size="sm"
        class="w-full"
        @click="handleLogout"
      >
        Logout
      </Button>
    </div>
  </aside>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, h } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import Button from '../ui/Button.vue';

// Icons using render functions (no runtime compilation needed)
const IconDashboard = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z' })
  ])
};

const IconUsers = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' })
  ])
};

const IconSettings = {
  render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
    h('path', { d: 'M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.62l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.48.1.62l2.03 1.58c-.05.3-.07.62-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.62l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.48-.1-.62l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z' })
  ])
};

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const isExpanded = ref(true);
const isMobile = ref(false);

// Logo URL - using public asset
const logoUrl = '/RoayatAlMostaqbal.svg';

const menuItems = [
  { path: '/dashboard', label: 'Dashboard', icon: IconDashboard },
  { path: '/users', label: 'Users', icon: IconUsers },
  { path: '/roles', label: 'Roles', icon: IconUsers },
  { path: '/permissions', label: 'Permissions', icon: IconUsers },
  { path: '/api-routes', label: 'API Routes', icon: IconUsers },
  { path: '/settings', label: 'Settings', icon: IconSettings },
];

const userInitial = computed(() => {
  return authStore.userName.charAt(0).toUpperCase();
});

const isActive = (path) => {
  return route.path === path;
};

const checkMobile = () => {
  isMobile.value = window.innerWidth < 768;
  // Close sidebar on mobile by default
  if (isMobile.value && isExpanded.value) {
    isExpanded.value = false;
  }
};

const toggleSidebar = () => {
  isExpanded.value = !isExpanded.value;
  localStorage.setItem('sidebar-expanded', isExpanded.value);
};

const closeSidebar = () => {
  if (isMobile.value) {
    isExpanded.value = false;
  }
};

const handleNavClick = () => {
  // Close sidebar on mobile after navigation
  if (isMobile.value) {
    isExpanded.value = false;
  }
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/admin/login');
};

// Restore sidebar state and setup mobile detection
onMounted(() => {
  checkMobile();
  window.addEventListener('resize', checkMobile);

  if (localStorage.getItem('sidebar-expanded') === 'false') {
    isExpanded.value = false;
  }
});

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile);
});

// Expose toggleSidebar method to parent components
defineExpose({
  toggleSidebar
});
</script>

