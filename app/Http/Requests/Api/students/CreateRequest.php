<?php

namespace App\Http\Requests\Api\students;

use App\Traits\RequestForApi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'username'         => 'required|string|min:3|max:255',
            'dialing_code'     => 'required|string',
            'phone'            => 'required|unique:students|string',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'country_id'       => 'required|exists:countries,id',
            'curriculum_id'    => 'required|exists:curriculums,id',
            'gender'           => ['required',Rule::in(0,1)],//0->male  1->female
            'token_firebase'   => 'nullable|string',
        ];
    }
}
