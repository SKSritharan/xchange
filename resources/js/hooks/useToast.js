import { ref } from 'vue';

export function useToast() {
    const toasts = ref([]);

    const toast = ({ title, description, variant }) => {
        const id = Date.now();
        toasts.value.push({ id, title, description, variant });
        setTimeout(() => {
            toasts.value = toasts.value.filter(t => t.id !== id);
        }, 5000); // Auto-dismiss after 5 seconds
    };

    return { toasts, toast };
}
