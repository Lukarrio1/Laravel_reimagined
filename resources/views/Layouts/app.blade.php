@php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;


$app_name =optional(collect(Cache::get('settings'))->where('key','app_name')->first())->properties;
$app_version =optional(collect(Cache::get('settings'))->where('key','app_version')->first())->properties;
$app_animation =optional(collect(Cache::get('settings'))->where('key','app_animation')->first())->getSettingValue('last');
$multi_tenancy =optional(collect(Cache::get('settings'))->where('key','multi_tenancy')->first())->getSettingValue('last');

function getUriFromUrl($url)
{

// Get the URI for the provided URL
return URL::to($url);
}


@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .scrollable-div {
            width: auto;
            height: auto;
            overflow: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('home'))? 'red' : 'black' }}" href="{{ url('/') }}">
                    {{$app_name}}
                </a>
                @auth
                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewNodes'))? 'red' : 'black' }}" href="{{route('viewNodes')}}" aria-current="page">
                    Nodes
                </a>

                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewRoles'))? 'red' : 'black' }}" href="{{route('viewRoles')}}">Roles</a>


                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewPermissions'))? 'red' : 'black' }}" href="{{route('viewPermissions')}}">Permissions</a>


                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewUsers'))? 'red' : 'black' }}" href="{{route('viewUsers')}}">Users</a>


                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewCache'))? 'red' : 'black' }}" href="{{route('viewCache')}}">Cache</a>


                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('exportData'))? 'red' : 'black' }}" href="{{route('exportData')}}">Export</a>


                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('importView'))? 'red' : 'black' }}" href="{{route('importView')}}">Import</a>

                @if($multi_tenancy=='true')
                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewCache'))? 'red' : 'black' }}" href="{{route('exportData')}}">Multi Tenancy</a>

                @endif
                <a class="navbar-brand" style="color:{{request()->url()==getUriFromUrl(route('viewSettings'))? 'red' : 'black' }}" href="{{route('viewSettings')}}">Settings</a>


                @endauth

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4 container-fluid">
            <div class="{{$app_animation}}">
                @if(Session::has('message'))
                <p class="alert text-center {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                @yield('content')
            </div>
        </main>
        @yield('scripts')
        <footer class="footer bg-white fixed-bottom">
            <div class="container text-center py-3">
                <span>Version: {{ $app_version }}</span>
            </div>
        </footer>

    </div>
</body>
</html>
