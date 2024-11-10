<?php

namespace App\Http\Controllers\API\Plan;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Lib\Interfaces\IPlanRepository;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function __construct(protected IPlanRepository $planRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $plans = $this->planRepository->getAllPlans();
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }

        return ResponseHelper::success(data: PlanResource::collection($plans));
    }

    public function store(StorePlanRequest $request): JsonResponse
    {
        try {
            $plan = $this->planRepository->createPlan($request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: PlanResource::make($plan));
    }

    public function show(int $id): JsonResponse
    {
        try {
            $plan = $this->planRepository->getPlanById($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: PlanResource::make($plan));
    }

    public function update(int $id, UpdatePlanRequest $request): JsonResponse
    {
        try {
            $plan = $this->planRepository->updatePlan($id, $request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: PlanResource::make($plan));
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->planRepository->deletePlan($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success();
    }
}
