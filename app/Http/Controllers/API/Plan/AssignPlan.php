<?php

namespace App\Http\Controllers\API\Plan;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;

class AssignPlan extends Controller
{
    public function store(int $id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }

        $user = auth()->user();
        $user->plans()->attach($plan);

        return ResponseHelper::success(
            message: 'Plan assigned successfully',
            data: PlanResource::make($plan)
        );
    }

    public function destroy(int $id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }

        $user = auth()->user();
        $user->plans()->detach($plan);

        return ResponseHelper::success(
            message: 'Plan cancelled successfully',
        );
    }
}
