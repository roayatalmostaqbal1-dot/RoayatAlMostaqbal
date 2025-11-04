import axios from 'axios';

const API_BASE_URL = '/api/v1';

// Create axios instance with default config
const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Add token to requests if available
apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Handle response errors
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear auth data on unauthorized
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '/admin/login';
        }
        return Promise.reject(error);
    }
);

export const authService = {
    // Standard login
    login: (email, password) => {
        return apiClient.post('/auth/login', { email, password });
    },

    // Standard registration
    register: (data) => {
        return apiClient.post('/auth/register', data);
    },

    // Get current user
    getUser: () => {
        return apiClient.get('/user');
    },

    // Logout
    logout: () => {
        return apiClient.post('/logout');
    },

    // Social auth redirect
    socialAuthRedirect: (provider) => {
        window.location.href = `${API_BASE_URL}/social-auth/${provider}`;
    },

    // Social auth callback handler
    handleSocialAuthCallback: (provider) => {
        return apiClient.get(`/social-auth/${provider}/callback`);
    },
};

export default apiClient;

