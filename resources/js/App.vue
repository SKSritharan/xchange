<template>
    <div>
        <Navigation />
        <router-view />
        <!-- Toast Notifications -->
        <div class="fixed bottom-4 right-4 space-y-2 z-50">
            <div
                v-for="t in toasts"
                :key="t.id"
                :class="[
          'card-glass p-4 rounded-lg shadow-lg max-w-sm',
          t.variant === 'destructive' ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500'
        ]"
            >
                <h3 class="font-semibold">{{ t.title }}</h3>
                <p>{{ t.description }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navigation from './components/Navigation.vue';
import { useToast } from './hooks/useToast';

const { toasts } = useToast();
</script>
