<template>
    <div class="container mx-auto pt-24 px-4 flex flex-col items-center justify-center">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold mb-2">Create an account</h1>
                <p class="text-muted-foreground">
                    Sign up to start tracking currency exchange rates
                </p>
            </div>

            <div class="card-glass p-8">
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-start">
                            <label for="name" class="text-sm font-medium">Full name</label>
                        </div>
                        <div class="relative">
                            <input
                                id="name"
                                type="text"
                                v-model="name"
                                class="input-field w-full pl-10"
                                placeholder="Enter your name"
                                required
                            />
                            <User
                                :size="18"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"
                            />
                        </div>
                    </div>

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
                        <div class="flex items-center justify-start">
                            <label for="password" class="text-sm font-medium">Password</label>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="password"
                                class="input-field w-full pl-10 pr-10"
                                placeholder="Create a password"
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

                    <div class="space-y-2">
                        <div class="flex items-center justify-start">
                            <label for="confirmPassword" class="text-sm font-medium">Confirm password</label>
                        </div>
                        <div class="relative">
                            <input
                                id="confirmPassword"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="confirmPassword"
                                class="input-field w-full pl-10"
                                placeholder="Confirm your password"
                                required
                            />
                            <LockKeyhole
                                :size="18"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"
                            />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="isLoading"
                            :class="[
                  'btn-primary w-full',
                  isLoading ? 'opacity-70 cursor-not-allowed' : ''
                ]"
                        >
                            {{ isLoading ? 'Creating account...' : 'Create account' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-sm">
                    <span class="text-muted-foreground">Already have an account?</span>
                    <router-link to="/login" class="text-primary hover:underline font-medium">
                        Sign in
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import {Eye, EyeOff, LockKeyhole, Mail, User} from 'lucide-vue-next';
import {useAuth} from '../hooks/useAuth';
import {useToast} from '../hooks/useToast';

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const showPassword = ref(false);
const isLoading = ref(false);

const {register} = useAuth();
const {toast} = useToast();
const router = useRouter();

const handleSubmit = async () => {
    if (password.value !== confirmPassword.value) {
        toast({
            title: "Passwords don't match",
            description: 'Please make sure your passwords match',
            variant: 'destructive',
        });
        return;
    }

    isLoading.value = true;

    try {
        await register(name.value, email.value, password.value);
        toast({
            title: 'Account created',
            description: 'Your account has been created successfully',
        });
        router.push('/dashboard');
    } catch (error) {
        toast({
            title: 'Registration failed',
            description: 'An error occurred during registration',
            variant: 'destructive',
        });
    } finally {
        isLoading.value = false;
    }
};
</script>
