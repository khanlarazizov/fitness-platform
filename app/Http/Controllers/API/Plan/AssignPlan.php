<?php

namespace App\Http\Controllers\API\Plan;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;

class AssignPlan extends Controller
{
    public function store(Plan $plan)
    {
        $user = auth()->user();
        $user->plans()->attach($plan);

        return ResponseHelper::success(
            message: 'Plan assigned successfully',
            data: PlanResource::make($plan)
        );
    }

    public function destroy(Plan $plan)
    {
        $user = auth()->user();
        $user->plans()->detach($plan);

        return ResponseHelper::success(
            message: 'Plan cancelled successfully',
        );
    }
}
