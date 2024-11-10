<?php

namespace App\Lib\Interfaces;

use App\Models\Category;
use Illuminate\Support\Collection;

interface ICategoryRepository
{
    public function getAllCategories(): Collection;

    public function getCategoryById(int $id): ?Category;

    public function createCategory(array $data): Category;

    public function updateCategory(int $id, array $data): Category;

    public function deleteCategory(int $id): void;
}
