<?php

namespace App\Http\Resources\Jobs;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function __construct($resource, $language = 'ar')
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->language = $language;

    }

    public function toArray($request)
    {
        $name = "name_".$this->language;

        return [
            "id" => $this->id,
            "name" => $this->$name,
        ];
    }
}
