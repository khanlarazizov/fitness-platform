<?php

namespace App\Http\Controllers\API\Plan;

use App\Events\PlanAssigned;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\AssignPlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\User;
use App\Services\AssignPlanService;

class AssignPlanByTrainer extends Controller
{
    public function __construct(protected AssignPlanService $assignPlanService)
    {
    }

    public function store(Plan $plan, AssignPlanRequest $request)
    {
        $this->assignPlanService->assignPlanByTrainer($plan, $request);

        return ResponseHelper::success(
            message: 'Plan assigned successfully',
            data: PlanResource::make($plan->load('workouts', 'trainer', 'users'))
        );
    }

    public function destroy(Plan $plan, AssignPlanRequest $request)
    {
        $this->assignPlanService->cancelPlanByTrainer($plan, $request);

        return ResponseHelper::success(
            message: 'Plan cancelled successfully',
        );
    }
}
