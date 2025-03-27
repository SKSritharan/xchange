<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800">Exchange Rate Dashboard</h2>

        <!-- Filters -->
        <div class="flex space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Currency</label>
                <select v-model="currency" @change="fetchRates" class="mt-1 block w-full border rounded-md p-2">
                    <option value="LKR">LKR</option>
                    <option value="AUD">AUD</option>
                    <option value="CAD">CAD</option>
                    <option value="GBP">GBP</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" v-model="date" @change="fetchRates" class="mt-1 block w-full border rounded-md p-2" :max="today" />
            </div>
        </div>

        <!-- Loading/Error State -->
        <div v-if="loading" class="text-center text-gray-500">Loading...</div>
        <div v-if="error" class="text-red-500">{{ error }}</div>

        <!-- Rates Display -->
        <div v-if="rates" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Current Rate -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-medium text-gray-700">Current Rate</h3>
                <p class="text-3xl font-bold text-blue-600">{{ rates.current_rate.toFixed(4) }}</p>
                <p class="text-sm text-gray-500">USD vs {{ currency }} on {{ selectedDate }}</p>
            </div>

            <!-- Weekly Average -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-medium text-gray-700">Weekly Average</h3>
                <p class="text-3xl font-bold text-green-600">{{ rates.weekly_average.toFixed(4) }}</p>
            </div>

            <!-- 7-Day Trend -->
            <div class="bg-white p-6 rounded-lg shadow-md col-span-1 md:col-span-3">
                <h3 class="text-lg font-medium text-gray-700">Last 7 Days</h3>
                <canvas ref="chartCanvas" class="mt-4"></canvas>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

const currency = ref('LKR');
const date = ref(new Date().toISOString().split('T')[0]); // Default to today
const today = new Date().toISOString().split('T')[0]; // Max date
const rates = ref(null);
const loading = ref(false);
const error = ref(null);
const chartCanvas = ref(null);
let chartInstance = null;

const selectedDate = ref(date.value);

const fetchRates = async () => {
    loading.value = true;
    error.value = null;
    selectedDate.value = date.value;

    try {
        const response = await axios.get('/api/v1/exchange-rates', {
            params: { currency: currency.value, date: date.value },
        });
        rates.value = response.data.data;

        // Update chart
        if (chartInstance) chartInstance.destroy();
        const ctx = chartCanvas.value.getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rates.value.last_7_days.map(day => day.date),
                datasets: [{
                    label: `USD vs ${currency.value}`,
                    data: rates.value.last_7_days.map(day => day.rate),
                    borderColor: '#1E90FF',
                    fill: false,
                }],
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: false } },
            },
        });
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to fetch rates';
    } finally {
        loading.value = false;
    }
};

onMounted(fetchRates);
</script>
