@php
use Illuminate\Support\Facades\Session;

$app_name =optional(collect(Cache::get('settings'))->where('key','app_name')->first())->properties;
$app_version =optional(collect(Cache::get('settings'))->where('key','app_version')->first())->properties;
$app_animation =optional(collect(Cache::get('settings'))->where('key','app_animation')->first())->getSettingValue('last');
$multi_tenancy =(int)optional(collect(Cache::get('settings'))->where('key','multi_tenancy')->first())->getSettingValue('first');

@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $app_name }}</title>

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
    <style>
        body {
            font-family: Garamond, serif;
        }

    </style>


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @include('Components.Navbar')
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
