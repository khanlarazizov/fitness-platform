<?php

namespace App\Lib;

use App\Models\Plan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TrainerPlanRepository extends PlanRepository
{
    public function getAllTrainerPlans(): Collection
    {
        return Plan::where('trainer_id', auth()->id())
            ->with('workouts', 'trainer')
            ->withCount('users', 'workouts')
            ->get();
    }

    public function getTrainerPlanById(int $id): ?Plan
    {
        try {
            return Plan::with('workouts', 'trainer')
                ->where('trainer_id', auth()->id())
                ->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            Log::error('Plan not found', ['plan_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Plan not found');
        }
    }
}
