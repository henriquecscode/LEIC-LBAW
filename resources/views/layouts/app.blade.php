<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') </title>

    <!-- <link rel="icon" href="/favicon.ico" type="image/x-icon /"> -->
    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <!-- Bootstrap CSS -->
  </head>
  <body>
    <main>
      <header class="d-flex flex-row justify-content-between">
        <div class="d-flex flex-row align-items-center">
          <h1 class="ml-4"><a href="{{ url('/feed') }}">Trivia(l)</a></h1>
  
          @if (auth()->check() && auth()->user()->isAdmin())
              <a href="{{ url('/admin') }}"><div id="admin_block" class="ml-5 inline text-center">A</div></a>
          @endif
          @if(Auth::check())
            @include('partials.popUpNotifications', ['notifications' => $popUpNotifications])
          @endif
        </div>

        @if (Auth::check())
        <div>
          <a href="{{ url('/members/'. Auth::user()->id) }}" class="mx-3">{{ Auth::user()->name }}</a>
          <a href="{{ url('create_question') }}" class="button mx-2">Nova questão</a>
          <a class="button" href="{{ url('/logout') }}"> Logout </a> 
        </div>
        @else
        <div>
          <a class="button" href="{{ url('/login') }}"> Login </a>
          <a class="button" href="{{ url('/register') }}"> Sign up </a>
        </div> 
        @endif
      </header>

      <div class="breadcrumb mx-5 my-4 d-flex justify-content-between">
        <!-- path (brbeadcrumbs) -->
        @section('path')
        <div>
          @php
            $str = app()->view->getSections()['path'];
            $exists = strpos($str,"/");
          @endphp
          @while($exists)
            @php
              $word = strtok($str,"/"); 
              $page = "/".$word;
              $str = substr($str,strlen($word)+1);
              $exists = strpos($str,"/");
            @endphp
              <span>/</span>
              <a href="{{ $page }}">{{$word}}</a>
          @endwhile
            <span>/ {{$str}}</span>
        </div>
        
        <!-- SEARCH BAR -->
        <div>
          <form id="search-form" method="GET" action="{{url('/search')}}" class="d-flex flex-row">
            @csrf
            <input type="text" placeholder="Search" name="search">
            <button type="submit" class="ml-2">SEARCH</button>
          </form>
        </div>

      </div>


      <section id="content" class="page-content mx-5">
        @yield('content')
      </section>
      <footer class="fixed-bottom">
        <div class="mx-5 d-flex justify-content-between">
            <a href="{{ url('/about_us') }}"><b>Sobre nós</b></a>
            <a href="{{ url('/faqs') }}"><b>FAQs</b></a>
            <a href="{{ url('/contactos') }}"><b>Contacte-nos</b></a>
        </div>
      </footer>
    </main>
  
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
