<?php

namespace App\Http\Requests\admin\terms;

use Illuminate\Foundation\Http\FormRequest;

class add extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'terms.*.name' => 'required|string|min:2',
            'year_id'     => 'required|exists:years,id',
        ];
    }
}
