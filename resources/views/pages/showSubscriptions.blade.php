@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/'.$path)

@section('content')

<a class="button" href="{{ url('/self/subscriptions/posts') }}">Post Subscriptions</a>
<a class="button" href="{{ url('/self/subscriptions/members') }}">Member Subscriptions</a>
<a class="button" href="{{ url('/self/subscriptions/categories') }}">Category Subscriptions</a>
@if(!empty($posts) and !$posts->isEmpty())
<div class="container" posts>
    @each('partials.question', $posts, 'post')
</div>
@elseif(!empty($members)and !$members->isEmpty())
<div class="container" members>
    @each('partials.member', $members, 'member')
</div>
@elseif(!empty($categories)and !$categories->isEmpty())
<div class="container"  categories>
    @each('partials.category', $categories, 'category')
</div>
@else
<div>You don't have any {{strtolower($path)}}</div>
@endif

@endsection