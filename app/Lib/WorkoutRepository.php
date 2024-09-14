<?php

namespace App\Lib;

use App\Helpers\UploadHelper;
use App\Lib\Interfaces\IWorkoutRepository;
use App\Models\Workout;
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
        return Workout::find($id)->load('image', 'category');
    }

    public function createWorkout(array $data): Workout
    {
        DB::beginTransaction();

        try {
            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;

            $workout = Workout::create($data);
            $workout->image()->create(['name' => $file]);
            DB::commit();
            return $workout;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Workout could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('Workout could not be created');
        }
    }

    public function updateWorkout(int $id, array $data): bool
    {
        $workout = Workout::find($id)->load('image', 'category');
        if (!$workout) {
            Log::error('Workout not found');
            throw new \Exception('Workout not found');
        }

        DB::beginTransaction();
        try {
            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;
            $workout->update($data);
            $workout->image()->update(['name' => $file]);

            DB::commit();
            return $workout;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Workout could not be updated', ['error' => $exception->getMessage()]);
            throw new \Exception('Workout could not be updated');
        }
    }

    public function deleteWorkout(int $id)
    {
        $workout = Workout::find($id);

        DB::beginTransaction();
        try {
            $workout->delete();
            $workout->image()->delete();
//            $workout->plans()->detach();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Workout could not be deleted', ['error' => $exception->getMessage()]);
            throw new \Exception('Workout could not be deleted');
        }
    }
}
