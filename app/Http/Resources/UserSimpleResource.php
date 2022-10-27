<?php

namespace App\Http\Resources;

use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rawResponse = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'language' => $this->language,
            'profile_picture' => ResponseService::urlPathStorage($this->profile_picture),
        ];
        return $rawResponse;
    }
}
