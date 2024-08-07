<?php

namespace App\Policies;

use App\Models\Member;

use Illuminate\Auth\Access\HandlesAuthorization;

class Privileges
{
    use HandlesAuthorization;

    public static function postPrivileges(Member $member){
        return $member->isNotBlocked();
    }
    
    public static function modPrivileges(Member $member){
        return $member->isMod() or $member->isAdmin();
    }

    public static function adminPrivileges(Member $member){
        return $member->isAdmin();
    }

}
