import axios from 'axios';
import { useAuthStore } from '../stores/Auth/auth';
import { useToastStore } from '../stores/toastStore';

const API_BASE_URL = '/api/v1';

// Flag to prevent multiple 401 redirects
let is401HandlingInProgress = false;

// Create axios instance with default config
const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// =====================
// Request Interceptor
// =====================
apiClient.interceptors.request.use((config) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        config.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }
    try {
        const authStore = useAuthStore();
        if (authStore.token) {
            config.headers.Authorization = `Bearer ${authStore.token}`;
        }
    } catch (e) {
        // Store not available yet
        console.error("Auth store not ready:", e);
    }
    return config;
});

// =====================
// Response Interceptor
// =====================
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            const requestUrl = error.config?.url || '';
            const authEndpoints = [
                '/auth/login',
                '/auth/register',
                '/auth/two-factor',
                '/auth/forgot-password',
                '/auth/reset-password',
                '/auth/verify-email',
            ];
            const isAuthEndpoint = authEndpoints.some(endpoint => requestUrl.includes(endpoint));
            if (!isAuthEndpoint && !is401HandlingInProgress) {
                is401HandlingInProgress = true;

                try {
                    const authStore = useAuthStore();
                    const toastStore = useToastStore();
                    const currentPath = window.location.pathname;
                    const redirectUrl = currentPath !== '/admin/login' ? currentPath : null;
                    authStore.clearAuthData();
                    toastStore.error(
                        'Session Expired',
                        'Your session has expired. Please login again.'
                    );
                    const loginUrl = redirectUrl
                        ? `/admin/login?redirect=${encodeURIComponent(redirectUrl)}`
                        : '/admin/login';
                    setTimeout(() => {
                        window.location.href = loginUrl;
                    }, 500);

                } catch (e) {
                    console.error('Error handling 401:', e);
                    window.location.href = '/admin/login';
                } finally {
                    setTimeout(() => {
                        is401HandlingInProgress = false;
                    }, 2000);
                }
            }
        }
        return Promise.reject(error);
    }
);

export default apiClient;

