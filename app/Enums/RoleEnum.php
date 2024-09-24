<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case TRAINER = 'trainer';
    case USER = 'user';
}
