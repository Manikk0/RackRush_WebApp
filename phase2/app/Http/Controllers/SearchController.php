<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        if ($q == null || $q == '') {
            return view('search', [
                'q' => '',
                'produkty' => null,
            ]);
        }

        // Jednoduche vyhladavanie podla nazvu
        $query = Produkt::with(['hlavnyObrazok', 'kategoria']);

        $query->where(function ($qBuilder) use ($q) {
            $qBuilder->where('name', 'LIKE', '%' . $q . '%')
                     ->orWhere('description', 'LIKE', '%' . $q . '%');
        });

        $produkty = $query->orderBy('name')->paginate(16);

        // Pridame query string aby fungovalo strankovanie s vyhladavanim
        $produkty->appends(['q' => $q]);

        return view('search', [
            'q' => $q,
            'produkty' => $produkty,
        ]);
    }
}
