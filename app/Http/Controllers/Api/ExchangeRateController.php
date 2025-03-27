<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExchangeRateRequest;
use App\Http\Requests\GetExchangeRateRequest;
use App\Models\ExchangeRate;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ExchangeRateController extends Controller
{
    /**
     * Get exchange rates for a given currency and date.
     *
     * @param  GetExchangeRateRequest  $request
     * @return JsonResponse
     */
    public function getRates(GetExchangeRateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $currency = $validated['currency'];
        $date = isset($validated['date']) ? Carbon::parse($validated['date']) : Carbon::today();

        // Fetch the current rate for the specified date
        $currentRate = ExchangeRate::where('currency', $currency)
            ->whereDate('date', $date)
            ->first();

        if (!$currentRate) {
            return response()->errorMessage("No rate found for {$currency} on {$date->toDateString()}", JsonResponse::HTTP_NOT_FOUND);
        }

        // Fetch the last 7 days of rates (including the specified date)
        $startDate = $date->copy()->subDays(6); // 7 days total
        $last7Days = ExchangeRate::where('currency', $currency)
            ->whereBetween('date', [$startDate, $date])
            ->orderBy('date', 'asc')
            ->get(['date', 'rate'])
            ->map(fn($rate) => [
                'date' => $rate->date->toDateString(),
                'rate' => (float) $rate->rate,
            ]);

        // Calculate the weekly average
        $weeklyAverage = $last7Days->avg('rate');

        $responseData = [
            'current_rate' => (float) $currentRate->rate,
            'last_7_days' => $last7Days->toArray(),
            'weekly_average' => round($weeklyAverage, 4),
        ];

        return response()->success($responseData);
    }

    /**
     * Store a new exchange rate.
     *
     * @param StoreExchangeRateRequest $request
     * @return JsonResponse
     */
    public function storeRate(StoreExchangeRateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $date = Carbon::parse($data['date'])->toDateString(); // Normalize date

        try {
            ExchangeRate::upsert(
                [
                    [
                        'currency' => $data['currency'],
                        'date' => $date,
                        'rate' => $data['rate'],
                        'created_at' => now(), // Optional, only for new records
                        'updated_at' => now(), // Optional, Laravel handles this
                    ]
                ],
                ['currency', 'date'], // Unique columns
                ['rate', 'updated_at'] // Columns to update
            );

            return response()->successMessage(
                "Exchange rate for {$data['currency']} on {$date} stored successfully",
                JsonResponse::HTTP_CREATED
            );
        } catch (\Exception $e) {
            // Log the error and return a consistent error response
            \Log::error('Exchange rate storage error: ' . $e->getMessage());

            return response()->errorMessage(
                'Unable to store exchange rate',
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
