@extends('layouts.app')

@section('path', 'home/forgot_password/')

@section('content')
<form method="POST" action="{{ url('/forgot_password') }}" class="sign" enctype="multipart/form-data">
  <fieldset>
    <legend> Recuperar Password </legend>
    {{ csrf_field() }}

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
    <span class="error">
      {{ $errors->first('email') }}
    </span>
  @endif

    <button type="submit">
      Recover password
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
  </fieldset>
</form>
@endsection