@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path','home/category/questions')

@section('content')

<h1 class="ml-5"> {{ $category->name }} </h1>

<div class="ml-5">
    <div>
        @if ($canSubscribe)
            <form method="POST" action="{{ url('subscriptions/categories/' .  $category->id) }}">@csrf
                <input class="button button-outline" type="submit" value="Subscribe">
            </form>
        @else
        <form method="POST" action="{{ url('unsubscriptions/categories/' .  $category->id) }}">@csrf
                <input class="button button-outline" type="submit" value="Unsubscribe">
            </form>
        @endif
    
        <div>
            <input type="text" placeholder="Search category">
            <a class="button text-white" id="category-search">SEARCH</a>
        </div>

        <a class="button text-white" id="category-questions-by-time"> Order by Time </a>
        <a class="button text-white" id="category-questions-by-votes"> Order by Votes </a>
        <a class="button text-white" id="category-questions-by-not-answered"> Order by Not Answered </a>

    </div>

    <div id="category-questions" category-id="{{$category->id}}">
        @include('partials.categoryQuestions')
    </div>
</div>

@endsection