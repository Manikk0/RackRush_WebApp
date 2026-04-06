@extends('layouts.app')

@section('title', 'RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
@endpush

@section('content')
    <!-- CATEGORIES SLIDER -->
    <div id="categories-container" class="mb-5 position-relative">
        <button class="categories-nav categories-nav--prev" id="categories-prev" aria-label="Scroll left">
            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-sm icon-theme icon-flip" alt="">
        </button>
        <div class="categories-scroll-wrapper">
            <div class="categories-slider">
                @foreach($kategorie as $kat)
                <a href="{{ route('category', $kat->id) }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset($kat->image ?? 'assets/vegetable&fruit.png') }}" alt="{{ $kat->name }}"></div>
                    <span class="category-card__name">{{ $kat->name }}</span>
                </a>
                @endforeach
                <!-- PLACEHOLDERS -->
                <div class="category-card category-card--placeholder">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/plus.png') }}" alt="Coming soon">
                    </div><span class="category-card__name">Pripravujeme...</span>
                </div>
                <div class="category-card category-card--placeholder">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/plus.png') }}" alt="Coming soon">
                    </div><span class="category-card__name">Pripravujeme...</span>
                </div>
                <div class="category-card category-card--placeholder">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/plus.png') }}" alt="Coming soon">
                    </div><span class="category-card__name">Pripravujeme...</span>
                </div>
            </div>
        </div>
        <button class="categories-nav categories-nav--next" id="categories-next" aria-label="Scroll right">
            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-sm icon-theme" alt="">
        </button>
    </div>

    <section class="product-section">
        <h2 class="product-section__title">Pre vás</h2>
        <div class="product-row">
            @foreach($featured as $produkt)
                @include('partials.product-card', ['produkt' => $produkt])
            @endforeach
        </div>
        <div class="product-section__footer">
            <a href="{{ route('categories') }}" class="product-section__more">Zobraziť viac</a>
        </div>
    </section>

    <!-- COMPARISON SECTION -->
    <section class="comparison-section mt-5 mb-5">
        <h2 class="comparison-section__title mb-4">
            Prečo nakupovať u nás?
            <span class="comparison-section__subtitle">Porovnanie našich služieb s konkurenciou</span>
        </h2>
        <div class="comparison-table-wrapper">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th class="feature-col"></th>
                        <th class="basic-col">Bežný obchod</th>
                        <th class="premium-col">RackRush</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Doručenie do 15 minút</td>
                        <td>&ndash;</td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                    </tr>
                    <tr>
                        <td>Vždy čerstvé potraviny</td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                    </tr>
                    <tr>
                        <td>Najširší výber lokálnych produktov</td>
                        <td>&ndash;</td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                    </tr>
                    <tr>
                        <td>Eko balenie bez plastov</td>
                        <td>&ndash;</td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                    </tr>
                    <tr>
                        <td>Zákaznícka podpora 24/7</td>
                        <td>&ndash;</td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                    </tr>
                    <tr>
                        <td>Možnosť vrátenia tovaru kuriérovi</td>
                        <td>&ndash;</td>
                        <td><img src="{{ asset('assets/check.png') }}" class="icon-sm icon-theme"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('categories') }}" class="btn comparison-section__btn">Vyskúšať prvý nákup</a>
        </div>
    </section>

    <section class="product-section">
        <h2 class="product-section__title">Najpredávanejšie</h2>
        <div class="product-row">
            @foreach($bestsellers as $produkt)
                @include('partials.product-card', ['produkt' => $produkt])
            @endforeach
        </div>
        <div class="product-section__footer">
            <a href="{{ route('categories') }}" class="product-section__more">Zobraziť viac</a>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="faq-section mt-5 mb-5">
        <h2 class="faq-section__title text-center mb-4">Často kladené otázky</h2>
        <div class="accordion" id="faqAccordion">

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq1">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit?
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq2">
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco?
                    </button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                        pariatur. Excepteur sint occaecat cupidatat non proident.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq3">
                        Duis aute irure dolor in reprehenderit in voluptate?
                    </button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt
                        lacus. Nulla gravida orci a odio, et tempus feugiat.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq4">
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa?
                    </button>
                </h3>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                        consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product-section">
        <h2 class="product-section__title">Aktuálne v zľave</h2>
        <div class="product-row">
            @forelse($onSale as $produkt)
                @include('partials.product-card', ['produkt' => $produkt])
            @empty
                <p class="text-muted">Momentálne žiadne zľavnené produkty.</p>
            @endforelse
        </div>
        <div class="product-section__footer">
            <a href="{{ route('categories') }}" class="product-section__more">Zobraziť viac</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/index.js') }}?v=2"></script>
@endpush
