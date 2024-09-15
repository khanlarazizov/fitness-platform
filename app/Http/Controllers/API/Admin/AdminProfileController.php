<?php

namespace App\Http\Controllers\API\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProfileRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;

class AdminProfileController extends Controller
{
    public function show(int $id)
    {
        if (auth()->user()->id !== $id) {
            return ResponseHelper::error(message: 'Profile not found');
        }

        return ResponseHelper::success(
            message: 'Profile found successfully',
            data: AdminResource::make(auth()->user())
        );
    }

    public function update(int $id, AdminProfileRequest $request)
    {
        if (auth()->user()->id !== $id) {
            return ResponseHelper::error(message: 'Profile not found');
        }

        $admin = Admin::find($id);
        $admin->update($request->validated());
        return ResponseHelper::success(
            message: 'Profile updated successfully',
            data: AdminResource::make($admin)
        );
    }
}
