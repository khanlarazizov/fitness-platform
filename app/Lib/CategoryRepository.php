<?php

namespace App\Lib;

use App\Lib\Interfaces\ICategoryRepository;
use App\Models\Category;
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
        return Category::find($id)->load('workouts');
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data): Category
    {
        $category = Category::find($id);
        $category->update($data);

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        DB::beginTransaction();
        try {
            $category->workouts()->delete();
            $category->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Category could not be deleted', ['error' => $exception->getMessage()]);
            throw new \Exception('Category could not be deleted');
        }
    }
}
