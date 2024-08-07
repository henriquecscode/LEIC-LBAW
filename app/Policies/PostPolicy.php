<?php

namespace App\Policies;

use App\Policies\Privileges;
use App\Models\Member;
use App\Models\Post;

use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function owner(Member $member, Post $post){
      return $post->owner == $member;
    }
    
    public function update(Member $member, Post $post){
      return Privileges::modPrivileges($member) or $this->owner($member, $post);
    }

    public function edit(Member $member, Post $post){
      return $this->update($member, $post);
    }

    public function softDelete(Member $member, Post $post)
    {
      return $this->update($member, $post);
    }
}
