<?php

namespace App\Lib;

use App\Lib\Interfaces\IPlanRepository;
use App\Models\Plan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanRepository implements IPlanRepository
{
    public function getAllPlans(array $data): LengthAwarePaginator
    {
        return Plan::with('workouts', 'trainer')
            ->name($data['name'])
            ->sortBy($data['sort_by'], $data['direction'])
            ->status($data['status'])
            ->trainer($data['trainer_id'])
            ->withCount('users', 'workouts')
            ->paginate(10);
    }

    public function getPlanById(int $id): ?Plan
    {
        try {
            return Plan::with('workouts', 'trainer')
                ->withCount('users', 'workouts')
                ->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            Log::error('Plan not found', ['plan_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Plan not found');
        }
    }

    public function createPlan(array $data): Plan
    {
        try {
            DB::beginTransaction();
            $data['trainer_id'] = auth()->id();
            $plan = Plan::create($data);
            $plan->workouts()->attach($data['workouts']);
            DB::commit();
            return $plan;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Plan could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('Plan could not be created');
        }
    }

    public function updatePlan(int $id, array $data): Plan
    {
        try {
            DB::beginTransaction();
            $plan = Plan::with('workouts', 'trainer')->findOrFail($id);
            $data['trainer_id'] = auth()->id();
            $plan->update($data);
            $plan->workouts()->sync($data['workouts']);
            DB::commit();
            return $plan;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('Plan not found', ['plan_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Plan not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Plan could not be created', ['plan_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('Plan could not be created');
        }
    }

    public function deletePlan(int $id): void
    {
        try {
            DB::beginTransaction();
            $plan = Plan::findOrFail($id);
            $plan->delete();
            $plan->workouts()->detach();
            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('Plan not found', ['plan_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Plan not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Plan could not be deleted', ['plan_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('Plan could not be deleted');
        }
    }
}
