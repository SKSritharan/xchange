<template>
    <div class="pt-20 px-4">
        <!-- Header Section -->
        <header class="max-w-6xl mx-auto py-20 md:py-32">
            <div class="text-center">
                <div class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                    Simplified Currency Exchange
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Track & Monitor <br class="hidden sm:block"/>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">
              Currency Exchange Rates
            </span>
                </h1>
                <p class="text-muted-foreground text-lg max-w-2xl mx-auto mb-8">
                    Real-time currency data with beautiful visualizations. <br class="hidden sm:block"/>
                    Stay updated with the latest exchange rates for global currencies.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button @click="navigateTo('/dashboard')"
                            class="btn-primary flex items-center justify-center gap-2">
                        <span>Go to Dashboard</span>
                        <ArrowRight :size="18"/>
                    </button>
                    <button @click="navigateTo('/register')" class="btn-secondary">
                        Create an Account
                    </button>
                </div>
            </div>
        </header>

        <!-- Key Features Section -->
        <section class="max-w-6xl mx-auto py-16 px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Key Features</h2>
                <p class="text-muted-foreground max-w-xl mx-auto">
                    Everything you need to stay on top of currency exchange rates
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="card-glass p-6 flex flex-col items-center text-center">
                    <div class="h-14 w-14 rounded-full bg-primary/10 flex items-center justify-center mb-5">
                        <LineChart :size="24" class="text-primary"/>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Trend Analysis</h3>
                    <p class="text-muted-foreground">
                        View 7-day trends and historical data to make informed decisions
                    </p>
                </div>

                <div class="card-glass p-6 flex flex-col items-center text-center">
                    <div class="h-14 w-14 rounded-full bg-secondary/10 flex items-center justify-center mb-5">
                        <Repeat :size="24" class="text-secondary"/>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Multiple Currencies</h3>
                    <p class="text-muted-foreground">
                        Support for LKR, AUD, CAD, GBP and more currencies
                    </p>
                </div>

                <div class="card-glass p-6 flex flex-col items-center text-center">
                    <div class="h-14 w-14 rounded-full bg-primary/10 flex items-center justify-center mb-5">
                        <Globe :size="24" class="text-primary"/>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Global Coverage</h3>
                    <p class="text-muted-foreground">
                        Exchange rates for major currencies from around the world
                    </p>
                </div>
            </div>
        </section>

        <!-- Real-Time Section -->
        <section class="max-w-6xl mx-auto py-16 px-4 mb-20">
            <div class="card-glass p-8 md:p-12 text-center">
                <div
                    class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-tr from-primary to-secondary mb-6">
                    <Clock :size="28" class="text-white"/>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Stay Updated in Real-Time</h2>
                <p class="text-muted-foreground max-w-2xl mx-auto mb-8">
                    Get access to the latest exchange rates and historical data. Perfect for businesses,
                    travelers, and finance professionals who need reliable currency information.
                </p>
                <button @click="navigateTo('/dashboard')" class="btn-primary">
                    Explore Dashboard
                </button>
            </div>
        </section>
    </div>
</template>

<script setup>
import {useRouter} from 'vue-router';
import {ArrowRight, Clock, Globe, LineChart, Repeat} from 'lucide-vue-next';

const router = useRouter();

const navigateTo = (path) => {
    router.push(path);
};
</script>
