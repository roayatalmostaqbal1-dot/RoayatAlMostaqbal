import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { authService } from '../services/api';

export const useAuthStore = defineStore('auth', () => {
    // State
    const user = ref(null);
    const token = ref(localStorage.getItem('auth_token') || null);
    const isLoading = ref(false);
    const error = ref(null);

    // Computed
    const isAuthenticated = computed(() => !!token.value);
    const userName = computed(() => user.value?.name || '');
    const userEmail = computed(() => user.value?.email || '');

    // Actions
    const login = async (email, password) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await authService.login(email, password);
            const { token: newToken, data } = response.data;
            
            token.value = newToken;
            user.value = data;
            
            localStorage.setItem('auth_token', newToken);
            localStorage.setItem('user', JSON.stringify(data));
            
            return { success: true, data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Login failed';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const register = async (userData) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await authService.register(userData);
            const { token: newToken, data } = response.data;
            
            token.value = newToken;
            user.value = data;
            
            localStorage.setItem('auth_token', newToken);
            localStorage.setItem('user', JSON.stringify(data));
            
            return { success: true, data };
        } catch (err) {
            error.value = err.response?.data?.message || 'Registration failed';
            return { success: false, error: error.value };
        } finally {
            isLoading.value = false;
        }
    };

    const logout = async () => {
        isLoading.value = true;
        try {
            await authService.logout();
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            token.value = null;
            user.value = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            isLoading.value = false;
        }
    };

    const fetchUser = async () => {
        if (!token.value) return;
        
        isLoading.value = true;
        try {
            const response = await authService.getUser();
            user.value = response.data.data;
            localStorage.setItem('user', JSON.stringify(user.value));
        } catch (err) {
            console.error('Fetch user error:', err);
            token.value = null;
            localStorage.removeItem('auth_token');
        } finally {
            isLoading.value = false;
        }
    };

    const socialAuthRedirect = (provider) => {
        authService.socialAuthRedirect(provider);
    };

    const restoreSession = () => {
        const savedToken = localStorage.getItem('auth_token');
        const savedUser = localStorage.getItem('user');
        
        if (savedToken) {
            token.value = savedToken;
        }
        if (savedUser) {
            try {
                user.value = JSON.parse(savedUser);
            } catch (err) {
                console.error('Error parsing saved user:', err);
            }
        }
    };

    return {
        // State
        user,
        token,
        isLoading,
        error,
        
        // Computed
        isAuthenticated,
        userName,
        userEmail,
        
        // Actions
        login,
        register,
        logout,
        fetchUser,
        socialAuthRedirect,
        restoreSession,
    };
}, {
    persist: true,
});

