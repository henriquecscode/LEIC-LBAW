<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model{

    public $timestamps = false;
    protected $table = 'comment';

    protected $primaryKey = 'post';
    
    protected $fillable = [
        'post', 'responding'
    ];

    public function postable(){
        return Post::find($this->post);
        // return $this->morphOne(Post::class, 'postable');
    }

    public function parent(){
        return $this->belongsTo(Post::class, 'responding');
    }

    public function question(){
        if($this->parent->isComment()){
            $parent_comment = Comment::find($this->parent->id);
            return $parent_comment->question();
        }
        else{
            return $this->parent;
        }
    }
    
}