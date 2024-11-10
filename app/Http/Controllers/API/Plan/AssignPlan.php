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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AssignPlan extends Controller
{
    public function __construct(protected AssignPlanService $assignPlanService)
    {
    }

    public function store(Plan $plan, AssignPlanRequest $request): JsonResponse
    {
        try {
            $this->assignPlanService->assignPlan($plan, $request);
        } catch (\Exception $exception) {
            Log::error('Plan could not be assigned', [
                'plan_id' => $plan->id,
                'error' => $exception->getMessage()
            ]);
            return ResponseHelper::error(
                message: 'Plan could not be assigned',
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: PlanResource::make($plan));
    }

    public function destroy(Plan $plan, AssignPlanRequest $request): JsonResponse
    {
        try {
            $this->assignPlanService->cancelPlan($plan, $request);
        } catch (\Exception $exception) {
            Log::error('Plan could not be assigned', [
                'plan_id' => $plan->id,
                'error' => $exception->getMessage()
            ]);
            return ResponseHelper::error(
                message: 'Plan could not be assigned',
                statusCode: 400
            );
        }

        return ResponseHelper::success();
    }
}
