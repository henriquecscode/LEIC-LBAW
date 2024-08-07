@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/notifications')

@section('content')

<div class="container pt-3">

    <h4 class="text-center">Notificações</h4>

    <div class="nots">
        <div class="user_name p-2 pl-3">Notificações</div>
        <div class="d-flex flex-column">
            @each('partials.notification', $notifications, 'notification')
</div>
    </div>
</div>


@endsection