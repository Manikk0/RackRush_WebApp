<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use App\Models\Produkt;

class CategoryController extends Controller
{
    // All categories overview page
    public function index()
    {
        $kategorie = Kategoria::whereNull('parent_id')->withCount('produkty')->get();
        return view('categories', compact('kategorie'));
    }

    // Products within a specific category
    public function show(Kategoria $kategoria)
    {
        $sort  = request()->get('sort', 'odporucane');
        $query = Produkt::with(['hlavnyObrazok'])->where('category_id', $kategoria->id);

        match ($sort) {
            'najlacnejsie' => $query->orderBy('price', 'asc'),
            'najdrahsie'   => $query->orderBy('price', 'desc'),
            default        => $query->orderBy('sold_count', 'desc'),
        };

        $produkty   = $query->paginate(16);
        $kategorie  = Kategoria::whereNull('parent_id')->get();
        $zemePovodu = Produkt::where('category_id', $kategoria->id)
                             ->whereNotNull('country_of_origin')
                             ->distinct()
                             ->pluck('country_of_origin');

        return view('category', compact('kategoria', 'produkty', 'kategorie', 'zemePovodu', 'sort'));
    }
}
