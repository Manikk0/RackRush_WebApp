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
        $sort = request()->get('sort', 'odporucane');
        $perPage = 16;

        $query = Produkt::with(['hlavnyObrazok'])
            ->where('category_id', $kategoria->id);

        $this->applyPriceFilter($query);
        $this->applyOriginFilter($query);
        $this->applyWeightFilter($query);
        $this->applyPlasticFilter($query);

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
            'activeOrigins' => request()->input('origin', []),
            'activeWeights' => request()->input('weight', []),
            'activePlasticFree' => request()->input('plastic_free'),
            'priceMin' => request()->input('price_min'),
            'priceMax' => request()->input('price_max'),
        ]);
    }

    private function applyPriceFilter($query): void
    {
        $priceMin = request()->input('price_min');
        $priceMax = request()->input('price_max');
        $effectivePriceExpression = '(price * (100 - discount) / 100)';

        if ($priceMin !== null && $priceMin !== '') {
            $query->whereRaw($effectivePriceExpression . ' >= ?', [(float) $priceMin]);
        }

        if ($priceMax !== null && $priceMax !== '') {
            $query->whereRaw($effectivePriceExpression . ' <= ?', [(float) $priceMax]);
        }
    }

    private function applyOriginFilter($query): void
    {
        $origins = request()->input('origin', []);
        if (!is_array($origins) || count($origins) === 0) {
            return;
        }

        $query->whereIn('country_of_origin', $origins);
    }

    private function applyWeightFilter($query): void
    {
        $weights = request()->input('weight', []);
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

    private function applyPlasticFilter($query): void
    {
        $plasticFree = request()->input('plastic_free');
        if ($plasticFree === '1') {
            $query->where('is_plastic_free', true);
        } elseif ($plasticFree === '0') {
            $query->where('is_plastic_free', false);
        }
    }

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
