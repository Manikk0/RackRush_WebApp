@extends('layouts.app')

@section('title', 'RackRush - Kategória')

@section('body-class', 'category-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/category.css') }}">
@endpush

@section('content')
    <div class="row pt-4">
        <!-- FILTERS SIDEBAR -->
        <div class="col-lg-3 col-xl-2 mb-4 mb-lg-0">
            <!-- MOBILE FILTERS TRIGGER -->
            <div class="d-lg-none mb-3">
                <button
                    class="btn btn-teal w-100 fw-bold d-flex justify-content-between align-items-center py-2 rounded-3 shadow-sm mobile-filter-btn"
                    type="button" data-bs-toggle="collapse" data-bs-target="#filters-container" aria-expanded="false">
                    <span>Filtrovať produkty</span>
                    <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs mobile-filter-chevron">
                </button>
            </div>

            <div class="collapse d-lg-block" id="filters-container">
                <aside class="filters-sidebar">

                    <!-- ORIGIN FILTER -->
                    <div class="filter-group">
                        <button class="filter-btn d-flex justify-content-between align-items-center w-100" type="button"
                            data-bs-toggle="collapse" data-bs-target="#filter-origin" aria-expanded="true">
                            <div>
                                <span class="d-block fw-bold text-start">Zem pôvodu</span>
                                <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">12
                                    možností</span>
                            </div>
                            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                        </button>
                        <div class="collapse show mt-3 filter-content" id="filter-origin">
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="origin1">
                                <label class="form-check-label small" for="origin1">Argentína (7)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="origin2">
                                <label class="form-check-label small" for="origin2">Belgicko (5)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="origin3">
                                <label class="form-check-label small" for="origin3">Čína (1)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="origin4">
                                <label class="form-check-label small" for="origin4">Taliansko (14)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="origin5">
                                <label class="form-check-label small" for="origin5">Francúzsko (3)</label>
                            </div>
                            <div class="collapse" id="more-origins">
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="checkbox" id="origin6">
                                    <label class="form-check-label small" for="origin6">Slovensko (12)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="checkbox" id="origin7">
                                    <label class="form-check-label small" for="origin7">Španielsko (8)</label>
                                </div>
                            </div>
                            <a href="#more-origins" data-bs-toggle="collapse" id="show-more-origins-btn"
                                aria-expanded="false"
                                class="d-block mt-2 small text-decoration-none d-flex align-items-center gap-1 show-more-origins">
                                <span class="show-more-text">Zobraziť viac</span> <img
                                    src="{{ asset('assets/chevron_right.png') }}"
                                    class="icon-xs more-origins-chevron show-more-origins-icon">
                            </a>
                        </div>
                    </div>

                    <!-- WEIGHT FILTER -->
                    <div class="filter-group">
                        <button class="filter-btn d-flex justify-content-between align-items-center w-100 collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter-weight" aria-expanded="false">
                            <div>
                                <span class="d-block fw-bold text-start">Hmotnosť</span>
                                <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">6
                                    možností</span>
                            </div>
                            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                        </button>
                        <div class="collapse mt-3 filter-content" id="filter-weight">
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="weight1">
                                <label class="form-check-label small" for="weight1">100g (2)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="weight2">
                                <label class="form-check-label small" for="weight2">250g (4)</label>
                            </div>
                        </div>
                    </div>

                    <!-- BIO FILTER -->
                    <div class="filter-group">
                        <button class="filter-btn d-flex justify-content-between align-items-center w-100 collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter-bio" aria-expanded="false">
                            <div>
                                <span class="d-block fw-bold text-start">BIO</span>
                                <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">2
                                    možnosti</span>
                            </div>
                            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                        </button>
                        <div class="collapse mt-3 filter-content" id="filter-bio">
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="bio1">
                                <label class="form-check-label small" for="bio1">Áno (12)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="bio2">
                                <label class="form-check-label small" for="bio2">Nie (3)</label>
                            </div>
                        </div>
                    </div>

                    <!-- PACKAGING FILTER -->
                    <div class="filter-group">
                        <button class="filter-btn d-flex justify-content-between align-items-center w-100 collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter-plastic"
                            aria-expanded="false">
                            <div>
                                <span class="d-block fw-bold text-start">Plastový obal</span>
                                <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">2
                                    možnosti</span>
                            </div>
                            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                        </button>
                        <div class="collapse mt-3 filter-content" id="filter-plastic">
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="plas1">
                                <label class="form-check-label small" for="plas1">Bez plastu (8)</label>
                            </div>
                        </div>
                    </div>

                    <!-- ALLERGENS FILTER -->
                    <div class="filter-group">
                        <button class="filter-btn d-flex justify-content-between align-items-center w-100 collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter-allergens"
                            aria-expanded="false">
                            <div>
                                <span class="d-block fw-bold text-start">Alergény</span>
                                <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">9
                                    možností</span>
                            </div>
                            <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                        </button>
                        <div class="collapse mt-3 filter-content" id="filter-allergens">
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="aler1">
                                <label class="form-check-label small" for="aler1">Mlieko</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="aler2">
                                <label class="form-check-label small" for="aler2">Orechy</label>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>
        </div>

        <!-- PRODUCT DISPLAY SECTION -->
        <div class="col-lg-9 col-xl-10 position-relative">

            <!-- BREADCRUMBS -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb category-breadcrumb d-flex align-items-center mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('assets/home.png') }}" class="breadcrumb-home-icon" alt="Domov">
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Ovocie a Zelenina</a></li>
                    <li class="breadcrumb-item"><a href="#">Ovocie</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jablká a Hrušky</li>
                </ol>
            </nav>

            <!-- SORTING -->
            <div class="sort-pills-container">
                <div class="sort-pills">
                    <button class="sort-pill active">Odporúčané</button>
                    <button class="sort-pill">Od najlacnejších</button>
                    <button class="sort-pill">Od najdrahších</button>
                </div>
            </div>

            <!-- PRODUCT GRID -->
            <div class="product-row" id="category-products"></div>

            <!-- PAGINATION AND LOAD MORE -->
            <div class="d-flex flex-column align-items-center mt-5 mb-5 gap-4">
                <button class="product-section__more product-more-btn px-4 py-2">Načítať ďalšie</button>

                <nav aria-label="Stránkovanie kategórie">
                    <ul class="pagination custom-pagination mb-0 align-items-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Predchádzajúca" tabindex="-1">
                                <img src="{{ asset('assets/chevron_right.png') }}" class="pagination-chevron left" alt="">
                            </a>
                        </li>
                        <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item disabled pagination-dots"><span class="page-link">...</span></li>
                        <li class="page-item"><a class="page-link" href="#">12</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Nasledujúca">
                                <img src="{{ asset('assets/chevron_right.png') }}" class="pagination-chevron right"
                                    alt="">
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/category.js') }}"></script>
@endpush
