<template>
    <main class="container mx-auto pt-24 pb-16 px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Currency Dashboard</h1>
                <p class="text-muted-foreground mt-1">
                    Monitor exchange rates and historical trends
                </p>
            </div>

            <div class="flex items-center mt-4 md:mt-0">
                <div class="relative mr-4">
                    <select
                        v-model="selectedCurrency"
                        class="input-field pr-10 pl-10 appearance-none"
                    >
                        <option v-for="currency in availableCurrencies" :key="currency" :value="currency">
                            {{ currency }}
                        </option>
                    </select>
                    <ArrowDownUp :size="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"/>
                </div>

                <button
                    @click="refetch"
                    class="btn-secondary flex items-center space-x-2"
                    :disabled="isLoading"
                >
                    <RefreshCw :size="16" :class="{ 'animate-spin': isLoading }"/>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        <!-- Currency Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <CurrencyCard
                v-for="currency in availableCurrencies"
                :key="currency"
                :currency="currency"
                :base-currency="BASE_CURRENCY"
                :rate="currentRates[currency] || 0"
                :change="changePercentages[currency] || 0"
                :weekly-average="weeklyAverages[currency] || 0"
            />
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 gap-6">
            <TrendChart
                :data="historicalData[selectedCurrency] || []"
                :currency="selectedCurrency"
                :base-currency="BASE_CURRENCY"
                :is-loading="isLoading"
            />
        </div>
    </main>
</template>

<script setup>
import {ref} from 'vue';
import {ArrowDownUp, RefreshCw} from 'lucide-vue-next';
import Navigation from '../components/Navigation.vue';
import CurrencyCard from '../components/CurrencyCard.vue';
import TrendChart from '../components/TrendChart.vue';
import {useCurrencyData} from '../hooks/useCurrencyData';

// Sample currencies
const availableCurrencies = ['LKR', 'AUD', 'CAD', 'GBP'];
const BASE_CURRENCY = 'USD';

// State
const selectedCurrency = ref('LKR');
const {isLoading, currentRates, historicalData, weeklyAverages, changePercentages, refetch} =
    useCurrencyData(availableCurrencies, BASE_CURRENCY);
</script>
