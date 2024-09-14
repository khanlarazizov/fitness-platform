<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Lib\Interfaces\ICategoryRepository;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct(protected ICategoryRepository $categoryRepository)
    {
    }

    public function index()
    {
        $categories = $this->categoryRepository->getAllCategories();
        if (!$categories) {
            return ResponseHelper::error(message: 'Categories could not found');
        }
        return ResponseHelper::success(
            message: 'Categories found successfully',
            data: CategoryResource::collection($categories)
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryRepository->createCategory($request->validated());
        } catch (\Exception $exception) {
            Log::error('Category could not be created', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Category could not be created',
                statusCode: 400
            );
        }

        return ResponseHelper::success(
            message: 'Category created successfully',
            data: CategoryResource::make($category)
        );
    }

    public function show(int $id)
    {
        $category = $this->categoryRepository->getCategoryById($id);
        if (!$category) {
            return ResponseHelper::error(message: 'Category could not found');
        }
        return ResponseHelper::success(
            message: 'Category found successfully',
            data: CategoryResource::make($category)
        );
    }

    public function update(int $id, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->getCategoryById($id);

        if (!$category) {
            return ResponseHelper::error(message: 'Category could not found');
        }

        try {
            $category = $this->categoryRepository->updateCategory($id, $request->validated());
        } catch (\Exception $exception) {
            Log::error('Category could not be updated', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'Category could not be updated',
                statusCode: 400);
        }
        return ResponseHelper::success(
            message: 'Category updated successfully',
            data: CategoryResource::make($category)
        );
    }


    public function destroy(int $id)
    {
        $category = $this->categoryRepository->getCategoryById($id);

        if (!$category) {
            return ResponseHelper::error(message: 'Category could not found');
        }

        $this->categoryRepository->deleteCategory($id);
    }
}
