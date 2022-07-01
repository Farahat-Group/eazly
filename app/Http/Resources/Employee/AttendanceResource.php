<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "time_in" => $this->time_in,
            "time_out" => $this->time_out,
            'Employee_id' => $this->user->id ,
            "Employee_name" => $this->user->name,
            "date" => $this->created_at->format('Y-m-d'),
        ];
    }
}
