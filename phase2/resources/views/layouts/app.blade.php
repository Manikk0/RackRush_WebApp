<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RackRush')</title>
    <link href="{{ asset('bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/layout.css') }}">
    @stack('styles')
</head>

<body class="@yield('body-class')">
    @include('partials.navbar')

    <!-- MAIN CONTENT -->
    <main class="container-fluid px-4">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.modals')

    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
