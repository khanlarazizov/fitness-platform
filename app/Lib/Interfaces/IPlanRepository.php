<?php

namespace App\Lib\Interfaces;

use App\Models\Plan;
use Illuminate\Support\Collection;

interface IPlanRepository
{
    public function getAllPlans(): Collection;

    public function getPlanById(int $id): ?Plan;

    public function createPlan(array $data): Plan;

    public function updatePlan(int $id, array $data): Plan;

    public function deletePlan(int $id);
}
