<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use App\Models\Produkt;
use Illuminate\Http\Request;

// Category list and category product browsing.
class CategoryController extends Controller
{
    // Show top-level categories page.
    public function index()
    {
        $kategorie = Kategoria::whereNull('parent_id')->withCount('produkty')->get();

        return view('categories', [
            'kategorie' => $kategorie,
        ]);
    }

    // Show products in one category with filters/sort/pagination.
    public function show(Request $request, Kategoria $kategoria)
    {
        $sort = $request->input('sort', 'odporucane');
        $perPage = 16;

        $query = Produkt::with(['hlavnyObrazok'])
            ->where('category_id', $kategoria->id);

        $this->applyPriceFilter($request, $query);
        $this->applyOriginFilter($request, $query);
        $this->applyWeightFilter($request, $query);
        $this->applyPlasticFilter($request, $query);

        $effectivePriceExpression = '(price * (100 - discount) / 100)';

        if ($sort === 'najlacnejsie') {
            $query->orderByRaw($effectivePriceExpression . ' asc');
        } elseif ($sort === 'najdrahsie') {
            $query->orderByRaw($effectivePriceExpression . ' desc');
        } else {
            $query->orderBy('sold_count', 'desc');
        }

        $produkty = $query->paginate($perPage)->withQueryString();
        $kategorie = Kategoria::whereNull('parent_id')->get();

        $allProductsInCategory = Produkt::where('category_id', $kategoria->id)->get();
        $availableFilters = $this->buildAvailableFilters($allProductsInCategory);

        return view('category', [
            'kategoria' => $kategoria,
            'produkty' => $produkty,
            'kategorie' => $kategorie,
            'sort' => $sort,
            'availableFilters' => $availableFilters,
            'activeOrigins' => $request->input('origin', []),
            'activeWeights' => $request->input('weight', []),
            'activePlasticFree' => $request->input('plastic_free'),
            'priceMin' => $request->input('price_min'),
            'priceMax' => $request->input('price_max'),
        ]);
    }

    // Apply min/max price filter on discounted price.
    private function applyPriceFilter(Request $request, $query): void
    {
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $effectivePriceExpression = '(price * (100 - discount) / 100)';

        if ($priceMin !== null && $priceMin !== '') {
            $query->whereRaw($effectivePriceExpression . ' >= ?', [(float) $priceMin]);
        }

        if ($priceMax !== null && $priceMax !== '') {
            $query->whereRaw($effectivePriceExpression . ' <= ?', [(float) $priceMax]);
        }
    }

    // Apply origin multiselect filter.
    private function applyOriginFilter(Request $request, $query): void
    {
        $origins = $request->input('origin', []);
        if (!is_array($origins) || count($origins) === 0) {
            return;
        }

        $query->whereIn('country_of_origin', $origins);
    }

    // Apply weight bucket filter.
    private function applyWeightFilter(Request $request, $query): void
    {
        $weights = $request->input('weight', []);
        if (!is_array($weights) || count($weights) === 0) {
            return;
        }

        $query->where(function ($q) use ($weights) {
            foreach ($weights as $weight) {
                if ($weight === 'small') {
                    $q->orWhere('quantity', '<=', 0.3);
                } elseif ($weight === 'medium') {
                    $q->orWhereBetween('quantity', [0.301, 0.75]);
                } elseif ($weight === 'large') {
                    $q->orWhere('quantity', '>', 0.75);
                }
            }
        });
    }

    // Apply radio filter for plastic-free packaging.
    private function applyPlasticFilter(Request $request, $query): void
    {
        $plasticFree = $request->input('plastic_free');
        if ($plasticFree === '1') {
            $query->where('is_plastic_free', true);
        } elseif ($plasticFree === '0') {
            $query->where('is_plastic_free', false);
        }
    }

    // Build filter options visible in sidebar.
    private function buildAvailableFilters($products): array
    {
        $origins = [];
        $hasPlasticData = false;

        foreach ($products as $product) {
            if (!empty($product->country_of_origin) && !in_array($product->country_of_origin, $origins, true)) {
                $origins[] = $product->country_of_origin;
            }

            if ($product->is_plastic_free !== null) {
                $hasPlasticData = true;
            }
        }

        sort($origins);

        return [
            'origins' => $origins,
            'showWeight' => true,
            'showPlastic' => $hasPlasticData,
            'hasAnyProducts' => count($products) > 0,
        ];
    }
}
