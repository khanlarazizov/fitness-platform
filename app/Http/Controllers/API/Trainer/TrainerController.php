<?php

namespace App\Http\Controllers\API\Trainer;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreTrainerRequest;
use App\Http\Requests\Trainer\UpdateTrainerRequest;
use App\Http\Resources\TrainerResource;
use App\Lib\Interfaces\ITrainerRepository;
use Illuminate\Support\Facades\Log;

class TrainerController extends Controller
{

    public function __construct
    (
        protected ITrainerRepository $trainerRepository
    )
    {
    }

    public function index()
    {
        $trainers = $this->trainerRepository->getAllTrainers();
        if (!$trainers) {
            return ResponseHelper::success(message: 'No trainers found');
        }

        return ResponseHelper::success(
            message: 'Trainers found successfully',
            data: TrainerResource::collection($trainers)
        );
    }

    public function store(StoreTrainerRequest $request)
    {
        try {
            $trainer = $this->trainerRepository->createTrainer($request->validated());
        } catch (\Exception $exception) {
            Log::error('Trainer could not be created', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Trainer could not be created',
                statusCode: 400
            );
        }

        return ResponseHelper::success(
            message: 'Trainer created successfully',
            data: TrainerResource::make($trainer)
        );
    }

    public function show(int $id)
    {
        $trainer = $this->trainerRepository->getTrainerById($id);
        if (!$trainer) {
            return ResponseHelper::error(message: 'Trainer could not found');
        }
        return ResponseHelper::success(
            message: 'Trainer found successfully',
            data: TrainerResource::make($trainer)
        );
    }

    public function update(UpdateTrainerRequest $request, int $id)
    {
        $trainer = $this->trainerRepository->getTrainerById($id);

        if (!$trainer) {
            return ResponseHelper::error(message: 'Trainer could not found');
        }

        try {
            $trainer = $this->trainerRepository->updateTrainer($id, $request->validated());
        } catch (\Exception $exception) {
            Log::error('Trainer could not be updated', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Trainer could not be updated',
                statusCode: 400
            );
        }

        return ResponseHelper::success(
            message: 'Trainer updated successfully',
            data: TrainerResource::make($trainer)
        );
    }

    public function destroy(int $id)
    {
        $trainer = $this->trainerRepository->getTrainerById($id);
        if (!$trainer) {
            return ResponseHelper::error(message: 'Trainer could not found');
        }

        $this->trainerRepository->deleteTrainer($id);
    }
}
