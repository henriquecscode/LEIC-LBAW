<?php

namespace App\Policies;

use App\Policies\Privileges;
use App\Models\Member;
use App\Models\Post;

use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    public function administrating(Member $member, Member $blocking)
    {
        return Privileges::adminPrivileges($member) and !Privileges::adminPrivileges($blocking);
    }

    public function post(Member $member)
    {
        return Privileges::postPrivileges($member);
    }

    public function editProfile(Member $member, Member $owner)
    {
        return Privileges::adminPrivileges($member) or $member->id == $owner->id;
    }

    public function block(Member $member, Member $blocking)
    {
        return $this->administrating($member, $blocking);
    }

    public function unblock(Member $member, Member $blocking)
    {
        return $this->administrating($member, $blocking);
    }

    public function editCategories(Member $member)
    {
        return Privileges::adminPrivileges($member);
    }

    public function editMod(Member $member, Member $newMod)
    {
        return $this->administrating($member, $newMod);
    }

    public function removeMod(Member $member, Member $newMod)
    {
        return $this->administrating($member, $newMod);
    }

    public function editBlock(Member $member, Member $blocked)
    {
        return $this->administrating($member, $blocked);
    }
    
    public function removeMember(Member $member, Member $owner)
    {
        return $this->administrating($member, $owner) or $member->id == $owner->id;
    }

    public function viewReports(Member $member)
    {
        return Privileges::adminPrivileges($member);
    }

    public function removeReport(Member $member)
    {
        return Privileges::adminPrivileges($member);
    }
}
