<?php

namespace App\Http\Resources;

use App\Models\Story;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'profile_picture' => ResponseService::urlPathStorage($this->profile_picture),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'role' => $this->role,
            'gender' => $this->gender,
            'address' => $this->address,
            'city' => $this->city,
            'birthdate' => $this->birthdate,
            'referral_code' => $this->referral_code,
            'count_referral' => $this->count_referral,
            'language' => $this->language,
            'peduli_lindungi' => new PeduliLindungiResource($this->peduliLindungi),
            'total_like' => count($this->storyReactions),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
