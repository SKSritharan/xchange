import { ref } from 'vue';

export function useToast() {
    const toasts = ref([]);

    const toast = ({ title, description, variant = 'default' }) => {
        const id = Date.now();
        toasts.value.push({ id, title, description, variant });
        setTimeout(() => {
            toasts.value = toasts.value.filter(t => t.id !== id);
        }, 5000);
    };

    return { toast, toasts };
}
