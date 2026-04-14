<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RackRush')</title>
    <link href="{{ asset('bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/layout.css') }}">
    @stack('styles')
</head>

<body class="@yield('body-class')">
    @include('partials.navbar')

    <div class="page-fill">
        <!-- MAIN CONTENT -->
        <main class="container-fluid px-4 site-main">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @include('partials.modals')

    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        window.showLoginModal = @json($errors->has('email') && !old('first_name'));
        window.showRegisterModal = @json($errors->any() && old('first_name'));
        window.showLogoutToast = @json((bool) session('logout_success'));
    </script>
    <script src="{{ asset('js/layout-cart.js') }}"></script>
    <script src="{{ asset('js/product-card-cart.js') }}?v=6"></script>
    @stack('scripts')
</body>

</html>
