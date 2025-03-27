<template>
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-border">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo & Brand -->
                <router-link to="/" class="flex items-center space-x-2">
          <span class="h-8 w-8 bg-gradient-to-tr from-primary to-secondary rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-sm">X</span>
          </span>
                    <span class="font-semibold text-xl">XChange</span>
                </router-link>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <router-link
                        to="/dashboard"
                        :class="[
              'font-medium transition-colors',
              isActive('/dashboard') ? 'text-primary' : 'text-foreground hover:text-primary'
            ]"
                    >
                        Dashboard
                    </router-link>
                    <router-link

                        to="/data-entry"
                        :class="[
              'font-medium transition-colors',
              isActive('/data-entry') ? 'text-primary' : 'text-foreground hover:text-primary'
            ]"
                    >
                        Data Entry
                    </router-link>
                </div>

                <!-- User Section (Desktop) -->
                <div class="hidden md:flex items-center space-x-4">
                    <template v-if="isAuthenticated">
                        <div class="relative">
                            <button
                                @click="toggleUserMenu"
                                class="flex items-center space-x-2 rounded-full bg-muted px-4 py-2 transition-colors hover:bg-muted/80"
                            >
                                <UserCircle :size="18" />
                                <span class="max-w-[100px] truncate text-sm">{{ userEmail }}</span>
                                <ChevronDown :size="16" />
                            </button>
                            <div
                                v-if="userMenuOpen"
                                class="absolute right-0 mt-2 w-48 card-glass py-2 z-50 animate-fade-in"
                            >
                                <button
                                    class="flex w-full items-center space-x-2 px-4 py-2 text-sm text-red-500 hover:bg-muted/50"
                                    @click="handleLogout"
                                >
                                    <LogOut :size="16" />
                                    <span>Logout</span>
                                </button>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex items-center space-x-4">
                            <router-link to="/login" class="text-foreground hover:text-primary transition-colors">
                                Login
                            </router-link>
                            <router-link to="/register" class="btn-primary">
                                Register
                            </router-link>
                        </div>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden" @click="toggleMenu">
                    <X v-if="isOpen" :size="24" />
                    <Menu v-else :size="24" />
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="isOpen" class="md:hidden bg-white border-t border-border animate-fade-in">
            <div class="container mx-auto px-4 py-4 flex flex-col space-y-4">
                <router-link
                    to="/dashboard"
                    :class="[
            'font-medium p-2 rounded-lg',
            isActive('/dashboard') ? 'bg-muted text-primary' : 'text-foreground hover:bg-muted'
          ]"
                    @click="toggleMenu"
                >
                    <div class="flex items-center space-x-3">
                        <BarChart2 :size="18" />
                        <span>Dashboard</span>
                    </div>
                </router-link>
                <router-link
                    v-if="isAuthenticated"
                    to="/data-entry"
                    :class="[
            'font-medium p-2 rounded-lg',
            isActive('/data-entry') ? 'bg-muted text-primary' : 'text-foreground hover:bg-muted'
          ]"
                    @click="toggleMenu"
                >
                    <div class="flex items-center space-x-3">
                        <BarChart2 :size="18" />
                        <span>Data Entry</span>
                    </div>
                </router-link>
                <div class="pt-2 border-t border-border">
                    <template v-if="isAuthenticated">
                        <div class="px-2 py-1 text-sm text-muted-foreground">
                            {{ userEmail }}
                        </div>
                        <button
                            class="w-full flex items-center space-x-3 p-2 text-red-500 rounded-lg hover:bg-muted mt-2"
                            @click="handleLogout"
                        >
                            <LogOut :size="18" />
                            <span>Logout</span>
                        </button>
                    </template>
                    <template v-else>
                        <div class="flex flex-col space-y-3 pt-2">
                            <router-link
                                to="/login"
                                class="w-full p-2 text-center rounded-lg border border-border hover:bg-muted"
                                @click="toggleMenu"
                            >
                                Login
                            </router-link>
                            <router-link
                                to="/register"
                                class="btn-primary text-center"
                                @click="toggleMenu"
                            >
                                Register
                            </router-link>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { Menu, X, BarChart2, UserCircle, LogOut, ChevronDown } from 'lucide-vue-next';

// State for mobile menu and user dropdown
const isOpen = ref(false);
const userMenuOpen = ref(false);

// Toggle functions
const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};
const toggleUserMenu = () => {
    userMenuOpen.value = !userMenuOpen.value;
};

// Mock auth state (replace with real auth logic)
const isAuthenticated = computed(() => localStorage.getItem('xchange_token') !== null);
const userEmail = computed(() => localStorage.getItem('xchange_email') || 'user@example.com');

// Logout handler
const router = useRouter();
const handleLogout = () => {
    localStorage.removeItem('xchange_token');
    localStorage.removeItem('xchange_email');
    router.push('/login');
};

// Active route check
const route = useRoute();
const isActive = (path) => {
    return route.path === path;
};
</script>
