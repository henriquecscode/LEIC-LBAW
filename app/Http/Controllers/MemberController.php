<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\SubscriptionMember;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Question;
use App\Models\Report;
use App\Policies\MemberPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        $questions = $member->questions;
        $comments = $member->comments;
        $badges = $member->badges;
        $blocked = $member->is_banned;

        if (Auth::check()) {
            $canEdit = Auth::user()->can('editProfile', $member);
        } else {
            $canEdit = false;
        }

        if (Auth::check()) {
            $canEditMod = Auth::user()->can('editMod', $member);
        } else {
            $canEditMod = false;
        }

        if (Auth::check()) {
            $canEditBlock = Auth::user()->can('editBlock', $member);
        } else {
            $canEditBlock = false;
        }

        foreach ($comments as $key => $comment) {
            $post_ = $comment->postable();
            $comments[$key] = $post_;
            $comments[$key]->question = $comment->question();
        }

        $subscribed = SubscriptionMember::where('subscribed','=', $id)->where('subscriber','=', Auth::id())->exists();
        $canSubscribe = !$subscribed;
        return view('pages.profile', ['member' => $member, 'questions' => $questions, 'comments' => $comments, 'canEdit' => $canEdit, 'canEditMod' => $canEditMod, 'canEditBlock' => $canEditBlock, 'badges' => $badges, 'canSubscribe' => $canSubscribe, 'blocked' => $blocked]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::check()) return redirect('/login');

        $member = Member::findOrFail($id);
        $this->authorize('editProfile', $member);
        return view('pages.profileEdit', ['member' => $member]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) return redirect('/login');
        //Missing password and photo
        $member = Member::findOrFail($id);
        $this->authorize('editProfile', $member);

        
        $member->name = $request->name;

        if ($request->hasFile('file')) {
            $photo = $request->file->store('public/images');
            $arr = explode("/", $photo);
            $member->photo = $arr[2];
        }
        
        $member->bio = $request->bio;
        $member->email = $request->email;
        $member->birth_date = $request->date;
        $member->save();
        return redirect("/members/{$id}");
    }

    public function vote($post_id, $value)
    {
        if (!Auth::check()) return response('/login', 401);

        $post = Post::findOrFail($post_id);
        $member = Auth::user();

        $member->likes()->detach($post);
        if ($value == 1) {
            // Add entry with 1
            $member->likes()->attach($post, ['likes' => 1]);
        } else if ($value == -1) {
            $member->likes()->attach($post, ['likes' => -1]);
            // Remove entry
        }
        $post = Post::findOrFail($post_id);

        return $post;
    }

    public function feed()
    {
        if (!Auth::check()) return redirect('/login');
        $question = Question::all()->random(1)->first();
        $post = $question->postable();
        return view('pages.feed', ['question' => $question, 'post' => $post]);
    }

    public function report(Request $request, $post_id)
    {
        if (!Auth::check()) return response('/login', 401);

        Post::findOrFail($post_id);

        Report::create([
            'motive' => $request['conteudo'],
            'post' => $post_id,
            'member' => Auth::id()
        ]);
        return response('Report successfully created', 200);
    }

    public function viewNotifications(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $notifications = Auth::user()->getNotifications();
        return view('pages.notifications', ['notifications' => $notifications]);
    }

    public function readNotification(Request $request, $notification_id)
    {

        if (!Auth::check()) return response('/login', 401);

        $notification = Notification::findOrFail($notification_id);

        $notification->notifiedMembers()->sync([Auth::id() => ['is_read' => true]], false);
        $notification = Auth::user()->notifications()->where('notification', $notification_id)->get()->first();
        $notification = $notification->displayNotification();

        return view('partials.notification', ['notification' => $notification]);
    }


    public function showTypeSubscriptions()
    {
        if (!Auth::check()) return redirect('/login');
        return view('pages.showTypeSubscriptions');
    }
    public function showPostSubscriptions()
    {
        if (!Auth::check()) return redirect('/login');
        $posts = Auth::user()->subscribingPosts;
        foreach ($posts as $key => $post) {
            $question = Question::findOrFail($post->id);
            $posts[$key]->title = $question->title;
        }

        return view('pages.showSubscriptions', ['path' => 'Post subscriptions', 'posts' => $posts]);
    }

    public function showMemberSubscriptions()
    {
        if (!Auth::check()) return redirect('/login');
        $members = Auth::user()->subscribingMembers;
        return view('pages.showSubscriptions', ['path' => 'Member subscriptions','members' => $members]);
        // return view('pages.')
    }
    public function showCategorySubscriptions()
    {
        if (!Auth::check()) return redirect('/login');
        $categories = Auth::user()->subscribingCategories;
        return view('pages.showSubscriptions', ['path' => 'Category subscriptions','categories' => $categories]);
        // return view('pages.')
    }

    public function appeal(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        $member = Member::findOrFail(Auth::id());
        $member->appeal = $request->text;
        $member->save();
        return back();
    }
}
