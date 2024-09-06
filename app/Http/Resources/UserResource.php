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
            'email' => $this->email,
            'status' => $this->status->value,
            'image' => new ImageResource($this->whenLoaded('image')),
            'trainer' => new UserResource($this->whenLoaded('trainer')),
            'roles' => $this->roles->pluck('name')->toArray() ?? [],
            'permissions' => $this->permissions->pluck('name')->toArray() ?? [],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
