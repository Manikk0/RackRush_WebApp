<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        if ($q === null || $q === '') {
            return view('search', [
                'q' => '',
                'produkty' => null,
            ]);
        }

        $q = trim((string) $q);
        if ($q === '') {
            return view('search', [
                'q' => '',
                'produkty' => null,
            ]);
        }

        // SEARCH: NAME OR PRODUCT_CODE (LIKE)
        $query = Produkt::with(['hlavnyObrazok', 'kategoria']);

        $query->where(function ($qBuilder) use ($q) {
            $qBuilder->where('name', 'LIKE', '%' . $q . '%')
                ->orWhere('product_code', 'LIKE', '%' . $q . '%');
        });

        $produkty = $query->orderBy('name')->paginate(16);

        // PAGINATION: PRESERVE q IN QUERY STRING
        $produkty->appends(['q' => $q]);

        return view('search', [
            'q' => $q,
            'produkty' => $produkty,
        ]);
    }
}
