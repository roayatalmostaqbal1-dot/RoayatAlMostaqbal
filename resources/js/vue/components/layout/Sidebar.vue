<template>
    <!-- Mobile Overlay -->
    <div v-if="isExpanded && isMobile" class="fixed inset-0  bg-opacity-50 z-30 md:hidden" @click="closeSidebar"></div>

    <aside :class="[
        'fixed left-0 top-0 h-screen w-64 bg-[#051824] border-r border-[#3b5265] transition-all duration-300 z-40',
        'overflow-y-auto',
        !isExpanded && '-translate-x-full md:translate-x-0 md:w-20',
    ]">
        <!-- Logo Section -->
        <div class="p-4 border-b border-[#3b5265] flex items-center justify-between">
            <div v-if="isExpanded" class="flex items-center gap-3">
                <img :src="logoUrl" alt="Logo" class="h-10 w-auto">
                <span class="text-white font-bold text-lg"> {{ formattedRoles }}</span>
            </div>
            <button @click="toggleSidebar" class="md:hidden text-white hover:text-[#27e9b5] transition-colors"
                aria-label="Close sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4 space-y-2">
            <router-link v-for="item in menuItems" :key="item.path" :to="item.path" @click="handleNavClick" :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300',
                'text-gray-400 hover:text-white hover:bg-[#162936]',
                isActive(item.path) && 'bg-[#27e9b5] text-[#051824] font-semibold',
            ]">
                <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                <span v-if="isExpanded" class="text-sm">{{ item.label }}</span>
            </router-link>
        </nav>

        <!-- User Section -->
        <div v-if="isExpanded" class="absolute bottom-0 left-0 right-0 p-4 border-t border-[#3b5265] bg-[#051824]">
            <div class="flex items-center gap-3 mb-4">
                <!-- User Avatar or Initial -->
                <div v-if="authStore.userAvatar" class="w-10 h-10 rounded-full flex-shrink-0 overflow-hidden">
                    <img :src="authStore.userAvatar" :alt="authStore.userName" class="w-full h-full object-cover">
                </div>
                <div v-else
                    class="w-10 h-10 rounded-full bg-[#27e9b5] flex items-center justify-center text-[#051824] font-bold flex-shrink-0">
                    {{ userInitial }}
                </div>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ authStore.userName }}</p>
                    <p class="text-gray-400 text-xs truncate">{{ authStore.userEmail }}</p>
                    <!-- User Roles -->
                    <div v-if="authStore.userRoles.length > 0" class="mt-1">
                        <p class="text-[#27e9b5] text-xs font-medium truncate">
                            {{ formattedRoles }}
                        </p>
                    </div>
                </div>
            </div>
            <Button variant="secondary" size="sm" class="w-full" @click="handleLogout">
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
// Dashboard icon - chart/grid
const IconDashboard = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z' })
    ])
};

// Users icon - people
const IconUsers = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' })
    ])
};

// Roles icon - shield/access control
const IconRoles = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z' })
    ])
};

// Permissions icon - key/lock
const IconPermissions = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M18 8h-1V6c0-2.76-2.24-5-5-5s-5 2.24-5 5v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z' })
    ])
};

// Settings icon - gear
const IconSettings = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.62l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.48.1.62l2.03 1.58c-.05.3-.07.62-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.62l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.48-.1-.62l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z' })
    ])
};

// Encryption icon - lock with key
const IconEncryption = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M12 1C6.48 1 2 5.48 2 11s4.48 10 10 10 10-4.48 10-10S17.52 1 12 1zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 7 15.5 7 14 7.67 14 8.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 7 8.5 7 7 7.67 7 8.5 7.67 10 8.5 10zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z' })
    ])
};

// Debug icon - bug/wrench
const IconDebug = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M11.99 5V1h-1v4H8.01V1H7v4c-1.1 0-2 .9-2 2v3h-.01C2.9 10 2 10.9 2 12s.9 2 2 2v3c0 1.1.9 2 2 2h1v4h1v-4h3.98v4h1v-4c1.1 0 2-.9 2-2v-3h.01c1.1 0 2-.9 2-2s-.9-2-2-2V7c0-1.1-.9-2-2-2h-1V1h-1v4h-3.01zm3.01 14H8.01V7h5.98v12z' })
    ])
};

// OAuth2 icon - key/lock
const IconOAuth2 = {
    render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { d: 'M18 8h-1V6c0-2.76-2.24-5-5-5s-5 2.24-5 5v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z' })
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
    { path: '/roles', label: 'Roles', icon: IconRoles },
    { path: '/permissions', label: 'Permissions', icon: IconPermissions },
    { path: '/oauth2-clients', label: 'OAuth2 Clients', icon: IconOAuth2 },
    { path: '/settings', label: 'Settings', icon: IconSettings },
    { path: '/encrypted-data', label: 'Encrypted Data', icon: IconEncryption },
    { path: '/encryption-debug', label: 'Encryption Debug', icon: IconDebug },
];

const userInitial = computed(() => {
    return authStore.userName.charAt(0).toUpperCase();
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
