<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'location' => 'required|string',
            'provenance' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'type' => 'required|string',
            'floor' => 'required|string',
            'with_elevator' => 'required|boolean',
            'size' => 'required|numeric'
        ];
    }
}
