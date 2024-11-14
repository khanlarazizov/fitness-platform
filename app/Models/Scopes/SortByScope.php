<?php

namespace App\Models\Scopes;

use App\Enums\DirectionEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SortByScope implements Scope
{
    public function __construct(protected $sortBy, protected $direction)
    {
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (in_array($this->sortBy, ['name', 'created_at']) &&
            in_array($this->direction, array_column(DirectionEnum::cases(), 'value'))
        ) {
            $builder->orderBy($this->sortBy, $this->direction);
        }
    }
}
