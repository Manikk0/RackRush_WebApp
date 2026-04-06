<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use App\Models\Produkt;

class ProductController extends Controller
{
    // Homepage - show featured/sale products grouped
    public function index()
    {
        $featured    = Produkt::with(['hlavnyObrazok', 'kategoria'])->orderBy('id')->limit(12)->get();
        $bestsellers = Produkt::with(['hlavnyObrazok', 'kategoria'])->orderBy('sold_count', 'desc')->limit(12)->get();
        $onSale      = Produkt::with(['hlavnyObrazok', 'kategoria'])->where('discount', '>', 0)->limit(12)->get();
        $kategorie   = Kategoria::whereNull('parent_id')->get();

        return view('index', compact('featured', 'bestsellers', 'onSale', 'kategorie'));
    }

    // Single product detail page
    public function show(Produkt $produkt)
    {
        $produkt->load(['obrazky', 'kategoria']);
        return view('product-detail', compact('produkt'));
    }
}
