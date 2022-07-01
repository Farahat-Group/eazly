<?php

namespace App\Http\Requests\Manger\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
            'start_work_time' => ['required'],
            'end_work_time' => 'required',
            'start_discount_time1' => 'required',
            'start_discount_time2' => 'required',
            'first_discount_rate1' => 'required',
            'second_discount_rate2' => 'required',
            'allowed_absence_days' => 'required',
            'lat' => 'required',
            'lon' => 'required',

        ];
    }
}
