<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use App\Models\Produkt;

// Homepage and product detail pages.
class ProductController extends Controller
{
    // Build homepage sections (featured, bestsellers, sale).
    public function index()
    {
        $featured = Produkt::with(['hlavnyObrazok', 'kategoria'])
            ->orderBy('id')
            ->limit(12)
            ->get();

        $bestsellers = Produkt::with(['hlavnyObrazok', 'kategoria'])
            ->orderBy('sold_count', 'desc')
            ->limit(12)
            ->get();

        $onSale = Produkt::with(['hlavnyObrazok', 'kategoria'])
            ->where('discount', '>', 0)
            ->limit(12)
            ->get();

        $kategorie = Kategoria::whereNull('parent_id')->get();

        return view('index', [
            'featured' => $featured,
            'bestsellers' => $bestsellers,
            'onSale' => $onSale,
            'kategorie' => $kategorie,
        ]);
    }

    // Load one product with category and images.
    public function show(Produkt $produkt)
    {
        $produkt->load(['obrazky', 'kategoria']);

        return view('product-detail', [
            'produkt' => $produkt,
        ]);
    }
}
