<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

// Product search endpoint with diacritic-insensitive matching.
class SearchController extends Controller
{
    // Render search page and query products by name/code.
    public function index(Request $request)
    {
        $queryText = (string) $request->input('q', '');
        $queryText = trim($queryText);

        if ($queryText === '') {
            return view('search', [
                'q' => '',
                'produkty' => null,
            ]);
        }

        $normalizedQuery = $this->normalizeForSearch($queryText);

        $query = Produkt::with(['hlavnyObrazok', 'kategoria']);
        $query->where(function ($builder) use ($queryText, $normalizedQuery) {
            $builder->whereRaw(
                $this->sqlNormalizeExpression('name') . ' LIKE ?',
                ['%' . $normalizedQuery . '%']
            )->orWhereRaw(
                'LOWER(COALESCE(product_code, \'\')) LIKE ?',
                ['%' . mb_strtolower($queryText) . '%']
            );
        });

        $produkty = $query->orderBy('name')->paginate(16);
        $produkty->appends(['q' => $queryText]);

        return view('search', [
            'q' => $queryText,
            'produkty' => $produkty,
        ]);
    }

    // Normalize text to lowercase ASCII-like form.
    private function normalizeForSearch(string $text): string
    {
        $text = mb_strtolower($text);

        return strtr($text, [
            'á' => 'a', 'ä' => 'a', 'à' => 'a', 'â' => 'a',
            'č' => 'c',
            'ď' => 'd',
            'é' => 'e', 'ě' => 'e', 'è' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i',
            'ĺ' => 'l', 'ľ' => 'l',
            'ň' => 'n',
            'ó' => 'o', 'ô' => 'o', 'ò' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ŕ' => 'r',
            'š' => 's',
            'ť' => 't',
            'ú' => 'u', 'ů' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ý' => 'y',
            'ž' => 'z',
        ]);
    }

    // SQL expression that normalizes one DB text column.
    private function sqlNormalizeExpression(string $column): string
    {
        return "TRANSLATE(LOWER(COALESCE($column, '')), 'áäàâčďéěèêíìîĺľňóôòõöŕšťúůùûüýž', 'aaaacdeeeeiiillnooooorstuuuuuyz')";
    }
}
