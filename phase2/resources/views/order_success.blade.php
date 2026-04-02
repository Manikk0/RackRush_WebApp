@extends('layouts.app')

@section('title', 'Objednávka potvrdená – RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/order_success.css') }}">
@endpush

@section('content')
    <!-- MAIN CONTENT -->
    <main class="os-main container-fluid px-3">
        <!-- SUCCESS CARD -->
        <div class="os-card">

            <!-- CHECK ICON -->
            <div class="os-check-wrap">
                <img src="{{ asset('assets/check.png') }}" alt="Hotovo">
            </div>

            <p class="os-eyebrow">Ďakujeme</p>
            <h1 class="os-title">Vaša objednávka je potvrdená!</h1>
            <p class="os-subtitle">Potvrdenie sme vám odoslali na váš e-mail.</p>
            <p class="os-order-number">Číslo objednávky: <strong>#ORD-2026-84512</strong></p>

            <div class="os-divider"></div>

            <!-- ORDER SUMMARY -->
            <div class="os-summary">
                <p class="os-summary-title">Zhrnutie objednávky</p>

                <div class="os-summary-item">
                    <span class="os-summary-name">Názov produktu 1</span>
                    <span class="os-summary-qty">× 1</span>
                    <span class="os-summary-price">€0.00</span>
                </div>

                <div class="os-summary-total">
                    <span>Celková suma</span>
                    <span>€0.00</span>
                </div>
            </div>

            <!-- ORDER PROGRESS -->
            <div class="os-progress" role="list">
                <!-- STEP 1 - CONFIRMED -->
                <div class="os-progress-step is-active" role="listitem">
                    <div class="os-step-dot">
                        <img src="{{ asset('assets/check.png') }}" alt="">
                    </div>
                    <span class="os-step-label">Objednávka<br>potvrdená</span>
                </div>

                <!-- STEP 2 - SHIPPED -->
                <div class="os-progress-step" role="listitem">
                    <div class="os-step-dot">
                        <img src="{{ asset('assets/task_complete.png') }}" alt="">
                    </div>
                    <span class="os-step-label">Objednávka<br>odoslaná</span>
                </div>

                <!-- STEP 3 - ON THE WAY -->
                <div class="os-progress-step" role="listitem">
                    <div class="os-step-dot">
                        <img src="{{ asset('assets/gps.png') }}" alt="">
                    </div>
                    <span class="os-step-label">Na<br>ceste</span>
                </div>

                <!-- STEP 4 - DELIVERED -->
                <div class="os-progress-step" role="listitem">
                    <div class="os-step-dot">
                        <img src="{{ asset('assets/bell.png') }}" alt="">
                    </div>
                    <span class="os-step-label">Objednávka<br>doručená</span>
                </div>
            </div>

            <!-- DELIVERY DATE -->
            <p class="os-delivery">
                Predpokladaný dátum doručenia:
                <strong>20. marca 2026</strong>
            </p>

            <!-- BACK TO HOME -->
            <a href="{{ route('index') }}" class="os-btn-home" id="btn-back-home">
                <img src="{{ asset('assets/chevron_left.png') }}" class="icon-sm" alt="">
                Späť na hlavnú stránku
            </a>

        </div>
    </main>
@endsection
