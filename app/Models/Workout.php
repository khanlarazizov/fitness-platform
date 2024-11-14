<?php

namespace App\Models;

use App\Enums\DirectionEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workout extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function scopeName(Builder $query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
    }

    public function scopeCategory(Builder $query, $category_id)
    {
        if (!is_null($category_id)) {
            return $query->whereHas('category', fn($query) => $query->where('id', $category_id));
        }
    }

    public function scopeSortBy(Builder $query, $sortBy, $direction = DirectionEnum::ASC->value)
    {
        if (in_array($sortBy, ['name', 'created_at'])
            && in_array($direction, array_column(DirectionEnum::cases(), 'value'))
        ) {
            return $query->orderBy($sortBy, $direction);
        }
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_workout');
    }
}
