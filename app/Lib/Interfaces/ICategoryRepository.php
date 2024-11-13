<?php

namespace App\Lib\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ICategoryRepository
{
    public function getAllCategories(array $data): LengthAwarePaginator;

    public function getCategoryById(int $id): ?Category;

    public function createCategory(array $data): Category;

    public function updateCategory(int $id, array $data): Category;

    public function deleteCategory(int $id): void;
}
