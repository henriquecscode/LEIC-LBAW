<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Badge extends Model
{

    public $timestamps = false;
    protected $table = 'badge';

    public function members(){
        return $this->belongsToMany(Member::Class, 'member_badge', 'badge', 'member');
    }

    public function notifications(){
        return $this->hasMany(Notification::class, 'badge');
    }
}