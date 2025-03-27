import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export function useAuth() {
    const router = useRouter();
    const isAuthenticated = ref(!!localStorage.getItem('xchange_token'));

    const login = async (email, password) => {
        try {
            const response = await axios.post('/api/v1/login', { email, password });
            const { token, email: userEmail } = response.data;
            localStorage.setItem('xchange_token', token);
            localStorage.setItem('xchange_email', userEmail);
            isAuthenticated.value = true;
            return true;
        } catch (error) {
            throw new Error(error.response?.data?.message || 'Login failed');
        }
    };

    const register = async (name, email, password) => {
        try {
            const response = await axios.post('/api/v1/register', { name, email, password });
            const { token, email: userEmail } = response.data;
            localStorage.setItem('xchange_token', token);
            localStorage.setItem('xchange_email', userEmail);
            isAuthenticated.value = true;
            return true;
        } catch (error) {
            throw new Error(error.response?.data?.message || 'Registration failed');
        }
    };

    const logout = () => {
        localStorage.removeItem('xchange_token');
        localStorage.removeItem('xchange_email');
        isAuthenticated.value = false;
        router.push('/login');
    };

    return { isAuthenticated, login, register, logout };
}
