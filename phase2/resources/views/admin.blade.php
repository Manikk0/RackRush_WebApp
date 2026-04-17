@extends('layouts.admin')

@section('title', 'RackRush Administrácia')

@section('content')
    @guest
        <div id="admin-login-view" class="admin-login-wrapper d-flex align-items-center justify-content-center vh-100">
            <div class="admin-login-card p-4 shadow-lg text-center mx-3">
                <img src="{{ asset('assets/logo.png') }}" alt="RackRush Admin" class="admin-logo mb-3">
                <h4 class="mb-4 admin-title">Administrácia</h4>

                <form id="admin-login-form" method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label small mb-1" for="admin-email">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="admin-email"
                            name="email" value="{{ old('email') }}" required autocomplete="username"
                            placeholder="vas@email.sk">
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label small mb-1" for="admin-password">Heslo</label>
                        <input type="password" class="form-control" id="admin-password" name="password" required
                            autocomplete="current-password" placeholder="Vaše heslo">
                    </div>
                    @error('email')
                        <div class="text-danger small mb-3 text-start">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary w-100 mb-3 py-2 admin-btn">Prihlásiť sa</button>
                </form>
                <div class="mt-2 d-flex justify-content-center">
                    <a href="{{ route('index') }}"
                        class="text-decoration-none admin-back-link small d-flex align-items-center gap-2">
                        <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs admin-back-icon"> Späť do
                        obchodu
                    </a>
                </div>
            </div>
        </div>
    @endguest

    @auth
        <div id="admin-dashboard-view">
        <!-- ADMIN HEADER -->
        <header id="admin-header" class="fixed-top shadow-sm admin-navbar">
            <div class="container-fluid px-4">
                <div class="row align-items-center py-2 py-md-3">
                    <div class="col-6 col-md-3 d-flex align-items-center gap-2">
                        <button class="btn d-md-none p-0 border-0 me-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#admin-sidebar" aria-expanded="false" aria-controls="admin-sidebar">
                            <img src="{{ asset('assets/burger-menu.png') }}" alt="Menu" class="icon-md icon-white">
                        </button>
                        <a href="{{ route('admin') }}" class="d-inline-block">
                            <img src="{{ asset('assets/logo.png') }}" alt="RackRush"
                                class="logo-placeholder admin-header-logo">
                        </a>
                        <span class="admin-header-title d-none d-md-inline ms-2">Administrácia</span>
                    </div>

                    <div class="col-6 col-md-9 d-flex justify-content-end align-items-center gap-4">
                        <div class="admin-user-info d-none d-sm-flex align-items-center gap-2">
                            <img src="{{ asset('assets/user.png') }}" alt="Admin"
                                class="icon-md icon-white admin-user-icon-img">
                            <span class="text-white small fw-bold">Hlavný administrátor</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="redirect" value="admin">
                            <button type="submit" id="admin-logout-btn"
                                class="btn btn-outline-custom btn-sm py-1 px-3 d-flex align-items-center gap-2"
                                title="Odhlásiť sa">
                                <img src="{{ asset('assets/logout.png') }}" class="icon-sm icon-theme">
                                <span class="d-none d-sm-inline">Odhlásiť sa</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- ADMIN MAIN CONTENT -->
        <div class="container-fluid admin-main-container">
            <div class="row">
                <!-- SIDEBAR NAVIGATION -->
                <nav id="admin-sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-space-indigo">
                    <div class="position-sticky pt-3 pb-3">
                        <ul class="nav flex-column admin-nav-list" id="admin-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#" data-section="overview">
                                    <img src="{{ asset('assets/check.png') }}" class="icon-sm me-2 icon-theme">
                                    Prehľad
                                </a>
                            </li>
                            <li class="nav-item px-3 mt-3 mb-1">
                                <small class="text-uppercase admin-nav-label">Správa
                                    e-shopu</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-section="products">
                                    <img src="{{ asset('assets/product.png') }}"
                                        class="icon-md icon-white me-2 admin-nav-icon">
                                    Produkty
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-section="orders">
                                    <img src="{{ asset('assets/box.png') }}"
                                        class="icon-md icon-white me-2 admin-nav-icon">
                                    Objednávky
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-section="customers">
                                    <img src="{{ asset('assets/user.png') }}"
                                        class="icon-md icon-white me-2 admin-nav-icon">
                                    Zákazníci
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- SECTION WRAPPER -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">

                    <!-- OVERVIEW SECTION -->
                    <div id="section-overview" class="admin-section">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-2 mb-4 border-bottom border-dusk-blue">
                            <h1 class="h3 text-white m-0">Prehľad</h1>
                        </div>

                        <div class="alert alert-info bg-space-indigo border-tropical-teal text-white mb-4 shadow-sm admin-alert-welcome"
                            role="alert">
                            <img src="{{ asset('assets/speech-bubble.png') }}" class="icon-sm icon-theme me-2">
                            <strong>Vitajte späť v administrácii!</strong> Dnes už bolo vytvorených 12 objednávok!.
                        </div>

                        <div class="row mt-4">
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card admin-stats-card h-100">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-tropical-teal text-uppercase mb-1 admin-stats-label">
                                                    Celkom produktov</div>
                                                <div class="h3 mb-0 font-weight-bold text-white">458</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card admin-stats-card h-100">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-tropical-teal text-uppercase mb-1 admin-stats-label">
                                                    Dnešné objednávky</div>
                                                <div class="h3 mb-0 font-weight-bold text-white">12</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card admin-stats-card h-100">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-tropical-teal text-uppercase mb-1 admin-stats-label">
                                                    Registrovaní užívatelia</div>
                                                <div class="h3 mb-0 font-weight-bold text-white">89</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PRODUCTS SECTION -->
                    <div id="section-products" class="admin-section d-none">
                        <div class="pt-4 pb-2 mb-4 border-bottom border-dusk-blue">
                            <h1 class="h3 text-white m-0 text-center">Produkty</h1>
                        </div>
                        
                        <div class="d-flex justify-content-center mb-4">
                            <div class="d-flex gap-2 align-items-center" style="max-width: 600px; width: 100%;">
                                <!-- Search Bar -->
                                <form class="header-search-form flex-grow-1" role="search" onsubmit="return false;">
                                    <div class="input-group search-container">
                                        <input type="search" id="admin-product-search" class="form-control"
                                            placeholder="Hľadať produkty..." maxlength="120" autocomplete="off"
                                            aria-label="Hľadať produkty">
                                        <button type="button" class="btn btn-search-submit" id="admin-search-btn" aria-label="Hľadať">
                                            <img src="{{ asset('assets/search.png') }}" class="icon-sm" alt="">
                                        </button>
                                    </div>
                                </form>
                                <button class="btn btn-teal d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addProductModal" style="height: 38px; white-space: nowrap; min-width: 160px; padding: 0 16px;">
                                    <img src="{{ asset('assets/plus.png') }}"
                                        class="icon-sm admin-btn-plus-icon"> Pridať produkt
                                </button>
                            </div>
                        </div>

                        <div class="card bg-space-indigo border-dusk-blue shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover mb-0 admin-table">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">ID</th>
                                                <th>Názov</th>
                                                <th>Kategória</th>
                                                <th>Cena</th>
                                                <th>Skladom</th>
                                                <th class="text-end pe-4">Akcie</th>
                                            </tr>
                                        </thead>
                                        <tbody id="admin-product-list">
                                            <tr>
                                                <td class="ps-4">#1234</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="product-thumb-sm bg-prussian-blue rounded"></div>
                                                        Čerstvé hrušky
                                                    </div>
                                                </td>
                                                <td>Ovocie a zelenina</td>
                                                <td>2.49 €</td>
                                                <td><span class="badge bg-success">Na sklade (120ks)</span></td>
                                                <td class="text-end pe-4">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button class="btn btn-edit-icon" title="Upraviť"><img
                                                                src="{{ asset('assets/pencil.png') }}"
                                                                class="icon-xs icon-white"></button>
                                                        <button class="btn btn-delete-icon" title="Vymazať"><img
                                                                src="{{ asset('assets/trash.png') }}"
                                                                class="icon-xs icon-white"></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PLACEHOLDER SECTIONS -->
                    <div id="section-orders" class="admin-section d-none">
                        <div class="pt-4">
                            <h1 class="h3 text-white">Objednávky</h1>
                        </div>
                    </div>
                    <div id="section-customers" class="admin-section d-none">
                        <div class="pt-4">
                            <h1 class="h3 text-white">Zákazníci</h1>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </div>

    <!-- ADD PRODUCT MODAL -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content auth-modal p-2 shadow-lg">
                <div class="modal-header border-bottom border-dusk-blue pb-3">
                    <h4 class="modal-title m-0" id="addProductModalLabel">Pridať nový produkt</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Zavrieť"></button>
                </div>
                <div class="modal-body py-4 px-4">
                    <form id="add-product-form">
                        <div class="row g-4">
                            <!-- FORM FIELDS -->
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label small mb-1 text-white-50">Názov produktu</label>
                                    <input type="text" class="form-control" id="p-name" required
                                        placeholder="napr. Domáce jablká">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small mb-1 text-white-50">Popis</label>
                                    <textarea class="form-control" id="p-desc" rows="4" required
                                        placeholder="Podrobný popis produktu..."></textarea>
                                </div>
                                <div class="row">
                                    <div class="dropdown custom-admin-dropdown" id="category-dropdown-container">
                                        <button
                                            class="btn auth-modal-input w-100 d-flex justify-content-between align-items-center dropdown-toggle custom-dropdown-btn"
                                            type="button" id="categoryDropdownBtn" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="selected-category-text">Vybrať kategóriu...</span>
                                            <img src="{{ asset('assets/chevron_right.png') }}"
                                                class="icon-xs chevron-icon">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark w-100 shadow-lg scrollable-menu"
                                            aria-labelledby="categoryDropdownBtn">
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Ovocie a zelenina">Ovocie a zelenina</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Mliečne a chladené">Mliečne a chladené</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Mäso a ryby">Mäso a ryby</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Pečivo">Pečivo</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Trvanlivé potraviny">Trvanlivé potraviny</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Mrazené výrobky">Mrazené výrobky</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Nápoje">Nápoje</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Sladkosti a slané">Sladkosti a slané</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Drogéria">Drogéria</a></li>
                                            <li><a class="dropdown-item category-select-item" href="#"
                                                    data-value="Pre zvieratá">Pre zvieratá</a></li>
                                        </ul>
                                        <input type="hidden" id="p-category" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label small mb-1 text-white-50">Cena (€)</label>
                                            <input type="number" step="0.01" max="999.99" class="form-control" id="p-price"
                                                required placeholder="0.00" maxlength="6">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label small mb-1 text-white-50">Skladom (ks)</label>
                                            <input type="number" min="0" max="999" step="1" class="form-control"
                                                id="p-stock" required placeholder="0" maxlength="3">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- IMAGE UPLOAD -->
                            <div class="col-md-5">
                                <label class="form-label small mb-1 text-white-50 d-block">Fotografie (min. 2)</label>
                                <div id="product-images-container" class="d-flex flex-wrap gap-2 mb-3">
                                    <div
                                        class="image-upload-wrapper p-3 border border-dashed border-dusk-blue rounded text-center d-flex align-items-center justify-content-center cursor-pointer"
                                        onclick="document.getElementById('p-img-upload').click()">
                                        <input type="file" id="p-img-upload" class="d-none" accept="image/*"
                                            multiple>
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <img src="{{ asset('assets/plus.png') }}"
                                                class="icon-sm icon-white opacity-75">
                                            <span class="text-white-50 upload-label-text">Pridať</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top border-dusk-blue pt-3 mt-4 px-0">
                            <button type="button" class="btn btn-outline-custom px-4"
                                data-bs-dismiss="modal">Zrušiť</button>
                            <button type="submit" class="btn btn-teal px-4 fw-bold">Uložiť produkt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content auth-modal p-2 shadow-lg">
                <div class="modal-header pb-3">
                    <h4 class="modal-title m-0" id="deleteProductModalLabel">Potvrdiť vymazanie</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Zavrie"></button>
                </div>
                <div class="modal-body py-4 px-4">
                    <p class="text-white mb-3">Naozaj chcete vymaza tento produkt?</p>
                    <input type="hidden" id="delete-product-id">
                </div>
                <div class="modal-footer px-0 border-0">
                    <button type="button" class="btn btn-outline-custom px-4"
                        data-bs-dismiss="modal">Zrušiť</button>
                    <button type="button" class="btn btn-danger px-4 fw-bold" id="confirm-delete-btn">Vymazať</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATIONS -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3 admin-toast-container">
        <!-- LOGOUT TOAST -->
        <div id="adminLogoutToast" class="toast align-items-center text-white bg-dark border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Boli ste úspešne odhlásený z administrácie.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
        <!-- LOGIN SUCCESS TOAST -->
        <div id="adminLoginToast" class="toast align-items-center border-0 toast-success-custom" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-bold">
                    Úspešne prihlásený!
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endauth
@endsection

@auth
    @push('scripts')
        <script src="{{ asset('js/admin.js') }}"></script>
    @endpush
@endauth
