<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $file = UploadHelper::class->uploadFile($request->file('uploaded_file'));

        return response()->json([
            'status' => 'success',
            'file' => $file
        ]);
    }
}
