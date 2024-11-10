<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case MANAGE_TRAINERS = 'manage-trainers';
    case MANAGE_USERS = 'manage-users';
    case MANAGE_PERMISSIONS = 'manage-permissions';
    case MANAGE_CATEGORIES = 'manage-categories';
    case MANAGE_WORKOUTS = 'manage-workouts';
    case MANAGE_ROLES = 'manage-roles';
    case MANAGE_PLANS = 'manage-plans';
    case CHOOSE_TRAINER = 'choose-trainer';
    case ASSIGN_PLAN = 'assign-plan';
}
