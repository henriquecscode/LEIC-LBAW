<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model{

    public $timestamps = false;
    protected $table = 'category';
    protected $fillable = ['name'];

    public function subscribedMembers(){
        return $this->belongsToMany(Member::class, 'subscription_category', 'category', 'member');
    }

    public function questions(){
        return $this->belongsToMany(Question::class, 'question_category', 'category', 'question');
    }
}