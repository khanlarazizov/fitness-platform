<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $file = UploadHelper::class->uploadFile($request->file('uploaded_file'));

            return ResponseHelper::success(data: $file);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }
    }
}
