<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model{

    public $timestamps = false;
    protected $table = 'report';
    protected $fillable = [
        'report_date', 'motive', 'post', 'member'
    ];

    public function getPost(){
        return $this->belongsTo(Post::class, 'post');
    }

}