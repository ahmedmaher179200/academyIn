<?php

namespace App\Http\Requests\Api\students;

use App\Traits\RequestForApi;
use App\Traits\response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    use RequestForApi;
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
            'phone'             => 'required|string',
            'password'          => 'required|string',
            'token_firebase'    => 'nullable|string',
        ];
    }
}
