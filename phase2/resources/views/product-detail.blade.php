@extends('layouts.app')

@section('title', 'RackRush - ' . $produkt->name)

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
                <li class="breadcrumb-item"><a href="{{ route('category', $produkt->kategoria->id) }}">{{ $produkt->kategoria->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $produkt->name }}</li>
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
                                @forelse($produkt->obrazky as $obrazok)
                                    <img src="{{ $obrazok->image_url }}" class="gallery-slide-img" alt="{{ $produkt->name }}">
                                @empty
                                    <img src="{{ asset('assets/grapes_white_tray.png') }}" class="gallery-slide-img" alt="{{ $produkt->name }}">
                                @endforelse
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
                        <h1 class="product-title">{{ $produkt->name }}</h1>
                        <div class="product-weight-section">
                            <p class="product-weight">{{ $produkt->mnozstvo_display }}</p>
                            @if($produkt->cenaNaJednotku)
                                <p class="product-unit-price">{{ $produkt->cenaNaJednotku }}</p>
                            @endif
                        </div>

                        @if($produkt->discount > 0)
                            <div class="product-sale-price">
                                <div class="product-sale-price__current">{{ number_format($produkt->cena_po_zlave, 2) }}€</div>
                                <div class="product-sale-price__meta">
                                    <span class="product-sale-price__old">{{ number_format($produkt->price, 2) }}€</span>
                                    <span class="product-sale-price__badge">-{{ $produkt->discount }}%</span>
                                </div>
                            </div>
                        @else
                            <div class="product-price">{{ number_format($produkt->price, 2) }}€</div>
                        @endif

                        @php
                            $cart = session()->get('cart', []);
                            $inCart = isset($cart[$produkt->id]);
                            $qty = $inCart ? $cart[$produkt->id]['quantity'] : 0;
                        @endphp

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" id="detail-qty-input" value="1" min="1" max="99" class="form-control text-center" style="width: 70px; height: 44px; font-weight: bold;">
                                <button class="btn add-to-cart-btn" style="height: 44px; display: flex; align-items: center; justify-content: center;" onclick="addToCartClick({{ $produkt->id }})">
                                    <img src="{{ asset('assets/shopping-cart.png') }}" alt="cart" style="margin-right: 8px;">
                                    Do košíka
                                </button>
                            </div>

                            <button class="btn add-to-list-btn" style="height: 44px;">
                                <img src="{{ asset('assets/task_complete.png') }}" alt="list">
                                Pridať do nákupného zoznamu
                            </button>
                        </div>

                        <div class="product-attributes">
                            <div class="attr-item">
                                <img src="{{ asset('assets/check.png') }}" alt="check" class="icon-sm icon-white">
                                <span>Zaručene čerstvé</span>
                            </div>
                            @if($produkt->country_of_origin)
                            <div class="attr-item">
                                <img src="{{ asset('assets/gps.png') }}" alt="origin" class="icon-sm icon-white">
                                <span>Zem pôvodu: {{ $produkt->country_of_origin }}</span>
                            </div>
                            @endif
                            <div class="attr-item">
                                <img src="{{ asset('assets/product-code.png') }}" alt="code" class="icon-sm icon-white">
                                <span>Kód produktu: {{ $produkt->product_code }}</span>
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
                <p>{{ $produkt->description ?? 'Popis produktu nie je k dispozícii.' }}</p>
            </div>

            @if($produkt->recipe)
                <div class="recipe-content-block mt-4">
                    <h3 class="recipe-subtitle">Použitie</h3>
                    <p>{{ $produkt->name }} sa používa hlavne na priamu konzumáciu. Môže sa použiť na zdobenie, ako prísada do rôznych nátierok alebo ako ľahká a zdravá desiata.</p>
                    <p class="recipe-tip"><strong>Tip!</strong> Pridajte sa k nám a pripravte si chutné jedlo s týmto produktom.</p>
                </div>

                <div class="recipe-card">
                    <h3>Recept s produktom {{ $produkt->name }}</h3>
                    <p>{{ $produkt->recipe }}</p>
                    <p class="recipe-goodtaste">Dobrú chuť!</p>
                </div>
            @endif
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
    <script>
        var CART_ADD_BASE = @json(url('/cart/add'));
        function addToCartClick(id) {
            var input = document.getElementById('detail-qty-input');
            var amount = parseInt(input.value, 10);
            if (isNaN(amount) || amount <= 0) {
                amount = 1;
            }

            var meta = document.querySelector('meta[name="csrf-token"]');
            var csrf = meta ? meta.getAttribute('content') : '';

            fetch(CART_ADD_BASE + '/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ quantity: amount }),
            })
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    if (!data.success) {
                        return;
                    }
                    input.value = 1;
                    if (typeof loadCartPopup === 'function') {
                        loadCartPopup();
                    }
                });
        }
    </script>
@endpush
