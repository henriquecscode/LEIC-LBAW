<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    public $timestamps = false;
    protected $table = 'notification';
    protected $fillable = [
        'content', 'notification_time'
    ];

    public function notifiedMembers()
    {
        return $this->belongsToMany(Member::class, 'member_notification', 'notification', 'member')->withPivot('is_read');
    }

    public function getPost()
    {
        return $this->belongsTo(Post::class, 'post');
    }

    public function getBadge()
    {
        return $this->belongsTo(Badge::class, 'badge');
    }

    public function displayNotification()
    {
        $notification = $this;
        if ($this->post != null) {
            $post = $this->getPost;
            if ($post->isComment()) {
                $question = Comment::find($this->post)->question();
                $notification->link = $question;
            } else {
                $notification->link = $post;
            }
        }
        if ($this->badge != null) {
            $badge = $this->getBadge;
            $notification->link = $badge;
        }
        return $notification;
    }
}
