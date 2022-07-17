<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Intervention\Validation\Rules\Creditcard;

class PaymentCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['required', Rule::in('credit', 'debit')],
            'holder' => 'required|string',
            'number' => ['required', 'unique:payment_cards,number', new Creditcard],
            'valid_to_month' => 'required|integer|between:1,12',
            'valid_to_year' => 'required|integer|gte:' . strval(now()->year),
            'cvv' => 'required|integer',
        ];
    }
}
