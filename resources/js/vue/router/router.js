import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/vue/stores/Auth/auth';

// Pages
import LoginPage from '@/vue/pages/auth/LoginPage.vue';
import RegisterPage from '@/vue/pages/auth/RegisterPage.vue';
import SocialCallbackPage from '@/vue/pages/auth/SocialCallbackPage.vue';
import SetPasswordPage from '@/vue/pages/auth/SetPasswordPage.vue';
import ForgotPasswordPage from '@/vue/pages/auth/ForgotPasswordPage.vue';
import ResetPasswordPage from '@/vue/pages/auth/ResetPasswordPage.vue';
import DashboardPage from '@/vue/pages/admin/DashboardPage.vue';
import UsersPage from '@/vue/pages/admin/UsersPage.vue';
import SettingsPage from '@/vue/pages/AllUser/SettingsPage.vue';
import RolesPage from '@/vue/pages/SuberAdmin/RolesPage.vue';
import PermissionsPage from '@/vue/pages/SuberAdmin/PermissionsPage.vue';
import EncryptedDataPage from '@/vue/pages/AllUser/EncryptedDataPage.vue';
import EncryptionDebugPage from '@/vue/pages/AllUser/EncryptionDebugPage.vue';
import OAuth2ClientsPage from '@/vue/pages/SuberAdmin/OAuth2ClientsPage.vue';
import ContactsPage from '@/vue/pages/admin/ContactsPage.vue';
import PagesManagementPage from '@/vue/pages/SuberAdmin/PagesManagementPage.vue';
import EncryptedDataRecoveryPage from '@/vue/pages/admin/EncryptedDataRecoveryPage.vue';
import TelegramChatsPage from '@/vue/pages/admin/telegram/TelegramChats.vue';
import SecurityDashboardPage from '@/vue/pages/AllUser/SecurityDashboardPage.vue';
import EncryptedDataDemoPage from '@/vue/pages/security/EncryptedDataDemoPage.vue';
const routes = [
    {
        path: '/login',
        name: 'Login',
        component: LoginPage,
        meta: { requiresAuth: false, layout: 'blank' },
    },
    {
        path: '/register',
        name: 'Register',
        component: RegisterPage,
        meta: { requiresAuth: false, layout: 'blank' },
    },
    {
        path: '/social-callback',
        name: 'SocialCallback',
        component: SocialCallbackPage,
        meta: { requiresAuth: false, layout: 'blank' },
    },
    {
        path: '/set-password',
        name: 'SetPassword',
        component: SetPasswordPage,
        meta: { requiresAuth: false, layout: 'blank' },
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPasswordPage,
        meta: { requiresAuth: false, layout: 'blank' },
    },
    {
        path: '/reset-password/:token',
        name: 'ResetPassword',
        component: ResetPasswordPage,
        meta: { requiresAuth: false, layout: 'blank' },
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: DashboardPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/users',
        name: 'Users',
        component: UsersPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/settings',
        name: 'Settings',
        component: SettingsPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/roles',
        name: 'Roles',
        component: RolesPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/permissions',
        name: 'Permissions',
        component: PermissionsPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/encrypted-data',
        name: 'EncryptedData',
        component: EncryptedDataPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/encryption-debug',
        name: 'EncryptionDebug',
        component: EncryptionDebugPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/oauth2-clients',
        name: 'OAuth2Clients',
        component: OAuth2ClientsPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/contacts',
        name: 'Contacts',
        component: ContactsPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/pages-management',
        name: 'PagesManagement',
        component: PagesManagementPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/telegram-chats',
        name: 'TelegramChats',
        component: TelegramChatsPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/encrypted-data-recovery',
        name: 'EncryptedDataRecovery',
        component: EncryptedDataRecoveryPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/security-dashboard',
        name: 'SecurityDashboard',
        component: SecurityDashboardPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/security-dashboard/demo',
        name: 'EncryptedDataDemo',
        component: EncryptedDataDemoPage,
        meta: { requiresAuth: true },
    },
    {
        path: '',
        redirect: '/dashboard',
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard',
    },
];

const router = createRouter({
    history: createWebHistory('/admin'),
    routes,
});

const routePageMap = {
    '/dashboard': 'dashboard',
    '/users': 'users',
    '/roles': 'roles',
    '/permissions': 'permissions',
    '/pages-management': 'pages',
    '/oauth2-clients': 'oauth2-clients',
    '/contacts': 'contacts',
    '/telegram-chats': 'telegram-chats',
    '/settings': 'settings',
    '/encrypted-data': 'encrypted-data',
    '/encrypted-data-recovery': 'encrypted-data-recovery',
    '/security-dashboard': 'security-dashboard',
    '/security-dashboard/demo': 'security-dashboard',
};

// Navigation guards
router.beforeEach((to, _from, next) => {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated;
    const requiresAuth = to.meta.requiresAuth !== false;

    if (requiresAuth && !isAuthenticated) {
        // Redirect to login if trying to access protected route
        next('/login');
    } else if ((to.path === '/login' || to.path === '/register') && isAuthenticated) {
        // Redirect to dashboard if already logged in
        next('/dashboard');
    } else if (requiresAuth && isAuthenticated) {
        const pageKey = routePageMap[to.path];

        if (!pageKey) {
            next();
            return;
        }


        if (!authStore.userPages || authStore.userPages.length === 0) {
            next();
            return;
        }

        if (!authStore.hasPageAccess(pageKey)) {
            const accessiblePages = [
                { path: '/dashboard', key: 'dashboard' },
                { path: '/users', key: 'users' },
                { path: '/roles', key: 'roles' },
                { path: '/permissions', key: 'permissions' },
                { path: '/pages-management', key: 'pages' },
                { path: '/oauth2-clients', key: 'oauth2-clients' },
                { path: '/contacts', key: 'contacts' },
                { path: '/telegram-chats', key: 'telegram-chats' },
                { path: '/settings', key: 'settings' },
                { path: '/encrypted-data', key: 'encrypted-data' },
                { path: '/encrypted-data-recovery', key: 'encrypted-data-recovery' },
            ];

            const firstAccessible = accessiblePages.find(page =>
                authStore.hasPageAccess(page.key)
            );

            if (firstAccessible) {
                next(firstAccessible.path);
            } else {
                next();
            }
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;

