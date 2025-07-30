<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as PermissionRole;

/**
 * @mixin IdeHelperRole
 */
class Role extends PermissionRole
{
    use HasFactory;

    protected $hidden = ['pivot'];

    protected $fillable = ['name', 'guard_name'];

    protected static function booted(): void
    {
        static::saving(fn (Role $role) => $role->guard_name = 'web');
    }
}
