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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.modals')

    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->has('email') && !old('first_name'))
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif

            @if ($errors->any() && old('first_name'))
                var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            @endif

            @if (session('logout_success'))
                var logoutToast = new bootstrap.Toast(document.getElementById('logoutToast'));
                logoutToast.show();
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
