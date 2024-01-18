<?php

declare(strict_types=1);

use App\Enums\Permission;

return [
    'permissions' => [
        Permission::AccessStaffPanel,
        Permission::ViewUsers,
    ],
];
