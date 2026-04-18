<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use App\Models\ObrazokProduktu;
use App\Models\Produkt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Admin product management controller - handles CRUD operations for products.
class AdminProductController extends Controller
{
    // Display all products in admin dashboard with simple search (case-insensitive on PostgreSQL).
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = Produkt::with(['hlavnyObrazok', 'kategoria']);

        $this->applyAdminProductSearch($query, $search);

        $products = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'products' => $products,
            'search' => $search,
        ]);
    }

    // Load one product with all images for the edit form.
    public function show(int $id)
    {
        $product = Produkt::with(['obrazky', 'kategoria'])->findOrFail($id);

        return response()->json([
            'product' => $product,
        ]);
    }

    // Apply search filter in a grouped WHERE so OR does not break future conditions.
    private function applyAdminProductSearch($query, string $search): void
    {
        if ($search === '') {
            return;
        }

        $escaped = addcslashes($search, '%_\\');
        $pattern = '%'.$escaped.'%';
        $driver = $query->getConnection()->getDriverName();

        $query->where(function ($q) use ($pattern, $driver) {
            if ($driver === 'pgsql') {
                $q->where('name', 'ILIKE', $pattern)
                    ->orWhere('product_code', 'ILIKE', $pattern)
                    ->orWhere('description', 'ILIKE', $pattern);
            } else {
                $q->whereRaw('LOWER(name) LIKE LOWER(?)', [$pattern])
                    ->orWhereRaw('LOWER(COALESCE(product_code, \'\')) LIKE LOWER(?)', [$pattern])
                    ->orWhereRaw('LOWER(COALESCE(description, \'\')) LIKE LOWER(?)', [$pattern]);
            }
        });
    }

    // Store new product in database.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0|max:999.99',
            'stock' => 'required|integer|min:0|max:999',
            'images' => 'required|array|min:2',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $category = Kategoria::firstOrCreate(['name' => $validated['category']]);

            $productCode = 'PRD'.strtoupper(Str::random(8));

            $product = Produkt::create([
                'category_id' => $category->id,
                'product_code' => $productCode,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'quantity' => $validated['stock'],
                'unit' => 'ks',
                'sold_count' => 0,
            ]);

            foreach ($validated['images'] as $index => $image) {
                $filename = 'product_'.$product->id.'_'.time().'_'.$index.'.'.$image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');

                ObrazokProduktu::create([
                    'product_id' => $product->id,
                    'url' => $path,
                    'order' => $index,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produkt bol úspešne pridaný!',
                'product' => $product->load(['hlavnyObrazok', 'kategoria']),
            ]);
        } catch (\Exception $e) {
            \Log::error('Product creation error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Nastala chyba pri pridávaní produktu: '.$e->getMessage(),
            ], 500);
        }
    }

    // Update product text fields, category, and image set (add/remove files).
    public function update(Request $request, int $id)
    {
        $product = Produkt::with('obrazky')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0|max:999.99',
            'stock' => 'required|integer|min:0|max:999',
            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'integer|exists:product_images,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $removeIds = $validated['remove_image_ids'] ?? [];
        $allowedImageIds = $product->obrazky->pluck('id')->all();
        $removeIds = array_values(array_unique(array_map('intval', $removeIds)));
        $removeIds = array_values(array_filter($removeIds, function (int $imageId) use ($allowedImageIds) {
            return in_array($imageId, $allowedImageIds, true);
        }));

        $remainingCount = $product->obrazky->count() - count($removeIds);
        $newImagesCount = $request->hasFile('images') ? count($request->file('images', [])) : 0;
        if ($remainingCount + $newImagesCount < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Produkt musí mať aspoň 2 fotografie (po úprave). Pridajte nové obrázky alebo zrušte odstránenie.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($request, $validated, $product, $removeIds) {
                $category = Kategoria::firstOrCreate(['name' => $validated['category']]);

                $product->update([
                    'category_id' => $category->id,
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'price' => $validated['price'],
                    'quantity' => $validated['stock'],
                ]);

                foreach ($removeIds as $imageId) {
                    $obrazok = ObrazokProduktu::where('product_id', $product->id)
                        ->where('id', (int) $imageId)
                        ->first();
                    if ($obrazok === null) {
                        continue;
                    }
                    if (Storage::disk('public')->exists($obrazok->url)) {
                        Storage::disk('public')->delete($obrazok->url);
                    }
                    $obrazok->delete();
                }

                if ($request->hasFile('images')) {
                    $maxOrder = (int) ObrazokProduktu::where('product_id', $product->id)->max('order');
                    $nextOrder = $maxOrder + 1;

                    foreach ($request->file('images') as $index => $image) {
                        $filename = 'product_'.$product->id.'_'.time().'_'.$index.'.'.$image->getClientOriginalExtension();
                        $path = $image->storeAs('products', $filename, 'public');

                        ObrazokProduktu::create([
                            'product_id' => $product->id,
                            'url' => $path,
                            'order' => $nextOrder + $index,
                        ]);
                    }
                }

                $this->normalizeProductImageOrder($product->id);
            });

            $fresh = Produkt::with(['hlavnyObrazok', 'kategoria', 'obrazky'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Produkt bol úspešne upravený!',
                'product' => $fresh,
            ]);
        } catch (\Exception $e) {
            \Log::error('Product update error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Nastala chyba pri úprave produktu: '.$e->getMessage(),
            ], 500);
        }
    }

    // After image changes, set order 0..n-1 so the "main" image (order 0) stays defined.
    private function normalizeProductImageOrder(int $productId): void
    {
        $images = ObrazokProduktu::where('product_id', $productId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        foreach ($images as $index => $obrazok) {
            $obrazok->update(['order' => $index]);
        }
    }

    // Delete product from database.
    public function destroy($id)
    {
        try {
            $product = Produkt::with('obrazky')->findOrFail($id);

            foreach ($product->obrazky as $obrazok) {
                if (Storage::disk('public')->exists($obrazok->url)) {
                    Storage::disk('public')->delete($obrazok->url);
                }
                $obrazok->delete();
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produkt bol úspešne vymazaný!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nastala chyba pri mazaní produktu: '.$e->getMessage(),
            ], 500);
        }
    }
}
