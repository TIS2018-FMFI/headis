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

    <link rel="apple-touch-icon" sizes="180x180" href="/Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Favicon/favicon-16x16.png">
    <link rel="manifest" href="/Favicon/site.webmanifest">
    <link rel="mask-icon" href="/Favicon/safari-pinned-tab.svg" color="#ff0000">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="theme-color" content="#ffffff">

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
                <a class="navbar-brand" href="/">
                    <img src="/system_images/logo.png" alt="Logo Headis" class="img-fluid logo">
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
                        <li class="nav-item">
                            <a href="{{ route('pyramid') }}" class="nav-link">
                                {{ __('Pyramid') }}
                            </a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login page') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register page') }}</a>
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
                            @if ($currentAuthUser->isRedactor)
                                <li class="nav-item">
                                    <a href="{{ route('manager') }}" class="nav-link">
                                        {{ __('Manager') }}
                                    </a>
                                </li>
                            @endif
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
        <main id="main">
            @yield('content')
        </main>
        <footer class="footer">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-sm-4 col-12 mb-sm-0 mb-2 text-center">
                        <a href="mailto:martin.dovicak@gmail.com"><font-awesome-icon icon="envelope" size="lg" class="mr-2"></font-awesome-icon>martin.dovicak@gmail.com</a>
                    </div>
                    <div class="col-sm-4 col-12 mb-sm-0 mb-2 text-center">
                        <a href="https://www.facebook.com/HeadisSK/"><font-awesome-icon :icon="['fab', 'facebook-f']" size="lg"></font-awesome-icon></a>
                    </div>
                    <div class="col-sm-4 col-12 mb-sm-0 mb-2 text-center">
                        <a href="http://ktvs.fmph.uniba.sk/article_forms.php?section=6&article_id=599&fbclid=IwAR0IBoY_zqTGf-J9bQN4xIi0QQxvm3MQWDV6fKaj8WXwY6NwSmm_Odet4vg"><font-awesome-icon icon="book" size="lg" class="mr-2"></font-awesome-icon>{{ __('Rules') }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <span>Copyright © {{ Carbon\Carbon::today()->year }}</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
