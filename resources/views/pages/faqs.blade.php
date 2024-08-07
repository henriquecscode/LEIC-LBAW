@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/faqs')

@section('content')

<h1> FAQs</h1>

<span>
    <!-- QUESTION -->
    <h4><b>P: O que preciso para me registar?</b></h4>
    <!-- ANWER -->
    <p>
        <b>R: </b>Para se registar no nosso website é necessário introduzir as seguintes informações:
            <ul class="ml-5">
                <li>Nome (Basta primeiro e último);</li>
                <li>E-mail;</li>
                <li>Password;</li>
                <li>Data de nascimento;</li>
                <li>Uma breve descrição dos seus interesses e temas que ache interessantes.</li>
            </ul>
    </p>

    <br>
    <h4><b>P: Posso colocar ou responder a questões se não estiver registado?</b></h4>
    <p><b>R: Não!</b> Questões e comentários só podem ser criados por utiliadores com sessão iniciada.</p>
    
    <h4><b>P: Porquê que depois de eliminar um comentário este continua visível?</b></h4>
    <p>
        <b>R:</b> Acreditamos que todos os comentários são úteis para a discussão de uma questão.
        Como tal, caso um utilizador já não concorde com o que anteriormente publicou, este tem a opção de eliminar os seus comentários.
        Um comentário desassocia-se do seu autor após eliminação, porém continua visível como anónimo.
    </p>

    <br>
    <p class="text-center">
        Esta página foi útil?
        <br>
        <a href="{{ url('/') }}">Sim!</a>
        <a href="{{ url('/contactos') }}">Não :(</a>
    </p>
        
        
</span>

@endsection