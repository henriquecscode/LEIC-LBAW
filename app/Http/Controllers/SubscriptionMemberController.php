<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionMemberController extends Controller
{
    public function subscribe(Request $request, $member_id){
        if (!Auth::check()) return redirect('/login');
        if (!SubscriptionMember::where('subscribed','=', $member_id)->where('subscriber','=', Auth::id())->exists()){
            $subscription = SubscriptionMember::create([
                'subscriber' => Auth::id(), 
                'subscribed' => $member_id
                    ]);
        }
        return redirect("/members/{$member_id}");
    }

    public function unsubscribe(Request $request, $member_id){
        if (!Auth::check()) return redirect('/login');
        if (SubscriptionMember::where('subscribed','=', $member_id)->where('subscriber','=', Auth::id())->exists()){
            $res = SubscriptionMember::where('subscribed','=', $member_id)->where('subscriber','=', Auth::id())->delete();
        }
        return redirect("/members/{$member_id}");
    }

}
