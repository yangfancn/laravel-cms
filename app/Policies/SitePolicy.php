<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;

class SitePolicy
{
    public function update(User $user, Site $site): bool
    {
        return $user->hasPermissionTo('sites update');
    }
}
