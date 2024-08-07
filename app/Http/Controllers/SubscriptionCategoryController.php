<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionCategoryController extends Controller
{
    public function subscribe(Request $request, $category_id){
        if (!Auth::check()) return redirect('/login');
        if (!SubscriptionCategory::where('category','=', $category_id)->where('member','=', Auth::id())->exists()){
            $subscription = SubscriptionCategory::create([
                'member' => Auth::id(), 
                'category' => $category_id
                    ]);
        }
        return redirect("/questions/category/{$category_id}");
    }

    public function unsubscribe(Request $request, $category_id){
        if (!Auth::check()) return redirect('/login');
        if (SubscriptionCategory::where('category','=', $category_id)->where('member','=', Auth::id())->exists()){
            $res = SubscriptionCategory::where('category','=', $category_id)->where('member','=', Auth::id())->delete();
        }
        return redirect("/questions/category/{$category_id}");
    }

}
