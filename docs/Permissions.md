## Permissions

Permissions in this project are defined as enums in both the frontend and the backend.

### Backend
Enum file: [`app/Enums/Permission.php`](../app/Enums/Permission.php)

#### Granting Permissions
Permissions are granted to roles, not directly to users, via the `config/roles/{roleName}.php` configuration files.

#### Checking Permissions
Permissions should be checked in Policy classes by checking if the user has permission via the `User::hasPermission`
method.

### Frontend
Enum file: [`resources/scripts/common/enums/Permission.ts`](../resources/scripts/common/enums/Permission.ts)

#### Checking Permissions
The `authStore` exposes the `hasAllPermissions` and `hasPermission` methods which can be used when you need to gate
individual elements on a page.

The `usePermissionGuard` composable should be used to gate whole pages by calling it first in the root level component
of that page. This is to allow the `ForbiddenError` that it throws to bubble up to the `Root` component where it can
be handled and a `ForbiddenErrorPage` can be rendered.
