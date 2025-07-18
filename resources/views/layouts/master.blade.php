<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>
    <!-- Styles -->
    @include('partials.styles')
    @yield('style')
</head>

<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <!-- Start Header Area -->
            @include('partials.header')
            <!-- End Header Area -->

            @yield('content')

            <!-- Start Footer Area -->
            @include('partials.footer')
            <!-- End Footer Area -->
        </div>
    </div>


    @include('partials.theme_settings')
    @include('partials.scripts')
    @yield('script')
</body>

</html>