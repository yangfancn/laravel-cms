<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as PermissionAlias;

class Permission extends PermissionAlias
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'guard_name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function adminMenu(): HasOne
    {
        return $this->hasOne(AdminMenu::class);
    }
}
