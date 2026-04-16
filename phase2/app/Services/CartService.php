<?php

namespace App\Services;

use App\Models\Produkt;
use Illuminate\Support\Facades\DB;

// Shared cart logic for session and persistent user cart.
class CartService
{
    // Keep same max quantity as cart controller UI.
    private const MAX_KS = 99;

    // Merge guest session cart into user cart and save both.
    public static function mergeSessionIntoUserAndStore(int $userId, array $sessionCart): array
    {
        $guestQuantities = self::extractQuantitiesFromSession($sessionCart);
        $storedQuantities = self::loadUserQuantities($userId);
        $merged = self::mergeQuantities($storedQuantities, $guestQuantities);

        self::saveUserQuantities($userId, $merged);

        return self::buildSessionCartFromQuantities($merged);
    }

    // Persist the current session cart as the user cart state.
    public static function persistSessionForUser(int $userId, array $sessionCart): void
    {
        $quantities = self::extractQuantitiesFromSession($sessionCart);
        self::saveUserQuantities($userId, $quantities);
    }

    // Load stored user cart and map it to session structure.
    public static function restoreUserCartForSession(int $userId): array
    {
        $quantities = self::loadUserQuantities($userId);

        return self::buildSessionCartFromQuantities($quantities);
    }

    // Convert session cart rows into product quantity map.
    private static function extractQuantitiesFromSession(array $sessionCart): array
    {
        $result = [];

        foreach ($sessionCart as $productId => $item) {
            $id = (int) $productId;
            $qty = (int) ($item['quantity'] ?? 0);
            $qty = max(0, min(self::MAX_KS, $qty));
            if ($id > 0 && $qty > 0) {
                $result[$id] = $qty;
            }
        }

        return $result;
    }

    // Build cart rows expected by existing frontend from quantities.
    private static function buildSessionCartFromQuantities(array $quantities): array
    {
        if (count($quantities) === 0) {
            return [];
        }

        $productIds = array_keys($quantities);
        $products = Produkt::with('hlavnyObrazok')->whereIn('id', $productIds)->get();
        $cart = [];

        foreach ($products as $produkt) {
            $qty = (int) ($quantities[$produkt->id] ?? 0);
            $qty = max(0, min(self::MAX_KS, $qty));
            if ($qty <= 0) {
                continue;
            }

            $image = 'assets/grapes_white_tray.png';
            if ($produkt->hlavnyObrazok !== null) {
                $image = $produkt->hlavnyObrazok->url;
            }

            $cart[$produkt->id] = [
                'name' => $produkt->name,
                'quantity' => $qty,
                'price' => $produkt->cena_po_zlave,
                'old_price' => $produkt->price,
                'discount' => $produkt->discount,
                'image' => $image,
                'weight' => $produkt->mnozstvo_display ?? '1 ks',
            ];
        }

        return $cart;
    }

    // Read stored user cart quantities from DB.
    private static function loadUserQuantities(int $userId): array
    {
        $rows = DB::table('user_carts')
            ->join('user_cart_items', 'user_carts.id', '=', 'user_cart_items.cart_id')
            ->where('user_carts.user_id', $userId)
            ->select('user_cart_items.product_id', 'user_cart_items.quantity')
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $productId = (int) $row->product_id;
            $qty = (int) $row->quantity;
            $qty = max(0, min(self::MAX_KS, $qty));
            if ($productId > 0 && $qty > 0) {
                $result[$productId] = $qty;
            }
        }

        return $result;
    }

    // Save the exact quantity state as user persistent cart.
    private static function saveUserQuantities(int $userId, array $quantities): void
    {
        DB::transaction(function () use ($userId, $quantities) {
            $existing = DB::table('user_carts')->where('user_id', $userId)->first();
            $now = now();

            if ($existing === null) {
                $cartId = DB::table('user_carts')->insertGetId([
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                $cartId = (int) $existing->id;
                DB::table('user_carts')->where('id', $cartId)->update(['updated_at' => $now]);
            }

            DB::table('user_cart_items')->where('cart_id', $cartId)->delete();

            $insertRows = [];
            foreach ($quantities as $productId => $qty) {
                $cleanQty = max(0, min(self::MAX_KS, (int) $qty));
                if ($cleanQty <= 0) {
                    continue;
                }

                $insertRows[] = [
                    'cart_id' => $cartId,
                    'product_id' => (int) $productId,
                    'quantity' => $cleanQty,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (count($insertRows) > 0) {
                DB::table('user_cart_items')->insert($insertRows);
            }

        });
    }

    // Merge two quantity maps without losing either side.
    private static function mergeQuantities(array $base, array $incoming): array
    {
        $merged = $base;

        foreach ($incoming as $productId => $qty) {
            $id = (int) $productId;
            $newQty = (int) $qty;
            $newQty = max(0, min(self::MAX_KS, $newQty));
            if ($id <= 0 || $newQty <= 0) {
                continue;
            }

            $oldQty = (int) ($merged[$id] ?? 0);
            $merged[$id] = min(self::MAX_KS, $oldQty + $newQty);
        }

        return $merged;
    }
}
