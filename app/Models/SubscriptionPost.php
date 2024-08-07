<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPost extends Model{

    public $timestamps = false;
    protected $table = 'subscription_post';
    
    protected $fillable = [
        'member', 'post' 
    ];
    
}