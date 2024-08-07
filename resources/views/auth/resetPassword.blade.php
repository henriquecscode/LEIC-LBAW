@extends('layouts.app')

@section('path', 'home/reset_password/')

@section('content')
<form method="POST" action="{{ url('/reset_password') }}" class="sign" enctype="multipart/form-data">
  <fieldset>
    <legend> Reset Password </legend>
    {{ csrf_field() }}
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
    <input type="text" name="token" style="display:none" value="{{$token}}"></input>
    <button type="submit">
      Reset password
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
  </fieldset>
</form>
@endsection
