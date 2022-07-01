<?php

namespace App\Http\Resources\Employee;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Jobs\JobResource;
use App\Models\Job;
use function Symfony\Component\String\b;

class ProfileResource extends JsonResource
{
    public function __construct($resource, $language = 'ar')
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->language = $language;

    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $job = Job::find($this->job_id);



        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "email" => $this->email,
            "salery" => isset($this->salery) ? $this->salery : '',
            "contract_start" => isset($this->contract_start) ? $this->contract_start : '',
            "contract_end" => isset($this->contract_end) ? $this->contract_end : '',
            "image" => isset($this->image) ? "https://eazily.mediaconsul.agency/public/images/employee/profile/".$this->image : '',
            "job_type" => strval($this->job_type),
            "job" => new JobResource($job, $this->language),
            'path' => $this->image
        ];
    }
}
