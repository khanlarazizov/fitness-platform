<?php

namespace App\Lib;

use App\Helpers\UploadHelper;
use App\Lib\Interfaces\ITrainerRepository;
use App\Models\Trainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainerRepository implements ITrainerRepository
{

    public function getAllTrainers()
    {
        return Trainer::with('image')->get();
    }

    public function getTrainerById(int $id)
    {
        return Trainer::find($id)->load('image');
    }

    public function createTrainer(array $data)
    {
        DB::beginTransaction();
        try {
            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;

            $trainer = Trainer::create($data);
            $trainer->image()->create(['name' => $file]);
            $trainer->assignRole('trainer');

            DB::commit();
            return $trainer;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Trainer could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('Trainer could not be created');
        }
    }

    public function updateTrainer(int $id, array $data)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            Log::error('Trainer not found');
            throw new \Exception('User not found');
        }

        DB::beginTransaction();
        try {

            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;
            $trainer->update($data);
            $trainer->image()->update(['name' => $file]);
            DB::commit();
            return $trainer;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Trainer could not be updated', ['error' => $exception->getMessage()]);
            throw new \Exception('Trainer could not be updated');
        }

    }

    public function deleteTrainer(int $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            Log::error('Trainer not found');
            throw new \Exception('Trainer not found');
        }

        DB::beginTransaction();
        try {
            $trainer->delete();
            $trainer->image()->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Trainer could not be deleted', ['error' => $exception->getMessage()]);
            throw new \Exception('Trainer could not be deleted');
        }
    }
}
