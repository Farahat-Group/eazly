<?php

namespace App\Http\Resources\Manger;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResorce extends JsonResource
{

    public function toArray($request)
    {
        return [
            'company_name' => $this->company_name,
            'days_of_work' => (int) $this->days_of_work,
            'start_work_time' => $this->start_work_time,
            'end_work_time' => $this->end_work_time,
            'start_discount_time1' => $this->start_discount_time1,
            'start_discount_time2' => $this->start_discount_time2,
            'first_discount_rate1' => (double) $this->first_discount_rate1,
            'second_discount_rate2' => (double) $this->second_discount_rate2,
            'allowed_absence_days' => (int) $this->allowed_absence_days,
            'lat' => (double) $this->lat,
            'lon' => (double) $this->lon,

        ];
    }
}
