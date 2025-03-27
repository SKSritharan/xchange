import { ref } from 'vue';
import axios from 'axios';

export function useCurrencyData(currencies, baseCurrency) {
    const isLoading = ref(false);
    const currentRates = ref({});
    const historicalData = ref({});
    const weeklyAverages = ref({});
    const changePercentages = ref({});

    const fetchData = async () => {
        isLoading.value = true;
        try {
            const promises = currencies.map(async (currency) => {
                const response = await axios.get('/api/v1/exchange-rates', {
                    params: { currency, date: new Date().toISOString().split('T')[0] },
                });
                const data = response.data.data;
                return { currency, data };
            });

            const results = await Promise.all(promises);

            results.forEach(({ currency, data }) => {
                currentRates.value[currency] = data.current_rate;
                historicalData.value[currency] = data.last_7_days;
                weeklyAverages.value[currency] = data.weekly_average;

                // Calculate change percentage (mocked for now; adjust based on your API)
                const lastRate = data.last_7_days[data.last_7_days.length - 2]?.rate || data.current_rate;
                const change = ((data.current_rate - lastRate) / lastRate) * 100;
                changePercentages.value[currency] = change;
            });
        } catch (error) {
            console.error('Failed to fetch currency data:', error);
        } finally {
            isLoading.value = false;
        }
    };

    // Initial fetch
    fetchData();

    return {
        isLoading,
        currentRates,
        historicalData,
        weeklyAverages,
        changePercentages,
        refetch: fetchData,
    };
}
