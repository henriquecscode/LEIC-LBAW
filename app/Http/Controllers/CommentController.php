<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $parent_id)
    {

        if (!Auth::check()) return response('/login', 401);
        
        $this->authorize('post', Member::class);

        $request->validate([
            'conteudo' => 'required|string'
        ]);

        $post = Post::create([
            'content' => $request['conteudo'],
            'member' => Auth::id()
        ]);

        $comment = Comment::create([
            'post' => $post->id,
            'responding' => $parent_id
        ]);

        $post = Post::find($post->id);
        $post->owner;
        $post->children_posts = [];
        $post->canUpdate = $post->canUpdate();

        return view('partials.comment', ['comment' => $post]);
        // return redirect("question/{$parent_id}");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $comment_id)
    {
        if (!Auth::check()) return redirect('/login');

        $this->authorize('post', Member::class); // If was block, can't post or edit

        $post = Post::findOrFail($comment_id);
        $this->authorize('edit', $post); // If it's theirs or modprivileged

        $data = $request->all();
        if ($data['conteudo'] != "") {
            $post->content = $data['conteudo'];
            $post->is_edited = true;
            $post->edition_date = date('Y-m-d H:i:s');
        }
        $post->save();

        return $post;
    }

    public function remove($comment_id)
    {
        if (!Auth::check()) return redirect('/login');

        $post = Post::findOrFail($comment_id);
        $this->authorize('softDelete', $post);
        $member = $post->owner;
        if ($post->member != -1) {
            $post->member = -1;
            $post->save();

            $member->likes -= $post->likes;
            $member->likes += $post->dislikes;
            $member->subscribingPosts()->detach($comment_id);
            $member->save();
        }

        $post = Post::find($post->id);
        $post = $post->displayableComment();
        return view('partials.comment', ['comment' => $post]);
    }
}
