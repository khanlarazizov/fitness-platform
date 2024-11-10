<?php

namespace App\Lib\Interfaces;

use App\Models\Workout;
use Illuminate\Support\Collection;

interface IWorkoutRepository
{
    public function getAllWorkouts(): Collection;

    public function getWorkoutById(int $id): ?Workout;

    public function createWorkout(array $data): Workout;

    public function updateWorkout(int $id, array $data): Workout;

    public function deleteWorkout(int $id): void;
}
