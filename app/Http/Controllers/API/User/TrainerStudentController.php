<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TrainerStudentService;
use Illuminate\Http\JsonResponse;

class TrainerStudentController extends Controller
{
    public function __construct(protected TrainerStudentService $trainerStudentService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $students = $this->trainerStudentService->getAllStudents();
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }

        return ResponseHelper::success(data: UserResource::collection($students));
    }

    public function show(User $user): JsonResponse
    {
        try {
            $student = $this->trainerStudentService->getStudentById($user->id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }

        return ResponseHelper::success(data: UserResource::make($student));
    }

    public function destroy(User $user)
    {

    }
}
