import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Pages
import LoginPage from '../pages/auth/LoginPage.vue';
import RegisterPage from '../pages/auth/RegisterPage.vue';
import SocialCallbackPage from '../pages/auth/SocialCallbackPage.vue';
import DashboardPage from '../pages/admin/DashboardPage.vue';
import UsersPage from '../pages/admin/UsersPage.vue';
import SettingsPage from '../pages/admin/SettingsPage.vue';
import RolesPage from '../pages/admin/RolesPage.vue';
import PermissionsPage from '../pages/admin/PermissionsPage.vue';
import EncryptedDataPage from '../pages/admin/EncryptedDataPage.vue';
import EncryptionDebugPage from '../pages/admin/EncryptionDebugPage.vue';

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
  } else {
    next();
  }
});

export default router;

