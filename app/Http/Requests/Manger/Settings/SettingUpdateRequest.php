<?php

namespace App\Http\Requests\Manger\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingUpdateRequest extends FormRequest
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
            'days_of_work' => 'nullable|max:2',
            'start_work_time' => ['nullable'],
            'end_work_time' => 'nullable',
            'start_discount_time1' => 'nullable',
            'start_discount_time2' => 'nullable',
            'first_discount_rate1' => 'nullable',
            'second_discount_rate2' => 'nullable',
            'allowed_absence_days' => 'nullable',
            'lat' => 'nullable',
            'lon' => 'nullable',

        ];
    }
}
