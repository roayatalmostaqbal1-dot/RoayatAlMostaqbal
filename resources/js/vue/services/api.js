import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const API_BASE_URL = '/api/v1';

// Create axios instance with default config
const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

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

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear auth data on unauthorized
            try {
                const authStore = useAuthStore();
                authStore.token = null;
                authStore.user = null;
            } catch (e) {
                console.error('Failed to clear auth data:', e);
            }
            window.location.href = '/admin/login';
        }
        return Promise.reject(error);
    }
);

export default apiClient;

