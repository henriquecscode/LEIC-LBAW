@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/admin/appeals')

@section('content')

<h2>Utilizadores bloqueados</h2>

<div class="admin_reported">
  <div class="user_name p-2 pl-3">Appeals</div>
  <div class="d-flex flex-column" id="blocked-list">
   @foreach ($blockedUser as $blocked)
      <div class="report d-flex flex-row" id="blocked-user-{{ $blocked->id }}">
        <div class="id_square">{{ $blocked->id }}</div>
        <div class="p-3">

          <b>Nome:</b><a href="{{ url('members/' . $blocked->id) }}"> {{ $blocked->name }} </a>
          <br>
          <a user-id-unblock="{{ $blocked->id }}" class="button block-remove-button text-white">Unblock</a>
          @if($blocked->appeal!=null)
          <div>
            <b>Pedido:</b> {{ $blocked->appeal }}
          </div>
          @endif
        </div>
      </div>
      
      @endforeach
  </div>
</div>


@endsection