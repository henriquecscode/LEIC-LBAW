<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{

    public $timestamps = false;
    protected $table = 'post';
    protected $fillable = [
        'content', 'publish_date', 'likes', 'dislikes', 'is_edited', 'edition_date', 'member'
    ];

    public function owner()
    {
        return $this->belongsTo(Member::class, 'member');
    }

    public function subscribedMembers()
    {
        return $this->belongsToMany(Member::class, 'subscription_post', 'post', 'member');
    }

    public function likes()
    {
        return $this->belongsToMany(Member::class, 'likes_member_post', 'post', 'member')->withPivot('likes');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'post');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'responding');
    }

    public function childrenPosts()
    {
        return $this->hasManyThrough(Post::class, Comment::class, 'responding', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'post');
    }

    public function isQuestion()
    {
        return Question::where('post', $this->id)->exists();
    }

    public function isComment()
    {
        return Comment::where('post', $this->id)->exists();
    }

    public function canUpdate()
    {
        if (Auth::check()) {
            return Auth::user()->can('update', $this);
        }
        return false;
    }

    public function recursiveChildrenPosts()
    {
        $children = $this->childrenPosts;
        foreach ($children as $key => $child) {
            $children[$key] = $child->displayableComment();
        }
        return $children;
    }

    public function displayableComment()
    {
        $post = $this;
        $post->owner;
        $post->children_posts = $post->recursiveChildrenPosts();
        $post->canUpdate = $post->canUpdate();
        $post->member_likes = $this->voting();
        return $post;
    }

    public function canSubscribe(){
        return !SubscriptionPost::where('post','=', $this->id)->where('member','=', Auth::id())->exists();
    }
    

    public function voting()
    {
        $member = Auth::user();
        if ($member == null) {
            return 0;
        } else {
            $member_likes = $this->likes()->where('member', $member->id)->first();
            if ($member_likes == null) {
                return 0;
            } else {
                return $member_likes->pivot->likes;
            }
        }
    }
}
