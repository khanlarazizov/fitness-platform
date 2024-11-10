<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class TrainerService
{
    public function assignTrainer(User $user, int $trainerId): void
    {
        try {
            $user->trainer()->associate($trainerId);
            $user->save();
        } catch (\Exception $exception) {
            Log::error('Error assigning trainer: ' . $exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }

    public function cancelTrainer(User $user): void
    {
        try {
            $user->trainer()->dissociate();
            $user->save();
        } catch (\Exception $exception) {
            Log::error('Error canceling trainer: ' . $exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }
}
