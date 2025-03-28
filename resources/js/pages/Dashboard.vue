<template>
    <main class="container mx-auto pt-24 pb-16 px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Currency Dashboard</h1>
                <p class="text-muted-foreground mt-1">
                    Monitor exchange rates and historical trends
                </p>
            </div>

            <div class="flex items-center mt-4 md:mt-0 space-x-4">
                <!-- Currency Selector -->
                <div class="relative">
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

                <!-- Date Picker -->
                <div class="relative">
                    <VueDatePicker
                        v-model="selectedDate"
                        :max-date="today"
                        :enable-time-picker="false"
                        input-class-name="input-field w-full"
                        placeholder="Select date"
                        aria-label="Select date"
                    >
                        <template #trigger>
                            <div class="relative">
                                <input
                                    :value="selectedDate ? formatDate(selectedDate) : ''"
                                    placeholder="Select date"
                                    class="input-field w-full cursor-pointer"
                                    readonly
                                />
                                <Calendar
                                    :size="18"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-muted-foreground"
                                />
                            </div>
                        </template>
                    </VueDatePicker>
                </div>

                <!-- Refresh Button -->
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

        <!-- Currency Card and Trend Chart -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="md:col-span-1">
                <CurrencyCard
                    :currency="selectedCurrency"
                    :base-currency="BASE_CURRENCY"
                    :rate="currentRate"
                    :weekly-average="weeklyAverage"
                />
            </div>
            <div class="md:col-span-2">
                <TrendChart
                    :data="historicalData"
                    :currency="selectedCurrency"
                    :base-currency="BASE_CURRENCY"
                    :is-loading="isLoading"
                />
            </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mt-4 text-red-500 text-center">
            {{ error }}
        </div>
    </main>
</template>

<script setup>
import {ref} from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import {ArrowDownUp, RefreshCw, Calendar} from 'lucide-vue-next';
import CurrencyCard from '../components/CurrencyCard.vue';
import TrendChart from '../components/TrendChart.vue';
import {useCurrencyData} from '../hooks/useCurrencyData';

// Sample currencies
const availableCurrencies = ['LKR', 'AUD', 'CAD', 'GBP'];
const BASE_CURRENCY = 'USD';

// State
const selectedCurrency = ref('LKR');
const today = new Date();
const selectedDate = ref(today); // Default to today

// Format date for display
const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return d.toISOString().split('T')[0]; // Format as YYYY-MM-DD
};

// Fetch data using the hook
const {isLoading, currentRate, historicalData, weeklyAverage, error, refetch} =
    useCurrencyData(selectedCurrency, selectedDate);
</script>
