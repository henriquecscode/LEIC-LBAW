<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showReports()
    {

        if (!Auth::check()) return redirect('/login');

        $reports = Report::all();

        foreach ($reports as $key => $report) {
            
            $reports[$key]->post = $report->getPost;
            $reports[$key]->member = $report->post->owner;
            
            if ($reports[$key]->post->isComment()) {
                $question = Comment::find($reports[$key]->post->id)->question();
                $reports[$key]->question = $question;
                $reports[$key]->isComment = true;
            }
            else{
                $reports[$key]->question = $reports[$key]->post;
                $reports[$key]->isComment = true;
            }
        }

        return view('pages.reports', ['reports' => $reports]);
    }

    public function showAppeals(){

        if (!Auth::check()) return redirect('/login');
    
        $blockedUser = Member::where('is_banned', TRUE)->get();

        return view('pages.appeals', ['blockedUser' => $blockedUser]);
    }

    public function block($member_id)
    {
        if (!Auth::check()) return redirect('/login');
        $member = Member::findOrFail($member_id);
        $this->authorize('block', $member);
        $member->is_banned = true;
        $member->save();

        $blocked = $member->is_banned;

        if (Auth::check()) {
            $canEdit = Auth::user()->can('editProfile', $member);
        } else {
            $canEdit = false;
        }

        return view('partials.block', ['member' => $member, 'blocked' => $blocked, 'canEdit' => $canEdit]);
    }

    public function unblock($member_id)
    {
        if (!Auth::check()) return redirect('/login');
        $member = Member::findOrFail($member_id);
        $this->authorize('unblock', $member);
        $member->is_banned = false;
        $member->appeal = null;
        $member->save();

        $blocked = $member->is_banned;

        if (Auth::check()) {
            $canEdit = Auth::user()->can('editProfile', $member);
        } else {
            $canEdit = false;
        }

        return view('partials.block', ['member' => $member, 'blocked' => $blocked, 'canEdit' => $canEdit]);
    }

    public function showCategoriesEditForm()
    {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('editCategories', Member::class);
        return view('pages.createCategory');
    }

    public function editCategories(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('editCategories', Member::class);

        $request->validate([
            'name' => 'required|string'
        ]);

        $name = strtolower($request->name);
        Category::firstOrCreate(['name' => $name]);
        return redirect('/category');
    }

    public function addMod($member_id)
    {
        if (!Auth::check()) return redirect('/login');

        $member = Member::findOrFail($member_id);
        $this->authorize('editMod', $member);

        $member->member_type = 'mod';
        $member->save();
        return $member;
    }

    public function removeMod($member_id)
    {
        if (!Auth::check()) return redirect('/login');

        $member = Member::findOrFail($member_id);
        $this->authorize('editMod', $member);

        $member->member_type = 'member';
        $member->save();

        return $member;
    }

    public function removeReport($id){

        if (!Auth::check()) return redirect('/login');

        $this->authorize('removeReport',Member::class);
        $report = Report::findOrFail($id);
        $report->delete();

        return response('Report removed successfully', 200);
    }

    public function removeMember($member_id)
    {
        if (!Auth::check()) return redirect('/login');

        $member = Member::findOrFail($member_id);
        $this->authorize('removeMember', $member);


        $member->notifications()->detach(); //Eliminate all notifications
        $member->badges()->detach(); //Eliminate all badges

        // Eliminate all subscriptions
        $member->subscribingMembers()->detach();
        $member->subscribingCategories()->detach();
        $member->subscribingPosts()->detach();

        $allComments = $member->comments;
        foreach ($allComments as $comment) {
            $member->removePost($comment->postable());
        }

        $allQuestions = $member->questions;
        foreach ($allQuestions as $question) {
            $member->removePost($question->postable());
        }

        // Delete reports belonging to this member
        // try{
        $reports = Report::all()->where('member', $member_id);
        foreach ($reports as $report) {
            $report->delete();
        }
        // }
        // catch(...){
        //     return $e;
        // }

        if ($member->id == Auth::id()) {
            Auth::logout();
        } else {
            // Is admin, all good
        }
        $member->delete();
        return redirect('/login');
    }
}
