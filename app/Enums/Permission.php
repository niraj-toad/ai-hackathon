<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * This enum has a counterpart in the frontend that is generated. If you update this,
 * you should run `php artisan build:ts-enum permission`.
 */
enum Permission: string
{
    /** Grants access to the staff panel in the frontend. */
    case AccessStaffPanel = 'access_staff_panel';
    /** Grants access to user details. */
    case ViewUsers = 'view_users';
}
