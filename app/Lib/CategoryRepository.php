<?php

namespace App\Lib;

use App\Lib\Interfaces\ICategoryRepository;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryRepository implements ICategoryRepository
{

    public function getAllCategories(): Collection
    {
        return Category::with('workouts')->get();
    }

    public function getCategoryById(int $id): ?Category
    {
        try {
            return Category::with('workouts')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            Log::error('Category not found', ['category_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Category not found');
        }
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data): Category
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);
            $category->update($data);
            DB::commit();
            return $category;
        } catch (ModelNotFoundException $exception) {//todo zor
            DB::rollBack();
            Log::error('Category not found', ['category_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Category not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Category could not be updated', ['category_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('Category could not be updated');
        }
    }

    public function deleteCategory($id): void
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);
            $category->workouts()->delete();
            $category->delete();
            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('Category not found', ['category_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('Category not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Category could not be deleted', ['category_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('Category could not be deleted');
        }
    }
}
