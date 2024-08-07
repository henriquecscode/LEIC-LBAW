@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/profile')

@section('content')

<form class="w-75" method="POST" action="{{ url('/members/'. $member->id  . '/edit') }}" enctype="multipart/form-data">
  <fieldset>
    <legend><b class="display-4"> Editar Perfil </b></legend><br>
    @csrf
    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ $member->name }}" required autofocus>

    <label for="password">Password</label>
    <input id="password" type="password" name="password" value="*******" required readonly>

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ $member->email }}" required>

    <label for="date"> Date of birth </label>
    <input id="date" type="date" name="date" value="{{ $member->birth_date }}" required>

    <label for="bio"> Bio </label>
    <input id="bio" type="text" name="bio" value="{{ $member->bio }}">

    <label for="image"> Image </label>
    @if($member->photo != NULL)
        <div>
            <!-- <label for="image"> image </label> -->
            <input id="image" type="image" name="profile picture" alt="Profile picture" width="200" height="250" src="{{'/storage/images/'.$member->photo}}" required readonly>
        </div>
      @endif
    <input type="file" id="file" name="file" accept="image/png, image/jpeg">
    <div class="d-flex justify-content-end">
      <button type="submit">Save</button>
    </div>
  </fieldset>
</form>
<form class="w-75" method="POST" action="{{ url('/removeAccount/'. $member->id ) }}">
  @csrf
  <div id="delete-account-div" style="display:none">
    <div>
      <a class="button button-outline" onclick="cancelDeleteAccount()"> Cancelar </a>
      <button class="button-outline" type="submit">Apagar</button>
    </div>
  </div>

  <div class="d-flex justify-content-end">
    <a class="button" onclick="showDeleteAccout()">Apagar Conta</a>
  </div>
</form>

@endsection