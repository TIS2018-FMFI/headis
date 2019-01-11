<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login page') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register page') }}</a>
                                @endif
                            </li>
                        @else
                            @if ($currentAuthUser && $currentAuthUser->currentMatch())
                                <li class="nav-item">
                                    <a href="/matches/{{ $currentAuthUser->currentMatch()->id }}" class="nav-link">Aktuálny zápas</a>
                                </li>
                            @elseif ($currentAuthUser && \App\User::currentChallenge($currentAuthUser))
                                <li class="nav-item">
                                    <a href="/challenges/{{ \App\User::currentChallenge($currentAuthUser)->id }}" class="nav-link">Aktuálna výzva</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('pyramid') }}" class="nav-link">
                                    {{ __('Pyramid') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/users/{{ auth()->user()->id }}" class="nav-link">
                                    {{ auth()->user()->user_name }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf

                                    <button class="btn-link nav-link border-0" type="submit">{{ __('Logout') }}</button>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
