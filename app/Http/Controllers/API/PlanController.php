<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Lib\Interfaces\IPlanRepository;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function __construct(protected IPlanRepository $planRepository)
    {
    }

    public function index()
    {
        $plans = $this->planRepository->getAllPlans();
        if (!$plans) {
            return ResponseHelper::error(message: 'Plans could not found');
        }

        return ResponseHelper::success(
            message: 'Plans found successfully',
            data: PlanResource::collection($plans)
        );
    }

    public function store(StorePlanRequest $request)
    {
        try {
            $plan = $this->planRepository->createPlan($request->validated());
        } catch (\Exception $exception) {
            Log::error('Plan could not be created', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Plan could not be created',
                statusCode: 400
            );
        }

        return ResponseHelper::success(
            message: 'Plan created successfully',
            data: PlanResource::make($plan)
        );
    }

    public function show(int $id)
    {
        $plan = $this->planRepository->getPlanById($id);
        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }

        return ResponseHelper::success(
            message: 'Plan found successfully',
            data: PlanResource::make($plan)
        );
    }

    public function update(int $id, UpdatePlanRequest $request)
    {
        $plan = $this->planRepository->getPlanById($id);

        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }

        try {
            $plan = $this->planRepository->updatePlan($id, $request->validated());
        } catch (\Exception $exception) {
            Log::error('Plan could not be updated', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Plan could not be updated',
                statusCode: 400
            );
        }

        return ResponseHelper::success(
            message: 'Plan updated successfully',
            data: PlanResource::make($plan)
        );
    }

    public function destroy(int $id)
    {
        $plan = $this->planRepository->getPlanById($id);
        if (!$plan) {
            return ResponseHelper::error(message: 'Plan could not found');
        }
        $this->planRepository->deletePlan($id);
    }
}
