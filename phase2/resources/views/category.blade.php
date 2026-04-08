@extends('layouts.app')

@section('title', 'RackRush - ' . $kategoria->name)

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
                    <form method="GET" action="{{ route('category', $kategoria->id) }}" class="filters-form">
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        <!-- PRICE FILTER -->
                        <div class="filter-group">
                            <button class="filter-btn d-flex justify-content-between align-items-center w-100"
                                type="button" data-bs-toggle="collapse" data-bs-target="#filter-price"
                                aria-expanded="true">
                                <div>
                                    <span class="d-block fw-bold text-start">Cena</span>
                                    <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">od
                                        / do</span>
                                </div>
                                <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                            </button>
                            <div class="collapse show mt-3 filter-content" id="filter-price">
                                <div class="d-flex gap-2">
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                                        name="price_min" placeholder="Od €" value="{{ $priceMin }}">
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                                        name="price_max" placeholder="Do €" value="{{ $priceMax }}">
                                </div>
                            </div>
                        </div>

                        <!-- ORIGIN FILTER -->
                        @if(count($availableFilters['origins']) > 0)
                        <div class="filter-group">
                            <button class="filter-btn d-flex justify-content-between align-items-center w-100"
                                type="button" data-bs-toggle="collapse" data-bs-target="#filter-origin"
                                aria-expanded="true">
                                <div>
                                    <span class="d-block fw-bold text-start">Zem pôvodu</span>
                                    <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">{{ count($availableFilters['origins']) }}
                                        možností</span>
                                </div>
                                <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                            </button>
                            <div class="collapse show mt-3 filter-content" id="filter-origin">
                                @foreach($availableFilters['origins'] as $i => $origin)
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="checkbox"
                                        name="origin[]" value="{{ $origin }}" id="origin{{ $i }}"
                                        @checked(in_array($origin, $activeOrigins, true))>
                                    <label class="form-check-label small" for="origin{{ $i }}">{{ $origin }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- WEIGHT FILTER -->
                        @if($availableFilters['showWeight'])
                        <div class="filter-group">
                            <button class="filter-btn d-flex justify-content-between align-items-center w-100 collapsed"
                                type="button" data-bs-toggle="collapse" data-bs-target="#filter-weight"
                                aria-expanded="false">
                                <div>
                                    <span class="d-block fw-bold text-start">Hmotnosť</span>
                                    <span class="d-block text-start small text-muted text-opacity-75 filter-options-count">3
                                        možnosti</span>
                                </div>
                                <img src="{{ asset('assets/chevron_right.png') }}" class="icon-xs filter-chevron">
                            </button>
                            <div class="collapse mt-3 filter-content" id="filter-weight">
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="weight[]"
                                        value="small" id="weight-small" @checked(in_array('small', $activeWeights, true))>
                                    <label class="form-check-label small" for="weight-small">Malé balenie</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="weight[]"
                                        value="medium" id="weight-medium" @checked(in_array('medium', $activeWeights, true))>
                                    <label class="form-check-label small" for="weight-medium">Stredné balenie</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="weight[]"
                                        value="large" id="weight-large" @checked(in_array('large', $activeWeights, true))>
                                    <label class="form-check-label small" for="weight-large">Veľké balenie</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- PACKAGING FILTER -->
                        @if($availableFilters['showPlastic'])
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
                                    <input class="form-check-input custom-checkbox" type="radio" name="plastic_free"
                                        value="1" id="plastic-free-yes" @checked($activePlasticFree === '1')>
                                    <label class="form-check-label small" for="plastic-free-yes">Bez plastu</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input custom-checkbox" type="radio" name="plastic_free"
                                        value="0" id="plastic-free-no" @checked($activePlasticFree === '0')>
                                    <label class="form-check-label small" for="plastic-free-no">Plastový obal</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-grid gap-3 mt-4">
                            <button type="submit" class="btn btn-teal py-2">Použiť filtre</button>
                            <a href="{{ route('category', ['kategoria' => $kategoria->id, 'sort' => $sort]) }}"
                                class="btn btn-outline-custom py-2">Vyčistiť</a>
                        </div>
                    </form>

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
                    <li class="breadcrumb-item active" aria-current="page">{{ $kategoria->name }}</li>
                </ol>
            </nav>

            <!-- SORTING -->
            <div class="sort-pills-container">
                <div class="sort-pills">
                    <a href="{{ route('category', array_merge(['kategoria' => $kategoria->id, 'sort' => 'odporucane'], request()->except(['page', 'sort']))) }}"
                        class="sort-pill {{ $sort === 'odporucane' ? 'active' : '' }} text-decoration-none">Odporúčané</a>
                    <a href="{{ route('category', array_merge(['kategoria' => $kategoria->id, 'sort' => 'najlacnejsie'], request()->except(['page', 'sort']))) }}"
                        class="sort-pill {{ $sort === 'najlacnejsie' ? 'active' : '' }} text-decoration-none">Od najlacnejších</a>
                    <a href="{{ route('category', array_merge(['kategoria' => $kategoria->id, 'sort' => 'najdrahsie'], request()->except(['page', 'sort']))) }}"
                        class="sort-pill {{ $sort === 'najdrahsie' ? 'active' : '' }} text-decoration-none">Od najdrahších</a>
                </div>
            </div>

            <!-- PRODUCT GRID -->
            <div class="product-row" id="category-products">
                @forelse($produkty as $produkt)
                    @include('partials.product-card', ['produkt' => $produkt])
                @empty
                    <div class="category-empty-state">
                        <div class="category-empty-state__icon">!</div>
                        <h3 class="category-empty-state__title">Žiadne výsledky</h3>
                        <p class="category-empty-state__text">V tejto kategórii sa nenašli žiadne produkty pre zvolené filtre.</p>
                        <a href="{{ route('category', ['kategoria' => $kategoria->id]) }}" class="btn btn-outline-custom mt-2 px-4">
                            Vyčistiť filtre
                        </a>
                    </div>
                @endforelse
            </div>

            @if($produkty->hasPages())
            <div class="category-pagination-wrap mt-4 mb-5">
                <nav aria-label="Stránkovanie kategórie">
                    <ul class="pagination custom-pagination justify-content-center mb-0">
                        @if($produkty->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $produkty->previousPageUrl() }}" rel="prev" aria-label="Predchádzajúca">&laquo;</a>
                            </li>
                        @endif

                        @for($page = 1; $page <= $produkty->lastPage(); $page++)
                            <li class="page-item {{ $page === $produkty->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $produkty->url($page) }}">{{ $page }}</a>
                            </li>
                        @endfor

                        @if($produkty->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $produkty->nextPageUrl() }}" rel="next" aria-label="Nasledujúca">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif

        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/category.js') }}?v=2"></script>
@endpush
