<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionPostController extends Controller
{
    public function subscribe(Request $request, $post_id){
        if (!Auth::check()) return redirect('/login');
        if (!SubscriptionPost::where('post','=', $post_id)->where('member','=', Auth::id())->exists()){
            $subscription = SubscriptionPost::create([
                'member' => Auth::id(), 
                'post' => $post_id
                    ]);
        }
        return back();
    }

    public function unsubscribe(Request $request, $post_id){
        if (!Auth::check()) return redirect('/login');
        if (SubscriptionPost::where('post','=', $post_id)->where('member','=', Auth::id())->exists()){
            $res = SubscriptionPost::where('post','=', $post_id)->where('member','=', Auth::id())->delete();
        }
        return back();
    }

}
