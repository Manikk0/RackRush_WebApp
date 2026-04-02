@extends('layouts.app')

@section('title', 'Košík – RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/cart.css') }}">
@endpush

@section('content')
    <h1 class="cart-page-title" id="cart-page-title">Prehľad objednávky (0)</h1>

    <div class="row g-4">
        <!-- CART ITEMS SECTION -->
        <div class="col-lg-8">
            <div id="cart-items-container">
                <!-- EMPTY STATE -->
                <div class="cart-empty-state" id="cart-empty-state">
                    Váš košík je prázdny.
                </div>
            </div>

            <!-- ACTIONS BAR -->
            <div class="cart-actions">
                <button class="btn-discount" id="btn-discount">
                    <img src="{{ asset('assets/tag.png') }}" class="icon-sm icon-white">
                    Vložiť zľavový kód
                </button>
                <button class="btn-clear" id="btn-clear">
                    <img src="{{ asset('assets/trash.png') }}" class="icon-sm icon-white">
                    Vyprázdniť košík
                </button>
            </div>

            <!-- DISCOUNT INPUT -->
            <div class="discount-input-wrap" id="discount-input-wrap">
                <input type="text" placeholder="Zadajte kód...">
                <button>Uplatniť</button>
            </div>
        </div>

        <!-- SUMMARY SECTION -->
        <div class="col-lg-4">
            <div class="cart-summary">
                <p class="cart-summary__title">Zhrnutie objednávky</p>

                <div class="cart-summary__row">
                    <span>Medzisúčet</span>
                    <span id="summary-subtotal">0,00 €</span>
                </div>
                <div class="cart-summary__row">
                    <span>Doprava</span>
                    <span id="summary-shipping">Zdarma</span>
                </div>
                <div class="cart-summary__row">
                    <span>Zľava</span>
                    <span id="summary-discount">–0,00 €</span>
                </div>

                <div class="cart-summary__row cart-summary__row--total">
                    <span>Spolu</span>
                    <span id="summary-total">0,00 €</span>
                </div>

                <p class="cart-summary__savings">Ušetrené: <span id="summary-savings">0 €</span></p>

                <a href="{{ route('order_details') }}" class="btn-proceed">Pokračovať k platbe a doprave</a>
            </div>
        </div>
    </div>

    <!-- RECOMMENDATIONS SECTION -->
    <section class="recommendations-section">
        <h2 class="recommendations-section__title">Mohlo by sa vám páčiť</h2>
        <div class="product-row" id="recommendations"></div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
@endpush
