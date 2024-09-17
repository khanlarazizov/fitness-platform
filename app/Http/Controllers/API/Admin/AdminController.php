<?php

namespace App\Http\Controllers\API\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\Trainer\UpdateTrainerRequest;
use App\Http\Resources\AdminResource;
use App\Lib\Interfaces\IAdminRepository;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected IAdminRepository $adminRepository
    )
    {}


    public function index()
    {
        $admins = $this->adminRepository->getAllAdmins();

        if (!$admins) {
            return ResponseHelper::error('No admins found', 404);
        }

        return ResponseHelper::success(
            message: 'Admins found successfully',
            data: AdminResource::collection($admins)
        );
    }

    public function store(StoreAdminRequest $request)
    {
        $admin = $this->adminRepository->createAdmin($request->validated());

        if (!$admin) {
            return ResponseHelper::error('Failed to create admin', 500);
        }

        return ResponseHelper::success(
            message: 'Admin created successfully',
            data: AdminResource::make($admin)
        );
    }

    public function show(int $id)
    {
        $admin = $this->adminRepository->getAdminById($id);
        if (!$admin) {
            return ResponseHelper::error('Admin not found', 404);
        }

        return ResponseHelper::success(
            message: 'Admin found successfully',
            data: AdminResource::make($admin)
        );
    }

    public function update(UpdateTrainerRequest $request, int $id)
    {
        $admin = $this->adminRepository->updateAdmin($id, $request->validated());

        if (!$admin) {
            return ResponseHelper::error('Failed to update admin', 500);
        }

        return ResponseHelper::success(
            message: 'Admin updated successfully',
            data: AdminResource::make($admin)
        );
    }

    public function destroy(int $id)
    {
        $this->adminRepository->getAdminById($id);
    }
}
