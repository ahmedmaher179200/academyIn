<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RequestForApi
{
    use response;
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->faild($validator->errors()->first(), 403, 'E03'));
    }
}