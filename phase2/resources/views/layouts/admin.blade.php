<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RackRush Administrácia')</title>
    <link href="{{ asset('bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
    @stack('styles')
</head>

<body class="admin-body">
    @yield('content')

    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
