<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $baseUrl = config('services.exchange_rates.base_url');
        $apiKey = config('services.exchange_rates.api_key');
        $baseCurrency = 'USD';
        $currencies = ['LKR', 'AUD', 'CAD', 'GBP'];
        $startDate = Carbon::today()->subDays(90);

        // Loop through the last 90 days
        for ($i = 0; $i <= 90; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();

            $url = "{$baseUrl}/{$apiKey}/latest/{$baseCurrency}";

            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['result'] === 'success') {
                    $rates = $data['conversion_rates'];

                    foreach ($currencies as $currency) {
                        $rate = $rates[$currency] ?? null;

                        if ($rate) {
                            $variation = (rand(-100, 100) / 10000); // Small random adjustment (±0.01)
                            $historicalRate = round($rate + $variation, 4);

                            ExchangeRate::updateOrCreate(
                                [
                                    'currency' => $currency,
                                    'date' => $date,
                                ],
                                [
                                    'rate' => $historicalRate,
                                ]
                            );
                        }
                    }
                } else {
                    $this->command->error("API error for date {$date}: " . $data['error-type']);
                }
            } else {
                $this->command->error("Failed to fetch data for date {$date}: " . $response->status());
            }

            sleep(1);
        }
    }
}
