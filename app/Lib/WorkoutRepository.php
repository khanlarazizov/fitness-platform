<?php

namespace App\Lib;

use App\Helpers\UploadHelper;
use App\Lib\Interfaces\IWorkoutRepository;
use App\Models\Workout;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkoutRepository implements IWorkoutRepository
{

    public function getAllWorkouts(): Collection
    {
        return Workout::with('image', 'category')->get();
    }

    public function getWorkoutById(int $id): ?Workout
    {
        $workout = Workout::with('image', 'category')->findOrFail($id);
        return $workout;
    }

    public function createWorkout(array $data): Workout
    {
        try {
            DB::beginTransaction();
            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;

            $workout = Workout::create($data);
            $workout->image()->create(['name' => $file]);
            DB::commit();
            return $workout->load('image', 'category');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Workout could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('Workout could not be created');
        }
    }

    public function updateWorkout(int $id, array $data): Workout
    {
        DB::beginTransaction();

        try {
            $workout = Workout::with('image', 'category')->findOrFail($id);

            if (isset($data['file'])) {
                $file = UploadHelper::updateFile($data['file'], $workout);
                $workout->image()->updateOrCreate(['name' => $file]);
            }

            $workout->update($data);

            DB::commit();
            return $workout;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('Workout not found for update', ['id' => $id, 'error' => $exception->getMessage()]);
            throw $exception;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Workout could not be updated', ['error' => $exception->getMessage()]);
            throw new \Exception('Workout could not be updated');
        }
    }

    public function deleteWorkout(int $id): void
    {
        try {
            DB::beginTransaction();
            $workout = Workout::findOrFail($id);
            $workout->delete();
            UploadHelper::deleteFile($workout);
            $workout->image()->delete();
            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('Workout not found', ['workout_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Workout not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Workout could not be deleted', ['workout_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('Workout could not be deleted');
        }
    }
}
