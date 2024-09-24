<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case MANAGE_TRAINERS = 'manage_trainers';
    case MANAGE_USERS = 'manage_users';
    case MANAGE_PERMISSIONS = 'manage_permissions';
    case MANAGE_CATEGORIES = 'manage_categories';
    case MANAGE_WORKOUTS = 'manage_workouts';
    case MANAGE_ROLES = 'manage_roles';
    case MANAGE_PLANS = 'manage_plans';

    public function label(): string
    {
        return match ($this) {
            self::MANAGE_TRAINERS => 'Manage Trainers',
            self::MANAGE_USERS => 'Manage Users',
            self::MANAGE_PERMISSIONS => 'Manage Permissions',
            self::MANAGE_ROLES => 'Manage Roles',
            self::MANAGE_CATEGORIES => 'Manage Categories',
            self::MANAGE_WORKOUTS => 'Manage Workouts',
            self::MANAGE_PLANS => 'Manage Plans',
        };
    }
}
