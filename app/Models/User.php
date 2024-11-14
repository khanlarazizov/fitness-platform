<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\DirectionEnum;
use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guard_name = 'api';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => StatusEnum::class,
            'gender' => GenderEnum::class,
            'role' => RoleEnum::class,
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'surname'],
                'onUpdate' => true,
            ]
        ];
    }

    public function scopeName(Builder $query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeSurname(Builder $query, $surname)
    {
        return $query->where('surname', 'like', '%' . $surname . '%');
    }

    public function scopeGender(Builder $query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopePhoneNumber(Builder $query, $phoneNumber)
    {
        return $query->where('phone_number', 'like', '%' . $phoneNumber . '%');
    }

    public function scopeBirthdayBetween(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('birth_date', [$startDate, $endDate]);
    }

    public function scopeStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeTrainer(Builder $query, $trainer_id)
    {
        return $query->whereHas('trainer', fn($query) => $query->where('trainer_id', $trainer_id));
    }

    public function scopeWeightBetween(Builder $query, $startWeight, $endWeight)
    {
        return $query->whereBetween('weight', [$startWeight, $endWeight]);
    }

    public function scopeHeightBetween(Builder $query, $startHeight, $endHeight)
    {
        return $query->whereBetween('height', [$startHeight, $endHeight]);
    }

    public function scopeSortBy(Builder $query, $sortBy, $direction = DirectionEnum::ASC->value)
    {
        if (in_array($sortBy, ['name', 'surname', 'weight', 'height', 'created_at'])
            && in_array($direction, array_column(DirectionEnum::cases(), 'value'))
        ) {
            return $query->orderBy($sortBy, $direction);
        }
    }

    public
    function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public
    function students(): HasMany
    {
        return $this->hasMany(User::class, 'trainer_id');
    }

    public
    function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public
    function scopeAllTrainers($query)
    {
        return $query->whereHas('roles', fn($query) => $query->where('name', 'trainer'));
    }


    public
    function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_user');
    }

    public
    function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
