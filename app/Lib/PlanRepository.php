<?php

namespace App\Lib;

use App\Lib\Interfaces\IPlanRepository;
use App\Models\Plan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanRepository implements IPlanRepository
{

    public function getAllPlans(): Collection
    {
        return Plan::with('workouts')->get();
    }

    public function getPlanById(int $id): ?Plan
    {
        return Plan::find($id)->load('workouts');
    }

    public function createPlan(array $data): Plan
    {
        DB::beginTransaction();
        try {
            $data['trainer_id'] = auth()->id();
            $plan = Plan::create($data);
            $plan->workouts()->attach($data['workouts']);

            DB::commit();
            return $plan->load('workouts');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Plan could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('Plan could not be created');
        }
    }

    public function updatePlan(int $id, array $data): Plan
    {
        $plan = Plan::find($id);
        $plan->update($data);
        return $plan->load('workouts');
    }

    public function deletePlan(int $id)
    {
        $plan = Plan::find($id);

        DB::beginTransaction();
        try {
//            $plan->workouts()->detach();//TODO check it
            $plan->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Plan could not be deleted', ['error' => $exception->getMessage()]);
            throw new \Exception('Plan could not be deleted');
        }
    }
}
