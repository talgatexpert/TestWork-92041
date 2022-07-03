<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DessertRequest extends FormRequest
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
            'name' => 'required',
            'calories' => 'required|regex:/^\d*(\.\d{2})?$/',
            'fat' => 'required|regex:/^\d*(\.\d{2})?$/',
            'carbs' => 'required|regex:/^\d*(\.\d{2})?$/',
            'protein' => 'required|regex:/^\d*(\.\d{2})?$/',
        ];
    }
}
