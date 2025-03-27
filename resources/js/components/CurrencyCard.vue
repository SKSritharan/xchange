<template>
    <div class="card-glass p-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-foreground">{{ currency }}/{{ baseCurrency }}</h3>
            <span :class="change >= 0 ? 'currency-trend-up' : 'currency-trend-down'" class="text-sm">
        {{ change >= 0 ? '+' : '' }}{{ change.toFixed(2) }}%
      </span>
        </div>
        <p class="text-2xl currency-value text-foreground mt-2">{{ rate.toFixed(4) }}</p>
        <p class="text-sm text-muted-foreground">Weekly Avg: {{ weeklyAverage.toFixed(4) }}</p>
        <p class="text-xs text-muted-foreground mt-1">~ 7-day trend</p>
    </div>
</template>

<script setup>
defineProps({
    currency: { type: String, required: true },
    baseCurrency: { type: String, required: true },
    rate: { type: Number, required: true },
    change: { type: Number, required: true },
    weeklyAverage: { type: Number, required: true },
});
</script>
