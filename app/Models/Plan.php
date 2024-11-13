<?php

namespace App\Models;

use App\Enums\DirectionEnum;
use App\Enums\StatusEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Plan extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function scopeName(Builder $query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
    }

    public function scopeStatus(Builder $query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }
    }

    public function scopeTrainer(Builder $query, $trainer_id)
    {
        if (!is_null($trainer_id)) {
            return $query->whereHas('trainer', fn($query) => $query->where('id', $trainer_id));
        }
    }

    public function scopeSortBy(Builder $query, $sortBy, $direction = DirectionEnum::ASC->value)
    {
        if (in_array($sortBy, ['name', 'created_at']) && in_array($direction, array_column(DirectionEnum::cases(), 'value'))) {
            return $query->orderBy($sortBy, $direction);
        }
    }

    public function workouts(): BelongsToMany
    {
        return $this->belongsToMany(Workout::class, 'plan_workout');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'plan_user');
    }
}
