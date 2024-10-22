{{-- resources/views/layouts/partials/head.blade.php --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="J.J.Nhytros">

    <meta name="msapplication-TileColor" content="#B22222">
    <meta name="theme-color" content="#00335B" />
    @yield('meta')
    <link rel="preload" href="{{ asset('fonts/EasyReading.woff') }}" as="font" type="font/woff" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ath_bs5.css') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap-icons/font/bootstrap-icons.css') }}">

    <title inertia>{{ config('app.name') }}{{ isset($title) ? ' | ' . $title : '' }}</title>
</head>
