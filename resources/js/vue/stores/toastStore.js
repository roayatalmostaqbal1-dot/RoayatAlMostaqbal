import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useToastStore = defineStore('toast', () => {
    const toasts = ref([]);

    const addToast = (toast) => {
        const id = Date.now() + Math.random();
        const newToast = {
            id,
            type: toast.type || 'success',
            title: toast.title || (toast.type === 'error' ? 'Error' : 'Success'),
            message: toast.message || '',
            duration: toast.duration || 4000,
        };

        toasts.value.push(newToast);

        // Auto remove after duration
        setTimeout(() => {
            removeToast(id);
        }, newToast.duration);

        return id;
    };

    const removeToast = (id) => {
        const index = toasts.value.findIndex(toast => toast.id === id);
        if (index > -1) {
            toasts.value.splice(index, 1);
        }
    };

    const success = (title, message = '') => {
        return addToast({
            type: 'success',
            title,
            message,
        });
    };

    const error = (title, message = '') => {
        return addToast({
            type: 'error',
            title,
            message,
        });
    };

    const clear = () => {
        toasts.value = [];
    };

    return {
        toasts,
        addToast,
        removeToast,
        success,
        error,
        clear,
    };
});
