@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/feed')

@section('content')

<div class="container pt-3">


    <div class="d-flex flex-wrap">
        @foreach ($categories as $category)
        <div class="d-flex flex-row align-self-center mx-4">
            <label class="my-auto mx-2" for="{{ $category->name }}">{{ $category->name }}</label>
            <input form="search-form" class="my-auto" type="checkbox" id="{{ $category->name }}" name="categories[]" value="{{ $category->id }}">
        </div>
        @endforeach

    </div>
    <div class="d-flex flex-row align-self-center mx-4">
        <label class="my-auto mx-2">Exact match</label>
        <input form="search-form" class="my-auto" type="radio" name="match" value="1">

        <label class="my-auto mx-2">Ranked match</label>
        <input form="search-form" class="my-auto" type="radio" name="match" value="0">
    </div>
    <div class="d-flex flex-row align-self-center mx-4">

        <label class="my-auto mx-2">All</label>
        <input form="search-form" class="my-auto" type="radio" name="attribute" value="1" checked>

        <label class="my-auto mx-2">Only Title</label>
        <input form="search-form" class="my-auto" type="radio" name="attribute" value="2">

        <label class="my-auto mx-2">Only Content</label>
        <input form="search-form" class="my-auto" type="radio" name="attribute" value="3">
    </div>
</div>

<h4 class="text-center">Search</h4>
@if($posts->isEmpty())
No questions
@else
@each('partials.question', $posts, 'post')
@endif
</div>


@endsection