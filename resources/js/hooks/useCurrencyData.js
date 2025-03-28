import { ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from './useToast';

export function useCurrencyData(selectedCurrency, date) {
    const isLoading = ref(false);
    const currentRate = ref(0);
    const weeklyAverage = ref(0);
    const historicalData = ref([]);
    const error = ref(null);

    const { toast } = useToast();

    const fetchData = async (currency, fetchDate) => {
        isLoading.value = true;
        error.value = null;

        try {
            // Format the date as DD-MM-YYYY
            const formatDateForApi = (d) => {
                const date = new Date(d);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            };

            const formattedDate = formatDateForApi(fetchDate);

            // Fetch data from the API
            const response = await axios.get(`http://xchange.test/api/v1/exchange-rates`, {
                params: {
                    currency: currency,
                    date: formattedDate,
                },
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('xchange_token')}`,
                },
            });

            const { data } = response.data;

            // Set the current rate
            currentRate.value = data.current_rate || 0;

            // Set the weekly average
            weeklyAverage.value = data.weekly_average || 0;

            // Set the historical data for the chart (last 7 days)
            // Filter out invalid entries
            historicalData.value = data.last_7_days
                .filter(entry => entry.rate != null && !isNaN(entry.rate))
                .map(entry => ({
                    date: entry.date,
                    rate: entry.rate,
                }));
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch currency data';
            console.error('Error fetching currency data:', err);

            currentRate.value = 0;
            weeklyAverage.value = 0;
            historicalData.value = [];

            toast({
                title: 'Error',
                description: error.value,
                variant: 'destructive',
            });
        } finally {
            isLoading.value = false;
        }
    };

    // Fetch data initially and whenever the selected currency or date changes
    watch([selectedCurrency, date], ([newCurrency, newDate]) => {
        if (newCurrency && newDate) {
            fetchData(newCurrency, newDate);
        }
    }, { immediate: true });

    // Refetch function to manually refresh data
    const refetch = () => {
        if (selectedCurrency.value && date.value) {
            fetchData(selectedCurrency.value, date.value);
        }
    };

    return {
        isLoading,
        currentRate,
        weeklyAverage,
        historicalData,
        error,
        refetch,
    };
}
