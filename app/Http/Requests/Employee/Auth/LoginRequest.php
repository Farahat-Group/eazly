<?php

namespace App\Http\Requests\Employee\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiTraits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    use ApiTraits;

    public function authorize()
    {
        return true;
    }






    public function rules()
    {
        return [
            "email" => "required|exists:users,email",
            "password" => "required",
        ];


    }



    protected function failedValidation(Validator $validator)
    {
        $er = [];
        $errors = $this->validator->errors();
        foreach($errors->all() as $error){
            array_push($er, $error);
        }
        throw new HttpResponseException(
            $this->responseValidationJsonFailed($er)
        );
    }
}
