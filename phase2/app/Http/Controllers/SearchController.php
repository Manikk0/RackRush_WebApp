<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $raw = $request->input('q', '');
        if (! is_string($raw)) {
            $raw = '';
        }
        $q = trim($raw);
        if (mb_strlen($q) > 120) {
            $q = mb_substr($q, 0, 120);
        }

        if ($q === '') {
            return view('search', [
                'q' => '',
                'produkty' => null,
            ]);
        }

        $like = '%' . addcslashes(mb_strtolower($q), '%_\\') . '%';

        $query = Produkt::with(['hlavnyObrazok', 'kategoria'])
            ->whereRaw('LOWER(name) LIKE ?', [$like])
            ->orderBy('name');

        $produkty = $query->paginate(16)->withQueryString();

        return view('search', [
            'q' => $q,
            'produkty' => $produkty,
        ]);
    }
}
