<?php

namespace App\Http\Requests\admin\students_numbers_cost;

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
            'cost'         => 'required',
            'min'          => 'required|integer',
            'max'          => 'required|integer',
        ];
    }
}
