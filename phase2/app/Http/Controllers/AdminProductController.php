<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use App\Models\Produkt;
use App\Models\ObrazokProduktu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Admin product management controller - handles CRUD operations for products
class AdminProductController extends Controller
{
    // Display all products in admin dashboard with simple search
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        // Start query with relationships
        $query = Produkt::with(['hlavnyObrazok', 'kategoria']);
        
        // Apply simple search filter
        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('product_code', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
        }
        
        // Get products ordered by latest
        $products = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'products' => $products,
            'search' => $search
        ]);
    }
    
    // Store new product in database
    public function store(Request $request)
    {
        // Validate the incoming request data with 3-digit limits
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0|max:999.99',
            'stock' => 'required|integer|min:0|max:999',
            'images' => 'required|array|min:2',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        try {
            // Find or create category
            $category = Kategoria::firstOrCreate(['name' => $validated['category']]);
            
            // Generate unique product code
            $productCode = 'PRD' . strtoupper(Str::random(8));
            
            // Create the product
            $product = Produkt::create([
                'category_id' => $category->id,
                'product_code' => $productCode,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'quantity' => $validated['stock'],
                'unit' => 'ks',
                'sold_count' => 0
            ]);
            
            // Store product images
            $imagePaths = [];
            foreach ($validated['images'] as $index => $image) {
                // Generate unique filename
                $filename = 'product_' . $product->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                
                // Store image in public/storage/products directory
                $path = $image->storeAs('products', $filename, 'public');
                
                // Create image record in database
                ObrazokProduktu::create([
                    'product_id' => $product->id,
                    'url' => $path,
                    'order' => $index
                ]);
                
                $imagePaths[] = $path;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Produkt bol úspeäne pridaný!',
                'product' => $product->load(['hlavnyObrazok', 'kategoria'])
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            return response()->json([
                'success' => false,
                'message' => 'Chyba validácie',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Product creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Nastala chyba pri pridávaní produktu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Delete product from database
    public function destroy($id)
    {
        try {
            $product = Produkt::findOrFail($id);
            
            // Delete associated images from storage and database
            foreach ($product->obrazky as $obrazok) {
                // Delete file from storage
                if (Storage::disk('public')->exists($obrazok->url)) {
                    Storage::disk('public')->delete($obrazok->url);
                }
                // Delete image record from database
                $obrazok->delete();
            }
            
            // Delete the product
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Produkt bol úspeäne vymazaný!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nastala chyba pri mazaní produktu: ' . $e->getMessage()
            ], 500);
        }
    }
}
