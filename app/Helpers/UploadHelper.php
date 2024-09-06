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

    public function updateFile($file, $modelName)
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $fileName = "";
        try {
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/' . $year . '/' . $month . '/', $fileName, 'public');
            if ($modelName->image->fullName) {
                Storage::delete('uploads/' . $year . '/' . $month . '/' . $modelName->image->fullName);
            }
        }catch (\Exception $e) {
            Log::error('Image could not be uploaded', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
