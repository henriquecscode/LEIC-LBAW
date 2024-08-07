<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionMember extends Model{

    public $timestamps = false;
    protected $table = 'subscription_member';
    
    protected $fillable = [
        'subscriber', 'subscribed' 
    ];
    
}