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
                        :aria-current="isActive('/dashboard') ? 'page' : undefined"
                    >
                        Dashboard
                    </router-link>
                    <router-link
                        v-if="authStore.isAuthenticated"
                        to="/data-entry"
                        :class="[
              'font-medium transition-colors',
              isActive('/data-entry') ? 'text-primary' : 'text-foreground hover:text-primary'
            ]"
                        :aria-current="isActive('/data-entry') ? 'page' : undefined"
                    >
                        Data Entry
                    </router-link>
                </div>

                <!-- User Section (Desktop) -->
                <div class="hidden md:flex items-center space-x-4">
                    <template v-if="authStore.isAuthenticated">
                        <div class="relative" ref="userMenu">
                            <button
                                @click="toggleUserMenu"
                                class="flex items-center space-x-2 rounded-full bg-muted px-4 py-2 transition-colors hover:bg-muted/80"
                                aria-haspopup="true"
                                :aria-expanded="userMenuOpen"
                            >
                                <UserCircle :size="18" />
                                <span class="max-w-[100px] truncate text-sm">{{ authStore.userEmail || 'User' }}</span>
                                <ChevronDown :size="16" :class="{ 'rotate-180': userMenuOpen }" />
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
                            <router-link
                                to="/login"
                                class="text-foreground hover:text-primary transition-colors"
                                :aria-current="isActive('/login') ? 'page' : undefined"
                            >
                                Login
                            </router-link>
                            <router-link
                                to="/register"
                                class="btn-primary"
                                :aria-current="isActive('/register') ? 'page' : undefined"
                            >
                                Register
                            </router-link>
                        </div>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden" @click="toggleMenu" aria-label="Toggle menu">
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
                    :aria-current="isActive('/dashboard') ? 'page' : undefined"
                >
                    <div class="flex items-center space-x-3">
                        <BarChart2 :size="18" />
                        <span>Dashboard</span>
                    </div>
                </router-link>
                <router-link
                    v-if="authStore.isAuthenticated"
                    to="/data-entry"
                    :class="[
            'font-medium p-2 rounded-lg',
            isActive('/data-entry') ? 'bg-muted text-primary' : 'text-foreground hover:bg-muted'
          ]"
                    :aria-current="isActive('/data-entry') ? 'page' : undefined"
                >
                    <div class="flex items-center space-x-3">
                        <Edit :size="18" />
                        <span>Data Entry</span>
                    </div>
                </router-link>
                <div class="pt-2 border-t border-border">
                    <template v-if="authStore.isAuthenticated">
                        <div class="px-2 py-1 text-sm text-muted-foreground">
                            {{ authStore.userEmail || 'User' }}
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
                                :aria-current="isActive('/login') ? 'page' : undefined"
                            >
                                Login
                            </router-link>
                            <router-link
                                to="/register"
                                class="btn-primary text-center"
                                :aria-current="isActive('/register') ? 'page' : undefined"
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
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { Menu, X, BarChart2, Edit, UserCircle, LogOut, ChevronDown } from 'lucide-vue-next';
import { useAuthStore } from '../stores/auth';

// State for mobile menu and user dropdown
const isOpen = ref(false);
const userMenuOpen = ref(false);

// Auth state from Pinia store
const authStore = useAuthStore();

// Toggle functions
const toggleMenu = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        userMenuOpen.value = false; // Close user menu when opening mobile menu
    }
};

const toggleUserMenu = () => {
    userMenuOpen.value = !userMenuOpen.value;
    if (userMenuOpen.value) {
        isOpen.value = false; // Close mobile menu when opening user menu
    }
};

// Click outside handler for user menu
const userMenu = ref(null);
const closeUserMenuOnClickOutside = (event) => {
    if (userMenu.value && !userMenu.value.contains(event.target)) {
        userMenuOpen.value = false;
    }
};

// Router and route for navigation
const router = useRouter();
const route = useRoute();

// Active route check
const isActive = (path) => {
    return route.path === path;
};

// Logout handler
const handleLogout = async () => {
    console.log('logout');
    try {
        await authStore.logout();
        isOpen.value = false; // Close mobile menu after logout
        userMenuOpen.value = false; // Close user menu after logout
    } catch (error) {
        console.error('Logout error:', error);
    }
};

// Automatically close mobile menu after navigation
router.afterEach(() => {
    isOpen.value = false;
});

// Add event listeners for click outside
onMounted(() => {
    document.addEventListener('click', closeUserMenuOnClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', closeUserMenuOnClickOutside);
});
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
