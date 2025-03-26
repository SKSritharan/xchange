<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExchangeRateRequest;
use App\Http\Requests\UpdateExchangeRateRequest;
use App\Models\ExchangeRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ExchangeRateController extends Controller
{
    /**
     * Get exchange rates for a given currency and date.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRates(Request $request): JsonResponse
    {
        // Validate query parameters
        $validator = \Validator::make($request->all(), [
            'currency' => 'required|string|size:3|in:LKR,AUD,CAD,GBP',
            'date' => 'nullable|date|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->errorMessage($validator->errors()->first(), JsonResponse::HTTP_BAD_REQUEST);
        }

        $currency = $request->input('currency');
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Fetch the current rate for the specified date
        $currentRate = ExchangeRate::where('currency', $currency)
            ->where('date', $date)
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
                'rate' => (float) $rate->rate, // Ensure 4 decimal places via cast in model
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
        $data = $request->all();
        $data['date'] = Carbon::parse($data['date'])->toDateString();

        // Store or update the exchange rate
        $exchangeRate = ExchangeRate::updateOrCreate(
            [
                'currency' => $data['currency'],
                'date' => $data['date'],
            ],
            [
                'rate' => $data['rate'],
            ]
        );

        return response()->successMessage("Exchange rate for {$data['currency']} on {$data['date']} saved successfully", JsonResponse::HTTP_CREATED);
    }
}
