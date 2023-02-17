<?php

namespace App\Http\Requests\admin\promocode;

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
            'code'              => 'required|string|unique:promo_codes,code,' . $this->id,
            'percentage'        => 'required|integer|min:0|max:100',
            'expiration'        => 'required',
        ];
    }
}
