<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Lib\Interfaces\ICategoryRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(protected ICategoryRepository $categoryRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryRepository->getAllCategories();
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage()
            );
        }

        return ResponseHelper::success(data: CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryRepository->createCategory($request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: CategoryResource::make($category));
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->getCategoryById($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: CategoryResource::make($category));
    }

    public function update(int $id, UpdateCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryRepository->updateCategory($id, $request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400);
        }

        return ResponseHelper::success(data: CategoryResource::make($category));
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->categoryRepository->deleteCategory($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success();
    }
}
