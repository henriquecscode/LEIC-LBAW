@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/category/new category')

@section('content')

<form  method="POST" action="{{ url('/edit_categories') }}">
  <fieldset>
    <legend><b class="display-4"> Nova Categoria </b></legend><br>
    @csrf
    <label class="mx-4" for="name">Nome</label>
    <div class="d-flex">
      <input class="w-75 mx-3" id="name" type="text" name="name" required>
      <button type="submit">Criar</button>
    </div>
</form>

@endsection