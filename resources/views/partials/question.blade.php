<div class="mytable mx-auto">

    <div id="user_name">
        @if( $post->member != -1)
        <a class="ml-2" href="{{url('members/'.$post->member)}}"> {{$post->owner->name}} </a>
        @else
        <a> {{$post->owner->name}} </a>
        @endif
    </div>

    <h3 class="ml-4 mt-3"><a href="{{ url('question/' . $post->id) }}"> {{ $post->title }} </a></h3>

    <div class="line mx-auto"></div>
    <p class="ml-5 mt-3"> {{ $post -> content }}</p>
</div>