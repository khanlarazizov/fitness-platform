<?php

namespace App\Services;

use App\Events\PlanAssigned;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Plan\AssignPlanRequest;
use App\Models\Plan;
use App\Models\User;

class AssignPlanService
{
    public function assignPlanByTrainer(Plan $plan, $request)
    {
        $users = $request->users;
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
    }

    public function cancelPlanByTrainer(Plan $plan, $request)
    {
        $users = $request->users;
        collect($users)->each(function ($userId) use ($plan) {
            $user = User::find($userId);
            $user->plans()->detach($plan);
        });
    }
}
