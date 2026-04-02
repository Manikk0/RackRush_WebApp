@extends('layouts.app')

@section('title', 'RackRush - Hrozno biele, bezsemenné')

@section('body-class', 'product-detail-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/product_detail.css') }}">
@endpush

@section('content')
    <main class="container product-detail-container">
        <!-- BREADCRUMBS -->
        <nav aria-label="breadcrumb" class="breadcrumb-section category-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}"><img src="{{ asset('assets/home.png') }}"
                            alt="Domov" class="breadcrumb-home-icon"></a></li>
                <li class="breadcrumb-item"><a href="{{ route('category') }}">Ovocie a zelenina</a></li>
                <li class="breadcrumb-item"><a href="{{ route('category') }}">Ovocie</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hrozno a Melóny</li>
            </ol>
        </nav>

        <!-- PRODUCT MAIN SECTION -->
        <div class="product-main">
            <div class="row">
                <!-- PRODUCT GALLERY -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="product-gallery shadow-sm">
                        <button class="gallery-wishlist-btn" title="Pridať do obľúbených">
                            <img src="{{ asset('assets/heart.png') }}" alt="heart" class="wishlist-icon">
                        </button>

                        <button class="gallery-nav-btn prev" id="gallery-prev">
                            <img src="{{ asset('assets/chevron_right.png') }}" alt="prev" class="icon-rotate-180">
                        </button>

                        <div class="gallery-track-container" id="gallery-container">
                            <div class="gallery-track" id="gallery-track">
                                <img src="{{ asset('assets/grapes_white_tray.png') }}" class="gallery-slide-img"
                                    alt="Hrozno biele 1">
                                <img src="{{ asset('assets/grapes_white_tray.png') }}" class="gallery-slide-img"
                                    alt="Hrozno biele 2">
                                <img src="{{ asset('assets/grapes_white_tray.png') }}" class="gallery-slide-img"
                                    alt="Hrozno biele 3">
                            </div>
                        </div>

                        <button class="gallery-nav-btn next" id="gallery-next">
                            <img src="{{ asset('assets/chevron_right.png') }}" alt="next">
                        </button>

                        <button class="gallery-zoom-btn" id="gallery-zoom">
                            <img src="{{ asset('assets/zoom-in.png') }}" alt="zoom" class="icon-zoom">
                        </button>
                    </div>
                </div>

                <!-- PRODUCT INFO -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <h1 class="product-title">Hrozno biele, bezsemenné</h1>
                        <div class="product-weight-section">
                            <p class="product-weight">500g</p>
                            <p class="product-unit-price">6.38€/kg</p>
                        </div>

                        <div class="product-price">3.19€</div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                            <button class="btn add-to-cart-btn">
                                <img src="{{ asset('assets/shopping-cart.png') }}" alt="cart">
                                Do košíka
                            </button>
                            <button class="btn add-to-list-btn">
                                <img src="{{ asset('assets/task_complete.png') }}" alt="list">
                                Pridať do nákupného zoznamu
                            </button>
                        </div>

                        <div class="product-attributes">
                            <div class="attr-item">
                                <img src="{{ asset('assets/check.png') }}" alt="check" class="icon-sm icon-white">
                                <span>Zaručene čerstvé</span>
                            </div>
                            <div class="attr-item">
                                <img src="{{ asset('assets/gps.png') }}" alt="origin" class="icon-sm icon-white">
                                <span>Zem pôvodu: Peru, JAR</span>
                            </div>
                            <div class="attr-item">
                                <img src="{{ asset('assets/product-code.png') }}" alt="code" class="icon-sm icon-white">
                                <span>Kód produktu: 123456</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCT DESCRIPTION SECTION -->
        <div class="product-details-content shadow-sm">
            <h2 class="section-title">Popis</h2>

            <div class="content-block">
                <p>Sladké biele hrozno bez kôstok.</p>
                <p>Upozorňujeme, že niektoré bobule môžu mať v priebehu roka tmavožltú až takmer karamelovú farbu. Táto
                    farba je spôsobená odrodou v závislosti od intenzity slnečného žiarenia a zvýšeného obsahu cukru v
                    jednotlivých bobuliach. Táto farba nie je vadou. Naopak, hrozno má sladšiu chuť.</p>
            </div>

            <div class="content-block">
                <h3>Použitie</h3>
                <p>Hrozno sa používa hlavne na priamu konzumáciu. Môže sa použiť na zdobenie kanapiek, ako prísada do
                    rôznych nátierok alebo ako ľahká a zdravá desiata.</p>
                <p><strong>Tip!</strong> Pridajte sa k nám a pripravte si lahodný hroznový koláč.</p>
            </div>

            <div class="content-block">
                <div class="recipe-card">
                    <h3>Recept na hroznový koláč</h3>
                    <p>2 bielka vyšľahajte do tuha, žĺtka vymiešajte s 90 g práškového cukru, jedným vanilkovým cukrom a
                        120 g zmäknutého masla do peny. Pridajte 1/4 kg mäkkého tvarohu, 120 g polohrubej múky s práškom
                        do pečiva a tuhé bielka. Cesto rozotrite do vymasteného a múkou vysypaného okrúhleho plechu s
                        vyšším okrajom, navrch položte umyté a osušené hrozno a posypte mandľami. Pečte v predhriatej
                        rúre pri 170 °C asi 40 minút.</p>
                    <p class="mt-3 fw-bold">Dobrú chuť!</p>
                </div>
            </div>
        </div>
    </main>

    <!-- ZOOM MODAL -->
    <div id="zoom-modal" class="zoom-modal">
        <div class="zoom-modal-content">
            <button class="zoom-modal-close-simple" id="zoom-modal-close">&times;</button>
            <img id="zoom-modal-img" src="" alt="Zoomed view">
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/product-detail.js') }}"></script>
@endpush
