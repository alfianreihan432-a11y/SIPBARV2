<?php

namespace App\Policies;

use App\Models\User;

class SiteSettingPolicy
{
    public function manageSiteSettings(User $user): bool
    {
        return $user->hasRole('superadmin');
    }
}
