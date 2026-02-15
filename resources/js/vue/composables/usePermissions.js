import { computed } from 'vue';
import { useAuthStore } from '../stores/auth';

/**
 * Composable for permission and role checking in Vue components
 *
 * Usage:
 * const { can, canAny, canAll, hasRole, hasAnyRole, hasAllRoles, isSuperAdmin, isAdmin } = usePermissions();
 *
 * if (can('users.view')) {
 *   // Show users management
 * }
 */
export function usePermissions() {
    const authStore = useAuthStore();

    /**
     * Check if user has a specific permission
     */
    const can = (permission) => {
        return authStore.hasPermission(permission);
    };

    /**
     * Check if user has any of the given permissions
     */
    const canAny = (permissions) => {
        return authStore.hasAnyPermission(permissions);
    };

    /**
     * Check if user has all of the given permissions
     */
    const canAll = (permissions) => {
        return authStore.hasAllPermissions(permissions);
    };

    /**
     * Check if user has a specific role
     */
    const hasRole = (role) => {
        return authStore.hasRole(role);
    };

    /**
     * Check if user has any of the given roles
     */
    const hasAnyRole = (roles) => {
        return authStore.hasAnyRole(roles);
    };

    /**
     * Check if user has all of the given roles
     */
    const hasAllRoles = (roles) => {
        return authStore.hasAllRoles(roles);
    };

    /**
     * Check if user is super admin
     */
    const isSuperAdmin = computed(() => authStore.isSuperAdmin);

    /**
     * Check if user is admin (admin or super admin)
     */
    const isAdmin = computed(() => authStore.isAdmin);

    /**
     * Get user's permissions
     */
    const userPermissions = computed(() => authStore.permissions);

    /**
     * Get user's roles
     */
    const userRoles = computed(() => authStore.roles);

    return {
        can,
        canAny,
        canAll,
        hasRole,
        hasAnyRole,
        hasAllRoles,
        isSuperAdmin,
        isAdmin,
        userPermissions,
        userRoles,
    };
}

