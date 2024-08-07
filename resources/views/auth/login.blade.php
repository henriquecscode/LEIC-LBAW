@extends('layouts.app')

@section('path', 'home/login/')

@section('content')
<form method="POST" action="{{ route('login') }}" class="sign">
    <fieldset>
        <legend>Login</legend>
        {{ csrf_field() }}

        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @if ($errors->has('email'))
            <span class="error">
            {{ $errors->first('email') }}
            </span>
        @endif

        <label for="password" >Password</label>
        <input id="password" type="password" name="password" required>
        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif

        <!-- <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label> -->

        <a class="" href="{{ url('/forgot_password') }}">Recover password</a>
        <br>
        <!-- <div class="d-flex align-items-center justify-content-center"> -->
            <button type="submit">
                Login
            </button>
            <!-- <a class="button" href="{{ route('login') }}">Login</a> -->
            <a class="button button-outline" href="{{ route('register') }}">Register</a>
        <!-- </div> -->
    </fieldset>
</form>
@endsection
