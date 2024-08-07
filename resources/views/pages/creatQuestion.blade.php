@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/new question')

@section('content')

<form method="POST" action="{{ url('/create_question/') }}">
  <fieldset>
    <legend><b class="display-4"> Nova Questão </b></legend><br>
    <label for="titulo"><b class="display-4">Título</b></label>
    <input class="w-75" id="titulo" type="text" name="titulo" value="{{old('titulo')}}" required>@csrf
    @if ($errors->has('titulo'))
    <span class="error">
      {{ $errors->first('titulo') }}
    </span>
    @endif

    <label for="conteudo"><b class="display-4">Conteúdo</b></label>
    <textarea id="conteudo" type="text" name="conteudo" required>{{old('conteudo')}}</textarea>

    @if ($errors->has('categoria'))
    <span class="error">
      {{ $errors->first('categoria') }}
    </span>
    @endif
  
    <div class="d-flex flex-wrap">
      @foreach ($categories as $category)
      <div class="d-flex flex-row align-self-center mx-4">
        <label class="my-auto mx-2" for="{{ $category->name }}">{{ $category->name }}</label>
        <input class="my-auto" type="checkbox" id="{{ $category->name }}" name="categoria[]" value="{{ $category->id }}">
      </div>
      @endforeach
    </div>
    <button type="submit" class="my-3 mx-4">Post</button>
  <fieldset>
</form>

@endsection