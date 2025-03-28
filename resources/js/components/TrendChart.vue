<template>
    <div class="card-glass p-6">
        <h3 class="text-lg font-semibold mb-4">
            {{ currency }}/{{ baseCurrency }} Trend (Last 7 Days)
        </h3>
        <div v-if="isLoading" class="flex justify-center items-center h-64">
            <svg class="animate-spin h-8 w-8 text-primary" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
        </div>
        <div v-else-if="data.length === 0" class="text-center text-muted-foreground h-64 flex items-center justify-center">
            No data available for {{ currency }}.
        </div>
        <LineChart v-else :data="chartData" :options="chartOptions" class="h-64"/>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Line as LineChart } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
} from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale);

const props = defineProps({
    data: { type: Array, required: true },
    currency: { type: String, required: true },
    baseCurrency: { type: String, required: true },
    isLoading: { type: Boolean, required: true },
});

// Chart data
const chartData = computed(() => ({
    labels: props.data.map(item => item.date),
    datasets: [
        {
            label: `${props.currency}/${props.baseCurrency}`,
            data: props.data.map(item => item.rate),
            borderColor: '#1E90FF',
            backgroundColor: '#32CD32',
            fill: true,
            tension: 0.3,
        },
    ],
}));

// Dynamic Y-axis scaling based on data
const yAxisRange = computed(() => {
    const rates = props.data.map(item => item.rate);
    const minRate = Math.min(...rates);
    const maxRate = Math.max(...rates);
    const padding = (maxRate - minRate) * 0.1 || 0.01; // Add 10% padding, or 0.01 if range is 0
    return {
        min: minRate - padding,
        max: maxRate + padding,
    };
});

// Chart options
const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        x: {
            title: {
                display: true,
                text: 'Date',
            },
        },
        y: {
            title: {
                display: true,
                text: 'Exchange Rate',
            },
            min: yAxisRange.value.min,
            max: yAxisRange.value.max,
            ticks: {
                stepSize: (yAxisRange.value.max - yAxisRange.value.min) / 5, // Adjust step size for better readability
            },
        },
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: (context) => {
                    const rate = context.parsed.y;
                    return `${props.currency}/${props.baseCurrency}: ${rate.toFixed(4)}`;
                },
            },
        },
    },
}));
</script>
