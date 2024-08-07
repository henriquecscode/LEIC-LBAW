@extends('layouts.app')

@section('path', 'home/register/')

@section('content')
<form method="POST" action="{{ route('register') }}" class="sign" enctype="multipart/form-data">
    {{ csrf_field() }}
  <fieldset>
    <legend class="text-decoration-underline"> Registo </legend>
    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password_confirmation">Confirm Password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" required>

    @if ($errors->has('birth_date'))
      <span class="error">
          {{ $errors->first('birth_date') }}
      </span>
    @endif

    <label for="birth_date"> Birthdate </label>
    <input id="birth_date" type="date" name="birth_date" value = "{{old('birth_date')}}" required>

    
    <label for="bio"> Bio </label>
    <input id="bio" type="text" name="bio" value="{{ old('bio') }}">

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
  </fieldset>
</form>
@endsection
