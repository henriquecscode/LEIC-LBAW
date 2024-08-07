<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;


class SearchController extends Controller
{

    public function search(Request $request)
    {
        if (!empty($request->categories)) {
            $categoryPosts = DB::table('question_category')->whereIn('category', $request->categories)->distinct('question');
            $myPosts = DB::table('post')
                ->joinSub($categoryPosts, 'categoryPosts', function ($join) {
                    $join->on('categoryPosts.question', '=', 'post.id');
                })
                ->select('post*')
                ->join('question', 'post.id', '=', 'question.post');
        } else {
            $myPosts = DB::table('post')
                ->join('question', 'post.id', '=', 'question.post');
        }

        if (empty($request->attribute)) {
            $request->attribute = 1;
        }

        $myPosts = $myPosts
            ->select(
                'post.*',
                'question.*',
            );
        switch ($request->attribute) {
            case 1:
                // All
                $myPosts = $myPosts->selectRaw("*, ts_rank(post.tsvectors || question.tsvectors, plainto_tsquery('portuguese', '$request->search')) as rank");
                break;
            case 2:
                // Only title
                $myPosts = $myPosts->selectRaw("*, ts_rank(question.tsvectors, plainto_tsquery('portuguese', '$request->search')) as rank");
                break;
            case 3:
                // Only content
                $myPosts = $myPosts->selectRaw("*, ts_rank(post.tsvectors , plainto_tsquery('portuguese', '$request->search')) as rank");
                break;
        }

        if ($request->match) {
            switch ($request->attribute) {
                case 1:
                    // All
                    $myPosts = $myPosts->whereRaw("post.tsvectors || question.tsvectors @@ plainto_tsquery('portuguese', '$request->search')");
                    break;
                case 2:
                    // Only title
                    $myPosts = $myPosts->whereRaw("question.tsvectors @@ plainto_tsquery('portuguese', '$request->search')");
                    break;
                case 3:
                    // Only content
                    $myPosts = $myPosts->whereRaw("post.tsvectors @@ plainto_tsquery('portuguese', '$request->search')");
                    break;
            }
        }
        $myPosts = $myPosts->orderBy('rank', 'DESC')->get();


        // return $myPosts;

        foreach ($myPosts as $key => $post) {
            $myPosts[$key]->owner = Post::findOrFail($post->id)->owner;
        }

        $categories = Category::all();
        return view('pages.search', ['posts' => $myPosts, 'categories' => $categories]);
    }
}
