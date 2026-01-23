<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as PermissionRole;

class Role extends PermissionRole
{
    use HasFactory;
    use LogsActivity;

    protected $hidden = ['pivot'];

    protected $fillable = ['name', 'guard_name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    protected static function booted(): void
    {
        static::saving(fn (Role $role) => $role->guard_name = 'web');
    }
}
