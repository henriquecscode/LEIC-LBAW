<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Member;
use App\Models\Question;
use App\Models\SubscriptionPost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{

    public function showCreationForm()
    {
        if (!Auth::check()) return redirect('/login');
        $categories = Category::all();
        return view('pages.creatQuestion', ['categories' => $categories]);
    }

    //     /**
    //  * Get a validator for an incoming registration request.
    //  *
    //  * @param  array  $data
    //  * @return \Illuminate\Contracts\Validation\Validator
    //  */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, );
    // }

    public function create(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        // $title = $request->input('titulo');
        // $content = $request->input('content');
        // $categories = $request->input('categoria'); //Does this work for multiple?
        $this->authorize('post', Member::class);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria' => 'required|array|min:1',
            'categoria.*' => 'required|int|distinct|min:1|exists:category,id'
        ]);

        $post = Post::create([
            'content' => $request['conteudo'],
            'member' => Auth::id()
        ]);

        $question = Question::create([
            'post' => $post->id,
            'title' => $request['titulo']
        ]);

        $question->categories()->attach($request['categoria']);

        return redirect("/question/{$question->post}");
    }

    public function showEditForm($question_id)
    {
        if (!Auth::check()) return redirect('/login');

        $question = Question::findOrFail($question_id);
        $this->authorize('edit', $question->postable());
        $question_categories = $question->categories;

        $categories = Category::all();
        foreach ($categories as $key => $category) {
            if ($question_categories->contains($category)) {
                $categories[$key]->checked = true;
            } else {
                $categories[$key]->checked = false;
            }
        }
        return view('pages.editQuestion', ['id' => $question->post, 'title' => $question->title, 'content' => $question->postable()->content, 'categories' => $categories]);
    }

    public function edit(Request $request, $question_id)
    {
        if (!Auth::check()) return redirect('/login');


        $this->authorize('post', Member::class); // If was block, can't post or edit

        $post = Post::findOrFail($question_id);
        $this->authorize('edit', $post); // If it's theirs or modprivileged

        $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria' => 'required|array|min:1',
            'categoria.*' => 'required|int|distinct|min:1|exists:category,id'
        ]);

        $post->content = $request['conteudo'];
        $post->is_edited = true;
        $post->edition_date = date('Y-m-d H:i:s');
        $post->save();

        $question = Question::findOrFail($question_id);
        $question->title = $request['titulo'];
        $question->save();

        $question->categories()->sync($request['categoria']);

        return redirect("/question/{$question_id}");
    }

    public function resolve($question_id)
    {
        if (!Auth::check()) return redirect('/login');
        $question = Question::findOrFail($question_id);
        $this->authorize('edit', $question->postable());
        $question->is_answered = true;
        $question->save();
        return back();
    }

    public function remove($question_id)
    {
        if (!Auth::check()) return redirect('/login');

        $post = Post::findOrFail($question_id);
        $this->authorize('softDelete', $post);
        $member = $post->owner;
        $member->removePost($post);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $question = Question::findOrFail($id);
        $categories = $question->categories;
        $comments = $post->recursiveChildrenPosts();
        $post->member_likes = $post->voting();

        $canUpdate = $post->canUpdate();


        $subscribed = SubscriptionPost::where('post', '=', $id)->where('member', '=', Auth::id())->exists();
        $canSubscribe = !$subscribed;

        return view('pages.question', [
            'canUpdate' => $canUpdate,
            'question' => $question,
            'post' => $post,
            'categories' => $categories,
            'comments' => $comments,
            'canSubscribe' => $canSubscribe
        ]);
    }

    public function showByTime()
    {
        $questions = Question::all();
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

    public function showByVotes()
    {
        $questions = Question::all();
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

    public function showByNotAnswered()
    {
        $questions = Question::all()->where('is_answered', false);
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
}
