<?php

namespace App\Enums;

enum UserRole: string
{
    case Member = 'member';
    case Staff = 'staff';
    case Finance = 'finance';
    case Leader = 'leader';
    case SuperAdmin = 'super_admin';
}
