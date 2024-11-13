<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\SearchCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Lib\Interfaces\ICategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(protected ICategoryRepository $categoryRepository)
    {
    }

    public function index(SearchCategoryRequest $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $categories = $this->categoryRepository->getAllCategories($request->validated());
            return CategoryResource::collection($categories);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage()
            );
        }
    }

    public function store(StoreCategoryRequest $request): CategoryResource|JsonResponse
    {
        try {
            $category = $this->categoryRepository->createCategory($request->validated());
            return CategoryResource::make($category);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }
    }

    public function show(int $id): CategoryResource|JsonResponse
    {
        try {
            $category = $this->categoryRepository->getCategoryById($id);
            return ResponseHelper::success(data: CategoryResource::make($category));
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }
    }

    public function update(int $id, UpdateCategoryRequest $request): CategoryResource|JsonResponse
    {
        try {
            $category = $this->categoryRepository->updateCategory($id, $request->validated());
            return CategoryResource::make($category);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->categoryRepository->deleteCategory($id);
            return ResponseHelper::success();
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }
    }
}
