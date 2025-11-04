import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Pages
import LoginPage from '../pages/auth/LoginPage.vue';
import RegisterPage from '../pages/auth/RegisterPage.vue';
import DashboardPage from '../pages/admin/DashboardPage.vue';
import UsersPage from '../pages/admin/UsersPage.vue';
import SettingsPage from '../pages/admin/SettingsPage.vue';

const routes = [
  {
    path: '/admin/login',
    name: 'Login',
    component: LoginPage,
    meta: { requiresAuth: false, layout: 'blank' },
  },
  {
    path: '/admin/register',
    name: 'Register',
    component: RegisterPage,
    meta: { requiresAuth: false, layout: 'blank' },
  },
  {
    path: '/admin/dashboard',
    name: 'Dashboard',
    component: DashboardPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/users',
    name: 'Users',
    component: UsersPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/settings',
    name: 'Settings',
    component: SettingsPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin',
    redirect: '/admin/dashboard',
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/admin/dashboard',
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
    next('/admin/login');
  } else if ((to.path === '/admin/login' || to.path === '/admin/register') && isAuthenticated) {
    // Redirect to dashboard if already logged in
    next('/admin/dashboard');
  } else {
    next();
  }
});

export default router;

