@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path','home')

@section('content')

<div class="container-fluid p-5">

  <div class="d-flex flex-column align-items-center justify-content-center">

    <div>
      <h1 class="text-uppercase text-dark">Trivia(l)</h1>
    </div>

    <div class="border rounded border-15 border-dark text-center">Community Of Believers.</div>

    <div class="mt-4"><b>Categorias</b></div>

    <div class="d-flex box flex-column pt-1 pb-2">

      <div class="d-flex flex-wrap justify-content-around">
        <a class="button" href="{{ url('/questions/category/1') }}">Álgebra</a>
        <a class="button" href="{{ url('/questions/category/4') }}">Geologia</a>
        <a class="button" href="{{ url('/questions/category/7') }}">Inglês</a>
      </div>

      <a class="font-weight-bold mx-auto" href="{{url('/category')}}">Ver mais</a>
    </div>
  </div>

</div>

@endsection
