@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/categories')

@section('content')

<div class="container-fluid">

  <!-- CATEGORIAS -->
  <div>
    @foreach ($categories as $category)
        <a class="button text-decoration-none" href="{{ url('questions/category/'.  $category->id) }}">{{ $category->name }}</a>
    @endforeach
  </div>
  <div class="line" class="mx-auto mt-3"></div>

  @if($canEdit)
  <div class="d-flex justify-content-end mr-5">
    <a class="button button-outline text-decoration-none" href="{{ url('edit_categories') }}"> Add category </a>
  </div>
  @endif

</div>



@endsection