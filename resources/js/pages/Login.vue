<template>
    <div class="container mx-auto pt-24 px-4 flex flex-col items-center justify-center">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold mb-2">Welcome back</h1>
                <p class="text-muted-foreground">
                    Sign in to your account to continue
                </p>
            </div>

            <div class="card-glass p-8">
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-start">
                            <label for="email" class="text-sm font-medium">Email address</label>
                        </div>
                        <div class="relative">
                            <input
                                id="email"
                                type="email"
                                v-model="email"
                                class="input-field w-full pl-10"
                                placeholder="Enter your email"
                                required
                            />
                            <Mail
                                :size="18"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"
                            />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-medium">Password</label>
                            <router-link to="#" class="text-xs text-primary hover:underline">
                                Forgot password?
                            </router-link>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="password"
                                class="input-field w-full pl-10 pr-10"
                                placeholder="Enter your password"
                                required
                            />
                            <LockKeyhole
                                :size="18"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"
                            />
                            <button
                                type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground"
                                @click="showPassword = !showPassword"
                            >
                                <Eye v-if="showPassword" :size="18"/>
                                <EyeOff v-else :size="18"/>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="isLoading"
                        :class="[
                'btn-primary w-full mt-6',
                isLoading ? 'opacity-70 cursor-not-allowed' : ''
              ]"
                    >
                        {{ isLoading ? 'Signing in...' : 'Sign in' }}
                    </button>
                </form>

                <div class="mt-6 text-center text-sm">
                    <span class="text-muted-foreground">Don't have an account?</span>
                    <router-link to="/register" class="text-primary hover:underline font-medium">
                        Create account
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import {Eye, EyeOff, LockKeyhole, Mail} from 'lucide-vue-next';
import {useAuth} from '../hooks/useAuth';
import {useToast} from '../hooks/useToast';

const email = ref('');
const password = ref('');
const showPassword = ref(false);
const isLoading = ref(false);

const {login} = useAuth();
const {toast} = useToast();
const router = useRouter();

const handleSubmit = async () => {
    isLoading.value = true;

    try {
        await login(email.value, password.value);
        router.push('/dashboard');
    } catch (error) {
        toast({
            title: 'Login failed',
            description: 'Invalid email or password',
            variant: 'destructive',
        });
    } finally {
        isLoading.value = false;
    }
};
</script>
