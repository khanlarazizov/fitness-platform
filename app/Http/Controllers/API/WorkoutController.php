<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workout\StoreWorkoutRequest;
use App\Http\Requests\Workout\UpdateWorkoutRequest;
use App\Http\Resources\WorkoutResource;
use App\Lib\Interfaces\IWorkoutRepository;
use Illuminate\Support\Facades\Log;

class WorkoutController extends Controller
{
    public function __construct(protected IWorkoutRepository $workoutRepository)
    {
    }

    public function index()
    {
        $workouts = $this->workoutRepository->getAllWorkouts();
        if (!$workouts) {
            return ResponseHelper::error(message: 'Workouts could not found');
        }
        return ResponseHelper::success(
            message: 'Workouts found successfully',
            data: WorkoutResource::collection($workouts)
        );
    }

    public function store(StoreWorkoutRequest $request)
    {
        $workout = $this->workoutRepository->createWorkout($request->validated());
        if (!$workout) {
            return ResponseHelper::error(
                message: 'Workout could not be created',
                statusCode: 400
            );
        }
        return ResponseHelper::success(
            message: 'Workout created successfully',
            data: WorkoutResource::make($workout),
            statusCode: 201
        );
    }

    public function show(int $id)
    {
        $workout = $this->workoutRepository->getWorkoutById($id);
        if (!$workout) {
            return ResponseHelper::error(message: 'Workout could not found');
        }

        return ResponseHelper::success(
            message: 'Workout found successfully',
            data: WorkoutResource::make($workout)
        );
    }

    public function update(int $id, UpdateWorkoutRequest $request)
    {
        $workout = $this->workoutRepository->getWorkoutById($id);
        if (!$workout) {
            return ResponseHelper::error(message: 'Workout could not found');
        }

        try {
            $this->workoutRepository->updateWorkout($id, $request->validated());
        } catch (\Exception $exception) {
            Log::error('Workout could not be updated', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Workout could not be updated',
                statusCode: 400
            );
        }

        return ResponseHelper::success(message: 'Workout updated successfully');
    }

    public function destroy(int $id)
    {
        $workout = $this->workoutRepository->getWorkoutById($id);
        if (!$workout) {
            return ResponseHelper::error(message: 'Workout could not found');
        }

        $this->workoutRepository->deleteWorkout($id);
    }
}
