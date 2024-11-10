<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workout\StoreWorkoutRequest;
use App\Http\Requests\Workout\UpdateWorkoutRequest;
use App\Http\Resources\WorkoutResource;
use App\Lib\Interfaces\IWorkoutRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class WorkoutController extends Controller
{
    public function __construct(protected IWorkoutRepository $workoutRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $workouts = $this->workoutRepository->getAllWorkouts();
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }
        return ResponseHelper::success(data: WorkoutResource::collection($workouts));
    }

    public function store(StoreWorkoutRequest $request): JsonResponse
    {
        try {
            $workout = $this->workoutRepository->createWorkout($request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: WorkoutResource::make($workout), statusCode: 201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $workout = $this->workoutRepository->getWorkoutById($id);
        } catch (ModelNotFoundException $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
            );
        }

        return ResponseHelper::success(data: WorkoutResource::make($workout));
    }

    public function update(int $id, UpdateWorkoutRequest $request): JsonResponse
    {
        try {
            $workout = $this->workoutRepository->updateWorkout($id, $request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: WorkoutResource::make($workout));
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->workoutRepository->deleteWorkout($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success();
    }
}
