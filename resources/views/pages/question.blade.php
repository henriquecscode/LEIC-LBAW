@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/question')

@section('content')

<div class="container">

  <div class="container d-flex flex-row justify-content-around align-items-start">
    <div class="mytable mr-5">
      <div class="user_name d-flex justify-content-between align-items-center">
        @if( $post->member != -1)
        <a class="ml-2" href="{{url('members/'.$post->member)}}"> {{$post->owner->name}} </a>
        @else
        <a> {{$post->owner->name}} </a>
        @endif
        <div class="date-size">{{$post->publish_date}}</div>
      </div>
      <h3 class="ml-4 mt-3"><a href="{{ url('question/' . $question->post) }}"> {{ $question->title }} </a></h3>
      <div class="line mx-auto"></div>
      <h4 class="ml-5 mt-3"><b>Pergunta:</b> {{ $post -> content }}</h4>
      <div class="ml-5 mt-3 small-letters d-flex flex-row justify-content-between align-center">
        @if ($post->is_edited)
        Editada
        <br>
        @endif
        @if ($question->is_answered)
        Respondida
        @endif
        @include('partials.voting', ['comment' => $post])
      </div>
      <!-- <div class="d-flex justify-content-end"> -->
        <div class="ml-5">
          @include('partials.subscribePost', ['id' => $post->id, 'canSubscribe' => $canSubscribe])
        </div>
        <div class="ml-3">
          @include('partials.report')
        </div>
      <!-- </div> -->

      <div class="ml-4 line"></div>
      <br>
      <div class="ml-5 pb-3">
        <h4><b>Respostas:</b></h4>

        <!--RESPOSTAS-->
        <div id="comment-{{$post->id}}-comments" class="ml-2">
          @each('partials.comment', $comments, 'comment')
        </div>
      </div>
    </div>

    <!-- Categorias 2 -->
    <div class="categ p-4">
      <h3> Categorias </h3>
      <ul>
        @foreach ($categories as $category)
        <li><a class="text-capitalize" target="_blank" href="{{url('questions/category/'.$category->id)}}"> {{ $category->name }} </a></li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- RESPONDER -->
  <div class="answer-question">

    <div id="post-reply">
      <label for="conteudo"><b class="display-4">Responder:</b></label>
      <textarea id="conteudo" type="text" name="conteudo" value="{{old('conteudo')}}" required></textarea>
      <br>
      <a class="button text-white" responding-id="{{$post->id}}" id="post-comment">Post</a>
    </div>

    <!--AÇÕES-->
    @if ($canUpdate)
    <a class="button button-outline" href="{{ url('question/edit_question/'.  $post->id) }}"> Editar questão </a>
    <a class="button button-outline" onclick="document.getElementById('question-resolve-form').submit();"> Editar resolver </a>
    <a class="button button-outline" onclick="document.getElementById('question-remove-form').submit();"> Remover questao </a>

    <form id="question-resolve-form" method="POST" action="{{ url('question/resolve/'.  $post->id) }}" style="display:none">
      {{ csrf_field() }}
    </form>

    <form id="question-remove-form" method="POST" action="{{ url('question/remove_question/'.  $post->id) }}" style="display:none">
      {{ csrf_field() }}
    </form>
    @endif

  </div>

</div>


@endsection