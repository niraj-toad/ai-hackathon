<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Traits\HasUlidRouteKey;
use Carbon\CarbonImmutable;
use Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read string $email
 * @property string $first_name
 * @property-read int $id
 * @property string $last_name
 * @property string $password
 * @property Role $role
 * @property-read string $remember_token
 * @property-read string $ulid
 * @property-read CarbonImmutable|null $email_verified_at
 * @property-read CarbonImmutable|null $created_at
 * @property-read CarbonImmutable|null $updated_at
 *
 * @method static UserFactory factory($count = null, $state = [])
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;
    use HasUlidRouteKey;
    use MustVerifyEmail;
    use Notifiable;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => Role::class,
    ];

    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions()->contains($permission);
    }

    /** @return Collection<int, Permission> */
    public function permissions(): Collection
    {
        return $this->role->permissions();
    }
}
