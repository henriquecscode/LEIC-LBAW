<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {

    public $timestamps = false;
    protected $table = 'question';
    protected $fillable = [
        'post', 'title', 'is_answered'
    ];

    protected $primaryKey = 'post';
    
    public function postable(){
        return Post::find($this->post);
        // return $this->morphOne(Post::class, 'postable');
    }
    
    public function categories(){
        return $this->belongsToMany(Category::class, 'question_category', 'question', 'category');
    }
}