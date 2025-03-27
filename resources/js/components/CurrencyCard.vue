<template>
    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">{{ currency }}/{{ baseCurrency }}</h3>
        </div>
        <div class="space-y-2">
            <div>
                <p class="text-sm text-muted-foreground">Current Rate</p>
                <p class="text-xl font-bold">{{ rate.toFixed(4) }}</p>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Weekly Average</p>
                <p class="text-sm font-bold">{{ weeklyAverage.toFixed(4) }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    currency: { type: String, required: true },
    baseCurrency: { type: String, required: true },
    rate: { type: Number, required: true },
    weeklyAverage: { type: Number, required: true },
});
</script>
