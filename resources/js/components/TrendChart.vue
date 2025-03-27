<template>
    <div class="card-glass p-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">
            {{ currency }}/{{ baseCurrency }} 7-Day Trend
        </h3>
        <div v-if="isLoading" class="text-center text-muted-foreground py-10">
            Loading chart...
        </div>
        <canvas v-else ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

const props = defineProps({
    data: { type: Array, required: true },
    currency: { type: String, required: true },
    baseCurrency: { type: String, required: true },
    isLoading: { type: Boolean, required: true },
});

const chartCanvas = ref(null);
let chartInstance = null;

const renderChart = () => {
    if (chartInstance) chartInstance.destroy();
    const ctx = chartCanvas.value.getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: props.data.map(item => item.date),
            datasets: [
                {
                    label: `${props.currency}/${props.baseCurrency}`,
                    data: props.data.map(item => item.rate),
                    borderColor: 'hsl(var(--primary))',
                    backgroundColor: 'rgba(30, 144, 255, 0.1)',
                    fill: true,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: false, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                x: { grid: { display: false } },
            },
        },
    });
};

watch(() => props.data, () => {
    if (!props.isLoading && props.data.length > 0) {
        renderChart();
    }
});

onMounted(() => {
    if (!props.isLoading && props.data.length > 0) {
        renderChart();
    }
});
</script>
