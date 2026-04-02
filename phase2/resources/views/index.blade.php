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
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/vegetable&fruit.png') }}"
                            alt="Ovocie a zelenina"></div><span class="category-card__name">Ovocie
                        a<br>zelenina</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/dairy.png') }}"
                            alt="Mliečne a chladené">
                    </div><span class="category-card__name">Mliečne a<br>chladené</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/meat.png') }}" alt="Mäso a ryby">
                    </div><span class="category-card__name">Mäso a<br>ryby</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/breads.png') }}" alt="Pečivo">
                    </div><span class="category-card__name">Pečivo</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/durable_food.png') }}"
                            alt="Trvanlivé potraviny"></div><span
                        class="category-card__name">Trvanlivé<br>potraviny</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/drinks.png') }}" alt="Nápoje">
                    </div><span class="category-card__name">Nápoje</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/sweet&snacks.png') }}"
                            alt="Sladké a slané"></div><span class="category-card__name">Sladké a<br>slané</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/frozen-food.png') }}"
                            alt="Mrazené produkty">
                    </div><span class="category-card__name">Mrazené<br>produkty</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/baby.png') }}" alt="Pre deti">
                    </div><span class="category-card__name">Pre deti</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/cosmetics.png') }}"
                            alt="Kozmetika a drogéria"></div><span class="category-card__name">Kozmetika
                        a<br>drogéria</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/household.png') }}"
                            alt="Domácnosť"></div>
                    <span class="category-card__name">Domácnosť</span>
                </a>
                <a href="{{ route('category') }}" class="category-card">
                    <div class="category-card__image-wrap"><img src="{{ asset('assets/pet-food.png') }}"
                            alt="Pre zvieratá"></div>
                    <span class="category-card__name">Pre<br>zvieratá</span>
                </a>
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

    <div id="products-top-1"></div>

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

    <div id="products-top-2"></div>

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

    <div id="products-bottom"></div>
@endsection

@push('scripts')
    <script src="{{ asset('js/index.js') }}"></script>
@endpush
