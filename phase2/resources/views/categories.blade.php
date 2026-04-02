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
        @php
            $categories = [
                ['name' => 'Ovocie a zelenina', 'icon' => 'vegetable&fruit.png'],
                ['name' => 'Mliečne a chladené', 'icon' => 'dairy.png'],
                ['name' => 'Mäso a ryby', 'icon' => 'meat.png'],
                ['name' => 'Pečivo', 'icon' => 'breads.png'],
                ['name' => 'Trvanlivé potraviny', 'icon' => 'durable_food.png'],
                ['name' => 'Nápoje', 'icon' => 'drinks.png'],
                ['name' => 'Sladké a slané', 'icon' => 'sweet&snacks.png'],
                ['name' => 'Mrazené produkty', 'icon' => 'frozen-food.png'],
                ['name' => 'Pre deti', 'icon' => 'baby.png'],
                ['name' => 'Kozmetika a drogéria', 'icon' => 'cosmetics.png'],
                ['name' => 'Domácnosť', 'icon' => 'household.png'],
                ['name' => 'Pre zvieratá', 'icon' => 'pet-food.png'],
            ];
        @endphp

        @foreach ($categories as $category)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('category') }}" class="category-tile">
                    <div class="category-tile__icon-wrap">
                        <img src="{{ asset('assets/' . $category['icon']) }}" alt="" class="category-tile__icon">
                    </div>
                    <span class="category-tile__name">{{ $category['name'] }}</span>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/index.js') }}"></script>
@endpush
