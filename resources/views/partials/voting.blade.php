<div id="post-votes-{{$comment->id}}" post-id="{{$comment->id}}">
    <div class="button button-outline">Votos: {{$comment->likes - $comment->dislikes}}</div>
    @if($comment->member_likes == 1)
    <a class="button button-outline post-upvote-button">^</i></a>
    <a class="button post-downvote-button text-white">v</a>
    @elseif($comment->member_likes == -1)
    <a class="button post-upvote-button text-white">^</i></a>
    <a class="button button-outline post-downvote-button">v</a>
    @else
    <a class="button post-upvote-button text-white">^</i></a>
    <a class="button post-downvote-button text-white">v</a>
    @endif
</div>