@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/admin')

@section('content')
    <div class="container text-center">
        <p>
            <b>
                Bem vindo, Administrador!
                <br>Vamos lá bloquear umas pessoas.
            </b>
        </p>
        <a class="button" href="{{ url('/reports') }}">REPORTS</a>
        <a class="button" href="{{ url('/appeals') }}">APPEALS</a>
    </div>
@endsection