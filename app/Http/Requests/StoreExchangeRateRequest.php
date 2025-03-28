<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExchangeRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'currency' => 'required|string|size:3|in:LKR,AUD,CAD,GBP',
            'rate' => 'required|numeric|min:0',
            'date' => 'required|date|before_or_equal:today',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'currency.required' => 'The currency is required.',
            'currency.size' => 'The currency must be exactly 3 characters.',
            'currency.in' => 'The currency must be one of: LKR, AUD, CAD, GBP.',
            'rate.required' => 'The exchange rate is required.',
            'rate.numeric' => 'The exchange rate must be a number.',
            'rate.min' => 'The exchange rate cannot be negative.',
            'date.required' => 'The date is required.',
            'date.date' => 'The date must be a valid date.',
            'date.before_or_equal' => 'The date cannot be in the future.',
        ];
    }
}
