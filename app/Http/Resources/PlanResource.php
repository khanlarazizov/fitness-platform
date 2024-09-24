<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'description' => $this->description,
            'user_count' => $this->when(isset($this->users_count), $this->users_count),
            'workout_count' => $this->when(isset($this->workouts_count), $this->workouts_count),
            'workouts' => WorkoutResource::collection($this->whenLoaded('workouts')),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
