<?php

namespace App\Http\Requests\Employee\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiTraits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    use ApiTraits;

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
            "name" => "required",
            "email" => "required|unique:users,email",
            "phone" => "required|unique:users,phone",
            "password" => "required",
            "job_id" => "required|exists:jobs,id",
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
