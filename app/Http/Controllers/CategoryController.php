<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Question;
use App\Models\SubscriptionCategory;

class CategoryController extends Controller
{

    public function show($id)
    {
        $category = Category::findorFail($id);
        $questions = $category->questions;
        foreach ($questions as $key => $question) {
            $post = $question->postable();
            $member = $post->owner;
            $post->is_answered = $question->is_answered;
            $post->title = $question->title;
            $questions[$key] = $post;
        }

        
        $subscribed = SubscriptionCategory::where('category','=', $id)->where('member','=', Auth::id())->exists();
        $canSubscribe = !$subscribed;
        return view('pages.category', ['category' => $category, 'questions' => $questions, 'id' => $id, 'canSubscribe' => $canSubscribe]);
    }


    public function showAll()
    {
        $categories = Category::all();

        if (Auth::check()) {
            $canEdit = Auth::user()->can('editCategories', Member::class);
        } else {
            $canEdit = false;
        }

        return view('pages.categories', ['categories' => $categories, 'canEdit' => $canEdit]);
    }

    public function showByTime($id)
    {
        $category = Category::findorFail($id);
        $questions = $category->questions;
        foreach ($questions as $key => $question) {
            $post = $question->postable();
            $member = $post->owner;
            $post->is_answered = $question->is_answered;
            $post->title = $question->title;
            $questions[$key] = $post;
        }
        $sorted = $questions->sortByDesc('publish_date');
        return view('partials.categoryQuestions', ['questions' => $sorted]);
    }

    public function showByVotes($id)
    {
        $category = Category::findorFail($id);
        $questions = $category->questions;
        foreach ($questions as $key => $question) {
            $post = $question->postable();
            $member = $post->owner;
            $post->is_answered = $question->is_answered;
            $post->title = $question->title;
            $questions[$key] = $post;
        }

        $sorted = $questions->sortBy([
            fn ($a, $b) => ($b->likes - $b->dislikes) <=>  ($a->likes - $a->dislikes),
            fn ($a, $b) => $b->publish_date <=> $a->publish_date
        ]);

        return view('partials.categoryQuestions', ['questions' => $sorted]);
    }

    public function showByNotAnswered($id)
    {
        $category = Category::findorFail($id);
        $questions = $category->questions->where('is_answered', false);
        foreach ($questions as $key => $question) {
            $post = $question->postable();
            $member = $post->owner;
            $post->is_answered = $question->is_answered;
            $post->title = $question->title;
            $questions[$key] = $post;
        }
        $sorted = $questions->sortByDesc('publish_date');
        return view('partials.categoryQuestions', ['questions' => $sorted]);
    }

    public function search(Request $request, $id)
    {
        $search = $request->all()['search'];
        $questions = DB::table('question_category')
            ->where('category', '=', $id)
            ->join('post', 'post.id', '=', 'question')
            ->select('post.*')
            ->join('question', 'id', '=', 'question.post')
            ->select(
                'post.*',
                'post.tsvectors as post_tsvectors',
                'question.tsvectors as question_tsvectors',
                'question.*',
            )
            ->selectRaw("*, ts_rank(post.tsvectors || question.tsvectors, plainto_tsquery('portuguese', '$search')) as rank")
            ->whereRaw("post.tsvectors || question.tsvectors @@ to_tsquery('portuguese', '$search')")
            ->orderBy('rank', 'DESC')
            ->get();
        return view('partials.categoryQuestions', ['questions' => $questions]);
    }
}
