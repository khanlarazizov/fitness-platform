<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Symfony\Component\String\s;

class UploadHelper
{
    public static function uploadFile($file)
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        try {
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/' . $year . '/' . $month . '/', $fileName, 'public');
            return $fileName;
        } catch (\Exception $e) {
            Log::error('Image could not be uploaded', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public static function updateFile($file, $model)
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $fileName = "";

        try {
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('uploads/' . $year . '/' . $month . '/', $fileName, 'public');

            if ($model->image && $model->image->name) {
                Storage::disk('public')->delete('uploads/' . $year . '/' . $month . '/' . $model->image->name);
            }

            return $fileName;
        } catch (\Exception $e) {
            Log::error('Image could not be uploaded', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public static function deleteFile($model)
    {
        if ($model->image && $model->image->name) {
            Storage::disk('public')->delete($model->image->name);
        }
    }
}
