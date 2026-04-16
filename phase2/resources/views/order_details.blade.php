@extends('layouts.app')

@section('title', 'Detaily objednávky – RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/order_details.css') }}">
@endpush

@section('content')
    @php
        $authUser = Auth::user();
        $defaultCustomerName = '';
        $defaultCustomerEmail = '';
        $defaultCustomerPhone = '';
        if ($authUser !== null) {
            $defaultCustomerName = trim(($authUser->first_name ?? '') . ' ' . ($authUser->last_name ?? ''));
            $defaultCustomerEmail = $authUser->email ?? '';
            $defaultCustomerPhone = $authUser->phone ?? '';
        }
    @endphp

    <main class="od-main container-fluid px-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Formulár obsahuje chyby:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-none d-md-flex align-items-center mb-4">
            <a href="{{ route('cart') }}" class="od-back-link">
                <img src="{{ asset('assets/chevron_left.png') }}" class="icon-sm icon-white">
                <span>Späť do košíka</span>
            </a>
        </div>

        <h1 class="od-page-title mb-4">Detaily objednávky</h1>

        <form class="row g-4" method="POST" action="{{ route('checkout.place') }}">
            @csrf
            <div class="col-lg-7 col-xl-6">
                <div class="od-accordion" id="orderAccordion">

                    <div class="od-section" id="section-delivery">
                        <button type="button" class="od-section__header" id="btn-delivery" aria-expanded="false"
                            aria-controls="body-delivery">
                            <div class="od-section__icon-wrap">
                                <img src="{{ asset('assets/gps.png') }}" class="icon-sm icon-white">
                            </div>
                            <div class="od-section__title-wrap">
                                <span class="od-section__title">Kam?</span>
                                <span class="od-section__subtitle" id="subtitle-delivery">Vyplňte adresu</span>
                            </div>
                            <div class="od-section__chevron">
                                <img src="{{ asset('assets/chevron_down.png') }}" class="icon-sm icon-white">
                            </div>
                        </button>
                        <div class="od-section__body" id="body-delivery">
                            <div class="od-form" id="form-delivery">
                                <div class="od-form__field">
                                    <label for="delivery-city">Mesto</label>
                                    <input type="text" id="delivery-city" name="delivery_city" value="{{ old('delivery_city') }}">
                                </div>
                                <div class="od-form__field">
                                    <label for="delivery-address">Adresa</label>
                                    <input type="text" id="delivery-address" name="delivery_address" value="{{ old('delivery_address') }}">
                                </div>
                                <div class="od-form__field od-form__field--half">
                                    <label for="delivery-floor">Poschodie</label>
                                    <input type="number" min="0" step="1" id="delivery-floor" name="delivery_floor" value="{{ old('delivery_floor') }}">
                                </div>
                                <button type="button" class="od-btn-save" id="save-delivery">Uložiť</button>
                            </div>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                    <div class="od-section" id="section-who">
                        <button type="button" class="od-section__header" id="btn-who" aria-expanded="false" aria-controls="body-who">
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
                            <div class="od-form" id="form-who">
                                <div class="od-form__field">
                                    <label for="who-name">Meno a priezvisko</label>
                                    <input type="text" id="who-name" name="customer_name" value="{{ old('customer_name', $defaultCustomerName) }}">
                                </div>
                                <div class="od-form__field">
                                    <label for="who-phone">Telefón</label>
                                    <input type="tel" id="who-phone" name="customer_phone" value="{{ old('customer_phone', $defaultCustomerPhone) }}">
                                </div>
                                <div class="od-form__field">
                                    <label for="who-email">E-mail</label>
                                    <input type="email" id="who-email" name="customer_email" value="{{ old('customer_email', $defaultCustomerEmail) }}">
                                </div>
                                <div class="od-form__checkbox">
                                    <input type="checkbox" id="who-company">
                                    <label for="who-company">Nakúpiť na firmu</label>
                                </div>
                                <button type="button" class="od-btn-save" id="save-who">Uložiť</button>
                            </div>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                    <div class="od-section" id="section-courier">
                        <button type="button" class="od-section__header" id="btn-courier" aria-expanded="false"
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
                            <div class="od-form" id="form-courier">
                                <div class="od-form__field">
                                    <label for="courier-notes">Informácie pre kuriéra</label>
                                    <textarea id="courier-notes" rows="4" name="courier_note">{{ old('courier_note') }}</textarea>
                                </div>
                                <button type="button" class="od-btn-save" id="save-courier">Uložiť</button>
                            </div>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                    <div class="od-section" id="section-payment">
                        <button type="button" class="od-section__header" id="btn-payment" aria-expanded="false"
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
                            <div class="od-form" id="form-payment">
                                <div class="od-payment-options">
                                    <label class="od-payment-option">
                                        <input type="radio" name="payment_method" value="card" @checked(old('payment_method') === 'card')>
                                        <span class="od-payment-option__box">
                                            <img src="{{ asset('assets/credit-card.png') }}" class="icon-sm icon-white">
                                            Platobná karta
                                        </span>
                                    </label>
                                    <label class="od-payment-option">
                                        <input type="radio" name="payment_method" value="cash" @checked(old('payment_method') === 'cash')>
                                        <span class="od-payment-option__box">
                                            <img src="{{ asset('assets/tag.png') }}" class="icon-sm icon-white">
                                            Dobierka
                                        </span>
                                    </label>
                                    <label class="od-payment-option">
                                        <input type="radio" name="payment_method" value="transfer" @checked(old('payment_method') === 'transfer')>
                                        <span class="od-payment-option__box">
                                            <img src="{{ asset('assets/offer_tag.png') }}" class="icon-sm icon-white">
                                            Bankový prevod
                                        </span>
                                    </label>
                                </div>
                                <button type="button" class="od-btn-save" id="save-payment">Uložiť</button>
                            </div>
                        </div>
                    </div>
                    <div class="od-section__divider"></div>

                </div>

                <div class="d-lg-none mt-4">
                    <button type="submit" class="od-btn-order w-100 d-block border-0" id="btn-order-mobile">Objednať</button>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4 offset-xl-1">
                <div class="od-summary">
                    <button type="button" class="od-voucher-btn" id="btn-voucher">
                        <img src="{{ asset('assets/plus.png') }}" class="icon-sm icon-white">
                        Vložiť kód
                    </button>
                    <div class="od-voucher-input" id="voucher-input-wrap">
                        <input type="text" placeholder="Zľavový kód..." id="voucher-code">
                        <button type="button" id="btn-apply-voucher">Uplatniť</button>
                    </div>

                    <div class="od-summary__divider"></div>

                    @php
                        $subtotal = 0;
                        $saved = 0;
                        foreach (session('cart', []) as $item) {
                            $subtotal += $item['price'] * $item['quantity'];
                            if (($item['discount'] ?? 0) > 0 && isset($item['old_price'])) {
                                $saved += ($item['old_price'] - $item['price']) * $item['quantity'];
                            }
                        }
                    @endphp
                    <div class="od-summary__row">
                        <span>Hodnota košíku</span>
                        <span id="od-subtotal">{{ number_format($subtotal, 2) }}€</span>
                    </div>
                    <div class="od-summary__row">
                        <span>Poplatok za doručenie</span>
                        <span id="od-shipping">{{ number_format($shippingFee, 2) }}€</span>
                    </div>
                    <div class="od-summary__row">
                        <span>Ušetrené peniaze</span>
                        <span id="od-savings">{{ number_format($saved, 2) }}€</span>
                    </div>

                    <div class="od-summary__divider"></div>

                    <div class="od-summary__total">
                        <span>Celková cena</span>
                        <strong id="od-total">{{ number_format($subtotal + $shippingFee, 2) }}€</strong>
                    </div>

                    <button type="submit" class="od-btn-order mt-4 d-none d-lg-block w-100 border-0" id="btn-order-desktop">Objednať</button>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/order_details.js') }}"></script>
@endpush
