<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'gender' => $this->gender,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'birth_date' => $this->birth_date,
            'status' => $this->status,
            'weight' => $this->weight,
            'height' => $this->height,
            'about' => $this->about,
            'image' => ImageResource::make($this->whenLoaded('image')),
            'trainer' => UserResource::make($this->whenLoaded('trainer')),
            'roles' => $this->roles->map(function ($role) {
                return [
                    'role_name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                ];
            })->toArray(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'target_weight' => $this->target_weight,
            'ideal_weight' => $this->ideal_weight
        ];
    }
}
