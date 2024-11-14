<?php

namespace App\Models;

use App\Enums\DirectionEnum;
use App\Models\Scopes\SortByScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
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

    protected static function booted(): void
    {
        static::addGlobalScope(new SortByScope('created_at', DirectionEnum::ASC->value));
    }

    public function scopeName(Builder $query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }
}
