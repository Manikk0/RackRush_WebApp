@extends('layouts.app')

@section('title', 'Vyhľadávanie – RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/category.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/search.css') }}">
@endpush

@section('content')
    <div class="search-page">
        @if($q === '')
            <div class="search-hint-card">
                <div class="search-hint-card__icon">?</div>
                <p class="mb-0">Zadajte text do vyhľadávania v hornom paneli a stlačte <strong>Hľadať</strong>.</p>
            </div>
        @else
            @php
                $pocet = $produkty->total();
                $produktSlovo = $pocet === 1 ? 'produkt' : ($pocet >= 2 && $pocet <= 4 ? 'produkty' : 'produktov');
            @endphp
            <p class="search-meta">
                Pre výraz <strong>„{{ $q }}“</strong> sme našli
                <strong>{{ $pocet }}</strong>
                {{ $produktSlovo }}.
            </p>

            <div class="product-row" id="search-products">
                @forelse($produkty as $produkt)
                    @include('partials.product-card', ['produkt' => $produkt])
                @empty
                    <div class="search-empty-state">
                        <p class="mb-2">Nič sme nenašli. Skúste iné slovo alebo sa pozrite do
                            <a href="{{ route('categories') }}">kategórií</a>.
                        </p>
                    </div>
                @endforelse
            </div>

            @if($produkty->hasPages())
                <div class="search-pagination-wrap">
                    <nav aria-label="Stránkovanie výsledkov">
                        <ul class="pagination custom-pagination justify-content-center mb-0 flex-wrap">
                            @if($produkty->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $produkty->previousPageUrl() }}" rel="prev"
                                        aria-label="Predchádzajúca">&laquo;</a>
                                </li>
                            @endif

                            @for($page = 1; $page <= $produkty->lastPage(); $page++)
                                <li class="page-item {{ $page === $produkty->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $produkty->url($page) }}">{{ $page }}</a>
                                </li>
                            @endfor

                            @if($produkty->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $produkty->nextPageUrl() }}" rel="next"
                                        aria-label="Nasledujúca">&raquo;</a>
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
        @endif
    </div>
@endsection
