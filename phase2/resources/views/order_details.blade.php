@extends('layouts.app')

@section('title', 'Detaily objednávky – RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/order_details.css') }}">
@endpush

@section('content')
    <!-- MAIN CONTENT -->
    <main class="od-main container-fluid px-4">

        <!-- BACK LINK -->
        <div class="d-none d-md-flex align-items-center mb-4">
            <a href="{{ route('cart') }}" class="od-back-link">
                <img src="{{ asset('assets/chevron_left.png') }}" class="icon-sm icon-white">
                <span>Späť do košíka</span>
            </a>
        </div>

        <h1 class="od-page-title mb-4">Detaily objednávky</h1>

        <div class="row g-4">
            <!-- FORM SECTIONS -->
            <div class="col-lg-7 col-xl-6">
                <div class="od-accordion" id="orderAccordion">

                    <!-- DELIVERY SECTION -->
                    <div class="od-section" id="section-delivery">
                        <button class="od-section__header" id="btn-delivery" aria-expanded="false"
                            aria-controls="body-delivery">
                            <div class="od-section__icon-wrap">
                                <img src="{{ asset('assets/gps.png') }}" class="icon-sm icon-white">
                            </div>
                            <div class="od-section__title-wrap">
                                <span class="od-section__title">Kam?</span>
                                <span class="od-section__subtitle" id="subtitle-delivery">Vyberte doručenie</span>
                            </div>
                            <div class="od-section__chevron">
                                <img src="{{ asset('assets/chevron_down.png') }}" class="icon-sm icon-white">
                            </div>
                        </button>
                        <div class="od-section__body" id="body-delivery">
                            <form class="od-form" id="form-delivery">
                                <div class="od-form__field">
                                    <label for="delivery-city">Mesto</label>
                                    <input type="text" id="delivery-city" placeholder="">
                                </div>
                                <div class="od-form__field">
                                    <label for="delivery-address">Adresa</label>
                                    <input type="text" id="delivery-address" placeholder="">
                                </div>
                                <div class="od-form__field od-form__field--half">
                                    <label for="delivery-floor">Poschodie</label>
                                    <input type="text" id="delivery-floor" placeholder="">
                                </div>
                                <button type="button" class="od-btn-save" id="save-delivery">Uložiť</button>
                            </form>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                    <!-- USER INFO SECTION -->
                    <div class="od-section" id="section-who">
                        <button class="od-section__header" id="btn-who" aria-expanded="false" aria-controls="body-who">
                            <div class="od-section__icon-wrap">
                                <img src="{{ asset('assets/user.png') }}" class="icon-sm icon-white">
                            </div>
                            <div class="od-section__title-wrap">
                                <span class="od-section__title">Kto?</span>
                                <span class="od-section__subtitle" id="subtitle-who">Vyplnte informácie</span>
                            </div>
                            <div class="od-section__chevron">
                                <img src="{{ asset('assets/chevron_down.png') }}" class="icon-sm icon-white">
                            </div>
                        </button>
                        <div class="od-section__body" id="body-who">
                            <form class="od-form" id="form-who">
                                <div class="od-form__field">
                                    <label for="who-name">Meno a priezvisko</label>
                                    <input type="text" id="who-name" placeholder="">
                                </div>
                                <div class="od-form__field">
                                    <label for="who-phone">Telefón</label>
                                    <input type="tel" id="who-phone" placeholder="">
                                </div>
                                <div class="od-form__field">
                                    <label for="who-email">E-mail</label>
                                    <input type="email" id="who-email" placeholder="">
                                </div>
                                <div class="od-form__checkbox">
                                    <input type="checkbox" id="who-company">
                                    <label for="who-company">Nakúpiť na firmu</label>
                                </div>
                                <div class="od-form__checkbox mt-2">
                                    <input type="checkbox" id="who-create-account"
                                        onchange="document.getElementById('password-field-container').style.display = this.checked ? 'block' : 'none'">
                                    <label for="who-create-account">Vytvoriť účet</label>
                                </div>
                                <div class="od-form__action mt-2 ms-4">
                                    <p class="small text-white opacity-75 m-0">
                                        Už máte účet? <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#loginModal"
                                            class="text-white text-decoration-underline fw-bold">Prihláste sa</a>
                                    </p>
                                </div>
                                <div class="od-form__field mt-3" id="password-field-container" style="display: none;">
                                    <label for="who-password">Heslo pre nový účet</label>
                                    <input type="password" id="who-password" placeholder="">
                                </div>
                                <button type="button" class="od-btn-save" id="save-who">Uložiť</button>
                            </form>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                    <!-- COURIER NOTES SECTION -->
                    <div class="od-section" id="section-courier">
                        <button class="od-section__header" id="btn-courier" aria-expanded="false"
                            aria-controls="body-courier">
                            <div class="od-section__icon-wrap">
                                <img src="{{ asset('assets/speech-bubble.png') }}" class="icon-sm icon-white">
                            </div>
                            <div class="od-section__title-wrap">
                                <span class="od-section__title">Informácie pre kuriéra</span>
                            </div>
                            <div class="od-section__chevron">
                                <img src="{{ asset('assets/chevron_down.png') }}" class="icon-sm icon-white">
                            </div>
                        </button>
                        <div class="od-section__body" id="body-courier">
                            <form class="od-form" id="form-courier">
                                <div class="od-form__field">
                                    <label for="courier-notes">Informácie pre kuriéra</label>
                                    <textarea id="courier-notes" rows="4" placeholder=""></textarea>
                                </div>
                                <button type="button" class="od-btn-save" id="save-courier">Uložiť</button>
                            </form>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                    <!-- PAYMENT SECTION -->
                    <div class="od-section" id="section-payment">
                        <button class="od-section__header" id="btn-payment" aria-expanded="false"
                            aria-controls="body-payment">
                            <div class="od-section__icon-wrap">
                                <img src="{{ asset('assets/credit-card.png') }}" class="icon-sm icon-white">
                            </div>
                            <div class="od-section__title-wrap">
                                <span class="od-section__title">Spôsob platby</span>
                            </div>
                            <div class="od-section__chevron">
                                <img src="{{ asset('assets/chevron_down.png') }}" class="icon-sm icon-white">
                            </div>
                        </button>
                        <div class="od-section__body" id="body-payment">
                            <form class="od-form" id="form-payment">
                                <div class="od-payment-options">
                                    <label class="od-payment-option">
                                        <input type="radio" name="payment" value="card">
                                        <span class="od-payment-option__box">
                                            <img src="{{ asset('assets/credit-card.png') }}" class="icon-sm icon-white">
                                            Platobná karta
                                        </span>
                                    </label>
                                    <label class="od-payment-option">
                                        <input type="radio" name="payment" value="cash">
                                        <span class="od-payment-option__box">
                                            <img src="{{ asset('assets/tag.png') }}" class="icon-sm icon-white">
                                            Dobierka
                                        </span>
                                    </label>
                                    <label class="od-payment-option">
                                        <input type="radio" name="payment" value="transfer">
                                        <span class="od-payment-option__box">
                                            <img src="{{ asset('assets/offer_tag.png') }}" class="icon-sm icon-white">
                                            Bankový prevod
                                        </span>
                                    </label>
                                </div>
                                <button type="button" class="od-btn-save" id="save-payment">Uložiť</button>
                            </form>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                </div>

                <div class="d-lg-none mt-4">
                    <a href="{{ route('order_success') }}" class="od-btn-order w-100 d-block"
                        id="btn-order-mobile">Objednať</a>
                </div>
            </div>

            <!-- ORDER SUMMARY -->
            <div class="col-lg-5 col-xl-4 offset-xl-1">
                <div class="od-summary">
                    <!-- VOUCHER SECTION -->
                    <button class="od-voucher-btn" id="btn-voucher">
                        <img src="{{ asset('assets/plus.png') }}" class="icon-sm icon-white">
                        Vložiť kód
                    </button>
                    <div class="od-voucher-input" id="voucher-input-wrap">
                        <input type="text" placeholder="Zľavový kód..." id="voucher-code">
                        <button id="btn-apply-voucher">Uplatniť</button>
                    </div>

                    <div class="od-summary__divider"></div>

                    <!-- SUMMARY DETAILS -->
                    <div class="od-summary__row">
                        <span>Hodnota košíku</span>
                        <span id="od-subtotal">0€</span>
                    </div>
                    <div class="od-summary__row">
                        <span>Poplatok za doručenie</span>
                        <span id="od-shipping">0€</span>
                    </div>
                    <div class="od-summary__row">
                        <span>Ušetrené peniaze</span>
                        <span id="od-savings">0€</span>
                    </div>

                    <div class="od-summary__divider"></div>

                    <div class="od-summary__total">
                        <span>Celková cena</span>
                        <strong id="od-total">0€</strong>
                    </div>

                    <a href="{{ route('order_success') }}" class="od-btn-order mt-4 d-none d-lg-block w-100"
                        id="btn-order-desktop">Objednať</a>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/order_details.js') }}"></script>
@endpush
