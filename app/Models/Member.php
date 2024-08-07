<?php

namespace App\Models;

use App\Models\Notification;
use App\Models\Post;



use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'member';
    protected $fillable = [
        'email', 'password', 'name', 'birth_date', 'photo', 'bio'
    ];

    protected $hidden = [
        'password', 'remember_token', 'tsvectors'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'member');
    }

    public function getMember()
    {
        return $this->belongsTo(Member::class, 'member');
    }

    public function questions()
    {
        return $this->hasManyThrough(Question::class, Post::class, 'member', 'post');
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Post::class, 'member', 'post');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes_member_post', 'member', 'post')->withPivot('likes'); //->using(LikesMemberPost::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'member_badge', 'member', 'badge');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'member_notification', 'member', 'notification')->withPivot('is_read');
    }

    // public function subscribedMembers(){
    //     return $this->hasMany(Member::class);
    // }

    // https://stackoverflow.com/questions/36205742/laravel-many-to-many-relationship-on-the-same-model
    // The members that are subscribed to this member
    public function subscribedMembers()
    {
        return $this->belongsToMany(Member::class, 'subscription_member', 'subscribed', 'subscriber');
    }
    // https://stackoverflow.com/questions/36205742/laravel-many-to-many-relationship-on-the-same-model
    // The members that this member subscribes to
    public function subscribingMembers()
    {
        return $this->belongsToMany(Member::class, 'subscription_member', 'subscriber', 'subscribed');
    }

    public function subscribingCategories()
    {
        return $this->belongsToMany(Category::class, 'subscription_category', 'member', 'category');
    }

    public function subscribingPosts()
    {
        return $this->belongsToMany(Post::class, 'subscription_post', 'member', 'post');
    }

    // Check has many through type or relationships?

    public function isNotBlocked()
    {
        return !$this->is_banned;
    }
    public function isMod()
    {
        return strcmp($this->member_type, 'mod') == 0;
    }

    public function isAdmin()
    {
        return strcmp($this->member_type, 'admin') == 0;
    }

    public function removePost(Post $post)
    {
        $post->member = -1;
        $post->save();

        $this->likes -= $post->likes;
        $this->likes += $post->dislikes;
        $this->subscribingPosts()->detach($post->id);
        $this->save();
    }

    public function getNotifications()
    {
        $notifications = $this->notifications;
        foreach ($notifications as $key => $notification) {
            $notifcations[$key] = $notification->displayNotification();
        }
        return $notifications;
    }
    public function getUnreadNotifications()
    {
        $notifications = $this->notifications()->wherePivot('is_read', false)->get();
        foreach ($notifications as $key => $notification) {
            $notifcations[$key] = $notification->displayNotification();
        }
        return $notifications;
    }
}
