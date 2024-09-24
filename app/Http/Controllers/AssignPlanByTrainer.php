<?php

namespace App\Http\Controllers;

use App\Events\PlanAssigned;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Plan\AssignPlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\User;

class AssignPlanByTrainer extends Controller
{
    public function store(int $id, AssignPlanRequest $request)
    {
        $users = $request->users;
        $plan = Plan::find($id);

        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }

        collect($users)->each(function ($userId) use ($plan) {
            $user = User::find($userId);
            if ($user) {
                $alreadyAssigned = $user->plans()->where('plan_id', $plan->id)->exists();

                if (!$alreadyAssigned) {
                    $user->plans()->attach($plan);
                    event(new PlanAssigned($user));
                }
            }
        });

        return ResponseHelper::success(
            message: 'Plan assigned successfully',
            data: PlanResource::make($plan->load('workouts', 'trainer', 'users'))
        );
    }

    public function destroy(int $id, AssignPlanRequest $request)
    {
        $users = $request->users;
        $plan = Plan::find($id);

        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }

        collect($users)->each(function ($userId) use ($plan) {
            $user = User::find($userId);
            $user->plans()->detach($plan);
        });

        return ResponseHelper::success(
            message: 'Plan cancelled successfully',
        );
    }
}
