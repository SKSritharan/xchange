import { defineStore } from 'pinia';
import { ref } from 'vue';
import apiClient from '../support/apiClient';
import { useRouter } from 'vue-router';
import { useToast } from '../hooks/useToast';

export const useAuthStore = defineStore('auth', () => {
    const router = useRouter();
    const { toast } = useToast();

    // Reactive state
    const isAuthenticated = ref(!!localStorage.getItem('xchange_token'));
    const userEmail = ref(localStorage.getItem('xchange_email') || '');

    const login = async (email, password) => {
        try {
            const response = await apiClient.post('/login', { email, password });
            const { token, email: userEmailValue } = response.data;
            localStorage.setItem('xchange_token', token);
            localStorage.setItem('xchange_email', userEmailValue);
            isAuthenticated.value = true;
            userEmail.value = userEmailValue;
            toast({
                title: 'Success',
                description: 'Login successful',
            });
            router.push('/dashboard');
            return true;
        } catch (error) {
            const message = error.response?.data?.message || 'Login failed';
            toast({
                title: 'Error',
                description: message,
                variant: 'destructive',
            });
            throw new Error(message);
        }
    };

    const register = async (name, email, password, passwordConfirmation) => {
        try {
            const response = await apiClient().post('/register', {
                name,
                email,
                password,
                password_confirmation: passwordConfirmation,
            });
            const { token, email: userEmailValue } = response.data;
            localStorage.setItem('xchange_token', token);
            localStorage.setItem('xchange_email', userEmailValue);
            isAuthenticated.value = true;
            userEmail.value = userEmailValue;
            toast({
                title: 'Success',
                description: 'Registration successful',
            });
            router.push('/dashboard');
            return true;
        } catch (error) {
            const message = error.response?.data?.message || 'Registration failed';
            toast({
                title: 'Error',
                description: message,
                variant: 'destructive',
            });
            throw new Error(message);
        }
    };

    const logout = async () => {
        try {
            await apiClient.post('/logout', {}, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('xchange_token')}`,
                },
            });
            localStorage.removeItem('xchange_token');
            localStorage.removeItem('xchange_email');
            isAuthenticated.value = false;
            userEmail.value = '';
            toast({
                title: 'Success',
                description: 'Logout successful',
            });
            router.push('/login');
        } catch (error) {
            const message = error.response?.data?.message || 'Logout failed';
            toast({
                title: 'Error',
                description: message,
                variant: 'destructive',
            });
            throw new Error(message);
        }
    };

    // Add Axios interceptor to handle 401 errors
    apiClient.interceptors.response.use(
        response => response,
        error => {
            if (error.response?.status === 401) {
                logout();
            }
            return Promise.reject(error);
        }
    );

    return { isAuthenticated, userEmail, login, register, logout };
});
