@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/subscriptions')

@section('content')

<a class="button" href="{{ url('/self/subscriptions/posts') }}">Post Subscriptions</a>
<a class="button" href="{{ url('/self/subscriptions/members') }}">Member Subscriptions</a>
<a class="button" href="{{ url('/self/subscriptions/categories') }}">Category Subscriptions</a>
    
@endsection
