@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/feed')

@section('content')

<div class="container pt-3">

    <h4 class="text-center">Feed</h4>
    
    <div class="mytable mx-auto">

        <div class="user_name">
            @if( $post->member != -1)
            <a class="ml-2" href="{{url('members/'.$post->member)}}"> {{$post->owner->name}} </a>
            @else
            <a> {{$post->owner->name}} </a>
            @endif
        </div>
        
        <h3 class="ml-4 mt-3"><a href="{{ url('question/' . $question->post) }}"> {{ $question->title }} </a></h3>
        
        <div class="line mx-auto"></div>
        <p class="ml-5 mt-3"> {{ $post -> content }}</p>
    </div>
    <div id="next_feed" class=" d-flex justify-content-end mx-auto">
        <a href="{{ url('feed/')}}"> Next </a>
    </div>
</div>


@endsection