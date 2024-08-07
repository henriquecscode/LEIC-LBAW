<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionCategory extends Model{

    public $timestamps = false;
    protected $table = 'subscription_category';
    
    protected $fillable = [
        'member', 'category' 
    ];
    
}