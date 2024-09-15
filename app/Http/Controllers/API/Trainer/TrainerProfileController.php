<?php

namespace App\Http\Controllers\API\Trainer;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\TrainerProfileRequest;
use App\Http\Resources\TrainerResource;
use App\Models\Trainer;

class TrainerProfileController extends Controller
{
    public function show(int $id)
    {
        if (auth()->user()->id !== $id) {
            return ResponseHelper::error(message: 'Profile not found');
        }

        return ResponseHelper::success(
            message: 'Profile found successfully',
            data: TrainerResource::make(auth()->user())
        );
    }

    public function update(int $id, TrainerProfileRequest $request)
    {
        if (auth()->user()->id !== $id) {
            return ResponseHelper::error(message: 'Profile not found');
        }

        $trainer = Trainer::find($id);
        $trainer->update($request->validated());
        return ResponseHelper::success(
            message: 'Profile updated successfully',
            data: TrainerResource::make($trainer)
        );
    }
}
