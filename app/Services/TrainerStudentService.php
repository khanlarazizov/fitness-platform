<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainerStudentService
{
    public function getAllStudents()
    {
        $trainer = auth()->user();
        return $trainer->students;
    }

    public function getStudentById(int $studentId)
    {
        $trainer = auth()->user();

        try {
            $student = $trainer->students()->findOrFail($studentId);
            return $student;
        } catch (ModelNotFoundException $exception) {
            Log::error('Student not found', [
                'student_id' => $studentId,
                'error' => $exception->getMessage()
            ]);
            throw new ModelNotFoundException('Student not found');
        }
    }

    public function removeStudent($studentId)
    {
        $trainer = auth()->user();

        DB::beginTransaction();
        try {
            $student = $trainer->students()->findOrFail($studentId);

            $student->trainer()->dissociate();
            $student->save();

            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('Student not found', ['student_id' => $studentId, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Student not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error removing student', [
                'student_id' => $studentId,
                'trainer_id' => $trainer->id,
                'error' => $exception->getMessage()
            ]);
            throw new \Exception('Error removing student');
        }
    }
}
