<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Config;

class BookingUtil
{
    /**
     * Check if the user is an admin or superadmin.
     *
     * @param object $user
     * @return bool
     */
    public static function isAdmin($user): bool
    {
        $adminRoles = [
            Config::get('constants.ADMIN_ROLE_ID'),
            Config::get('constants.SUPERADMIN_ROLE_ID'),
        ];

        return in_array($user->user_type, $adminRoles);
    }
}
