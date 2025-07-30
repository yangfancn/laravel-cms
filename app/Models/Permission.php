<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Permission as PermissionAlias;

/**
 * @mixin IdeHelperPermission
 */
class Permission extends PermissionAlias
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    public function adminMenu(): HasOne
    {
        return $this->hasOne(AdminMenu::class);
    }
}
