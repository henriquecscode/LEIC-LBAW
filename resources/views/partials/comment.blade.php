<div id="comment-div-{{$comment->id}}" comment-id="{{$comment->id}}">
    @if ($comment->member != -1)
    <a href="{{url('members/'.$comment->member)}}"><b>{{$comment->owner->name}}: &nbsp</b></a>
    @else
    <a><b>{{$comment->owner->name}}: &nbsp</b></a>
    @endif
    <span id="comment-{{$comment->id}}">{{$comment->content}}</span>

    @if ($comment->is_edited)
    <span class="ml-5 mt-3 small-letters">Editado</span>
    @endif

    @include('partials.voting')

    @if($comment->canSubscribe())

        <form method="POST" action="{{ url('subscriptions/post/' . $comment->id) }}">@csrf
            <input class="button button-outline" type="submit" value="Subscribe">
        </form>

    @else
        <form method="POST" action="{{ url('unsubscriptions/post/' .  $comment->id) }}">@csrf
            <input class="button button-outline" type="submit" value="Unsubscribe">
        </form>
    @endif


    <div id="comment-reply-{{$comment->id}}" style="display:none">
        <textarea name="conteudo" comment-id="{{$comment->id}}">{{old('conteudo')}}</textarea>
        <a class="button button-outline" onclick="cancelReply({{$comment->id}})"> Cancelar </a>
        <a class="button button-outline comment-reply-button">Postar</a>
    </div>
    <a class="button text-white" onclick="showReply({{$comment->id}})">Responder</a>

    @include('partials.report', ['post' => $comment])

    @if ($comment->canUpdate)
    <div id="comment-edit-{{$comment->id}}" style="display:none">
        <textarea name="conteudo" comment-id="{{$comment->id}}">{{$comment->content}}</textarea>
        <a class="button button-outline" onclick="cancelEdit({{$comment->id}})"> Cancelar </a>
        <a class="button button-outline comment-edit-button"> Guardar </a>
    </div>
    <a class="button button-outline" onclick="showEdit({{$comment->id}})"> Editar comentário </a>
    <a class="button button-outline comment-remove-button"> Remover </a>
    @endif
    <br>

    <div id="comment-{{$comment->id}}-comments" style="padding-left:10%;">
        @each('partials.comment', $comment->children_posts, 'comment')
    </div>
</div>