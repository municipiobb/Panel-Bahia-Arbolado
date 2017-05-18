<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="{{ asset('lib/fancybox/jquery.fancybox.css?v=2.1.5')}}" type="text/css"
          media="screen"/>
    <script src="{{ asset('js/bootbox.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">

    @yield('styles')

    <style type="text/css">
        .navbar-default .navbar-brand {
            color: #019153;
            font-size: 22px;
            font-weight: bolder;
        }

        .panel-default > .panel-heading {
            color: white;
            background-color: #4c4c4c;
            border: 1px solid #4c4c4c;
        }

        .panel-default {
            border-color: #969494;
        }

        .btn-primary {
            color: #fff;
            border-radius: 4px;
            background-color: #42bb85;
            border-color: #3cab79;
        }

        .btn-primary:hover {
            background-color: #50c591;
            border-color: #50c591;
        }

        .btn-link, .btn-link {
            color: #4c4c4c
        }

        .btn-link:hover, .btn-link:focus {
            color: #2f2f2f
        }

        .btn-primary:active, .btn-primary.active, .open > .btn-primary.dropdown-toggle {
            color: #fff;
            background-color: #359068;
            border-color: #359068;
        }

        .btn-primary:active:hover, .btn-primary:active:focus, .btn-primary:active.focus, .btn-primary.active:hover, .btn-primary.active:focus, .btn-primary.active.focus {
            color: #fff;
            background-color: #359068;
            border-color: #359068;
        }
    </style>
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            @if (!Auth::guest())
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{ action('CensosController@index') }}" {{ Request::is('censos') ? 'class=active' : '' }}>Censos</a>
                    </li>
                    <li>
                        <a href="{{ action('EspeciesController@index') }}" {{ Request::is('especies') ? 'class=active' : '' }}>Especies</a>
                    </li>
                    <li>
                        <a href="{{ action('CallesController@index') }}" {{ Request::is('calles') ? 'class=active' : '' }}>Calles</a>
                    </li>
                    <li>
                        <a href="{{ action('ImagenesController@index') }}" {{ Request::is('imagenes') ? 'class=active' : '' }}>Imágenes</a>
                    </li>
                    <li><a href="{{ url('mapa') }}" {{ Request::is('mapa') ? 'class=active' : '' }}>Mapa</a></li>

                    <!-- <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li> -->
                </ul>
        @endif


        <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/password/change') }}">
                                    Cambiar Contraseña
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                    Salir
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>

                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

@yield('scripts')
</body>
</html>
