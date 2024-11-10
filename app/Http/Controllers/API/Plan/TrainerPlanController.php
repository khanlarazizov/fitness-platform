<?php

namespace App\Http\Controllers\API\Plan;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Lib\TrainerPlanRepository;
use Illuminate\Http\JsonResponse;

class TrainerPlanController extends Controller
{
    public function __construct(protected TrainerPlanRepository $trainerPlanRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $plans = $this->trainerPlanRepository->getAllTrainerPlans();
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }

        return ResponseHelper::success(
            data: PlanResource::collection($plans)
        );
    }

    public function store(StorePlanRequest $request): JsonResponse
    {
        try {
            $plan = $this->trainerPlanRepository->createPlan($request->all());
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
            $plan = $this->trainerPlanRepository->getTrainerPlanById($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: PlanResource::make($plan));
    }

    public function update(UpdatePlanRequest $request, int $id): JsonResponse
    {
        try {
            $plan = $this->trainerPlanRepository->updatePlan($id, $request->all());
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
            $this->trainerPlanRepository->deletePlan($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success();
    }
}
