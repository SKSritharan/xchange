<template>
    <div class="min-h-screen page-transition">
        <!-- Navbar -->
        <Navigation/>

        <!-- Main Content -->
        <div id="app">
            <!-- Toasts -->
            <div class="fixed top-4 right-4 z-50 space-y-2">
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="[
          'p-4 rounded-lg shadow-lg max-w-sm',
          toast.variant === 'destructive' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'
        ]"
                >
                    <h3 class="font-semibold">{{ toast.title }}</h3>
                    <p>{{ toast.description }}</p>
                </div>
            </div>
            <router-view class="page-transition"></router-view>
        </div>
    </div>
</template>

<script setup>
import {RouterView} from 'vue-router';
import { useToast } from './hooks/useToast';
import Navigation from "./components/Navigation.vue";

const { toasts } = useToast();
</script>
