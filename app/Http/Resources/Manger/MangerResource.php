<?php

namespace App\Http\Resources\Manger;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MangerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $subscription = Carbon::createFromDate($this->code->end_time);
        $end = $subscription->diffForHumans();

        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "email" => $this->email,
            'subscription_ends_in' => $end,
            "token" => isset($this->api_token) ? $this->api_token : '',

        ];
    }
}
