import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Pages
import LoginPage from '../pages/auth/LoginPage.vue';
import RegisterPage from '../pages/auth/RegisterPage.vue';
import DashboardPage from '../pages/admin/DashboardPage.vue';
import UsersPage from '../pages/admin/UsersPage.vue';
import ProductsPage from '../pages/admin/ProductsPage.vue';
import CategoriesPage from '../pages/admin/CategoriesPage.vue';
import SettingsPage from '../pages/admin/SettingsPage.vue';

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
    path: '/products',
    name: 'Products',
    component: ProductsPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/categories',
    name: 'Categories',
    component: CategoriesPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/settings',
    name: 'Settings',
    component: SettingsPage,
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

