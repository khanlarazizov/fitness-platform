<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'users'=>new UserResource($this->whenLoaded('users')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
