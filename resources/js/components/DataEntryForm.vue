<template>
    <div class="card-glass p-6">
        <!-- Header with Info Tooltip -->
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-foreground">Add Exchange Rate</h2>
                <p class="text-muted-foreground text-sm mt-1">
                    Enter the latest exchange rate data
                </p>
            </div>
            <div class="relative group">
                <div class="bg-muted/50 rounded-full p-2 text-muted-foreground cursor-pointer">
                    <Info :size="20" />
                </div>
                <!-- Tooltip -->
                <div class="absolute right-0 top-10 w-64 p-3 bg-muted text-foreground text-sm rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                    <p>Ensure the rate is the midpoint value for the selected currency pair. Use four decimal places for precision. Rates are published immediately upon submission.</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-4">
            <!-- Currency Pair -->
            <div class="space-y-2">
                <label for="currency" class="text-sm font-medium text-foreground">Currency Pair</label>
                <select
                    id="currency"
                    v-model="form.currency"
                    class="input-field w-full"
                    required
                    :disabled="isSubmitting"
                    aria-label="Select currency pair"
                >
                    <option v-for="currency in currencies" :key="currency" :value="currency">
                        {{ currency }}/{{ baseCurrency }}
                    </option>
                </select>
            </div>

            <!-- Exchange Rate -->
            <div class="space-y-2">
                <label for="rate" class="text-sm font-medium text-foreground">Exchange Rate</label>
                <div class="relative">
                    <input
                        id="rate"
                        type="number"
                        v-model.number="form.rate"
                        placeholder="Enter rate (e.g., 0.1234)"
                        step="0.0001"
                        min="0.0001"
                        class="input-field w-full pr-16"
                        required
                        :disabled="isSubmitting"
                        aria-label="Enter exchange rate"
                        @input="validateRate"
                    />
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-muted-foreground">
                        {{ baseCurrency }}
                    </div>
                </div>
                <p v-if="rateError" class="text-sm text-red-500">{{ rateError }}</p>
            </div>

            <!-- Date Picker -->
            <div class="space-y-2">
                <label for="date" class="text-sm font-medium text-foreground">Date</label>
                <VueDatePicker
                    v-model="form.date"
                    :max-date="today"
                    :enable-time-picker="false"
                    :disabled="isSubmitting"
                    input-class-name="input-field w-full"
                    placeholder="Select date"
                    required
                    aria-label="Select date"
                    @update:model-value="validateDate"
                >
                    <template #trigger>
                        <div class="relative">
                            <input
                                :value="form.date ? formatDate(form.date) : ''"
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
                <p v-if="dateError" class="text-sm text-red-500">{{ dateError }}</p>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="isSubmitting"
                :class="[
          'btn-primary w-full mt-6',
          isSubmitting ? 'opacity-70 cursor-not-allowed' : ''
        ]"
            >
        <span v-if="isSubmitting" class="flex items-center justify-center">
          <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
          </svg>
          Submitting...
        </span>
                <span v-else>Submit Exchange Rate</span>
            </button>
        </form>

        <!-- Feedback Messages -->
        <div v-if="successMessage" class="mt-4 text-green-600 text-center">{{ successMessage }}</div>
        <div v-if="errorMessage" class="mt-4 text-red-500 text-center">{{ errorMessage }}</div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import { Calendar, Info } from 'lucide-vue-next';
import { useToast } from '../hooks/useToast';
import axios from 'axios';

// Props
const props = defineProps({
    currencies: { type: Array, required: true },
    baseCurrency: { type: String, required: true },
});

// State
const form = reactive({
    currency: props.currencies[0] || 'LKR',
    rate: null,
    date: new Date(), // VueDatePicker expects a Date object
});
const today = new Date();
const isSubmitting = ref(false);
const rateError = ref('');
const dateError = ref('');
const successMessage = ref('');
const errorMessage = ref('');

const { toast } = useToast();

// Format Date for Display and API Submission
const formatDate = (date) => {
    if (!date) return '';
    try {
        const d = new Date(date);
        if (isNaN(d.getTime())) throw new Error('Invalid date');
        return d.toISOString().split('T')[0]; // Format as YYYY-MM-DD
    } catch (error) {
        console.error('Date formatting error:', error);
        return '';
    }
};

// Validation Functions
const validateRate = () => {
    rateError.value = '';
    if (form.rate === null || form.rate === '') {
        rateError.value = 'Exchange rate is required';
    } else if (form.rate <= 0) {
        rateError.value = 'Please enter a positive rate value';
    } else if (form.rate < 0.0001) {
        rateError.value = 'Rate must be at least 0.0001';
    } else if (!/^\d*\.?\d{0,4}$/.test(form.rate.toString())) {
        rateError.value = 'Use up to four decimal places for precision';
    }
};

const validateDate = () => {
    dateError.value = '';
    if (!form.date) {
        dateError.value = 'Please select a date';
        return;
    }
    const selectedDate = new Date(form.date);
    if (isNaN(selectedDate.getTime())) {
        dateError.value = 'Invalid date selected';
        form.date = today;
        return;
    }
    if (selectedDate > today) {
        dateError.value = 'Date cannot be in the future';
        form.date = today;
    }
};

// Watch for changes in props.currencies to update form.currency
watch(() => props.currencies, (newCurrencies) => {
    if (newCurrencies && newCurrencies.length > 0 && !newCurrencies.includes(form.currency)) {
        form.currency = newCurrencies[0];
    }
});

// Watch form fields to trigger validation on change
watch(() => form.rate, () => {
    validateRate();
});

watch(() => form.date, () => {
    validateDate();
});

// Form Submission
const handleSubmit = async () => {

    // Reset feedback messages
    successMessage.value = '';
    errorMessage.value = '';

    // Validate before submission
    validateRate();
    validateDate();

    if (rateError.value || dateError.value) {
        console.log('Validation errors:', { rateError: rateError.value, dateError: dateError.value });
        return;
    }

    isSubmitting.value = true;

    try {
        const payload = {
            currency: form.currency,
            rate: parseFloat(form.rate.toFixed(4)), // Ensure 4 decimal places
            date: formatDate(form.date),
        };

        const response = await axios.post('/api/v1/exchange-rates', payload, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('xchange_token')}`,
            },
        });

        successMessage.value = response.data.message || 'Exchange rate data has been submitted';
        toast({
            title: 'Success',
            description: successMessage.value,
        });
        form.rate = null;
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Failed to submit exchange rate data';
        toast({
            title: 'Error',
            description: errorMessage.value,
            variant: 'destructive',
        });
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>

</style>
