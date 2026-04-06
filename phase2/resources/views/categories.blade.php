@extends('layouts.app')

@section('title', 'RackRush - Dostupné kategórie')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/categories.css') }}">
@endpush

@section('content')
    <div class="page-title-section">
        <h1 class="page-title">Dostupné kategórie</h1>
        <p class="page-subtitle">Vyberte si z našej bohatej ponuky čerstvých a kvalitných produktov</p>
    </div>

    <!-- CATEGORIES GRID -->
    <div class="row g-4 px-md-5">
        @foreach ($kategorie as $kat)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('category', $kat->id) }}" class="category-tile">
                    <div class="category-tile__icon-wrap">
                        <img src="{{ asset($kat->image ?? 'assets/vegetable&fruit.png') }}" alt="" class="category-tile__icon">
                    </div>
                    <span class="category-tile__name">{{ $kat->name }}</span>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/index.js') }}"></script>
@endpush
