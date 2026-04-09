<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** Najviac kusov jedneho produktu v košíku (rovnako ako na fronte). */
    private const MAX_KS = 99;

    public function index()
    {
        $kosik = session('cart', []);

        return view('cart', [
            'cart' => $kosik,
        ]);
    }

    /**
     * Pridanie alebo úprava produktu v košíku (session).
     *
     * JSON body často: { "quantity": 5, "exact": true }
     * - exact true  -> nastav množstvo presne na quantity
     * - exact false -> pripočítaj quantity k tomu, čo už v košíku je
     *
     * Odstránenie riadku: exact true a quantity 0 (alebo menej).
     */
    public function add(Request $request, $id)
    {
        $idProduktu = (int) $id;

        $produkt = Produkt::findOrFail($idProduktu);

        $kosik = session('cart', []);

        // Koľko chceme (ak parameter chýba, berieme 1)
        $mnozstvo = (int) $request->input('quantity', 1);
        if ($mnozstvo < 0) {
            $mnozstvo = 0;
        }
        if ($mnozstvo > self::MAX_KS) {
            $mnozstvo = self::MAX_KS;
        }

        $jePresne = $request->boolean('exact');

        // --- Odstránenie z košíka (používa product-card aj košík pri množstve 0) ---
        if ($jePresne === true && $mnozstvo <= 0) {
            if (array_key_exists($idProduktu, $kosik)) {
                unset($kosik[$idProduktu]);
            }
            session(['cart' => $kosik]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'cart' => $kosik,
                ]);
            }

            return redirect()->back();
        }

        // --- Už je v košíku: uprav množstvo ---
        if (array_key_exists($idProduktu, $kosik) === true) {
            if ($jePresne === true) {
                $kosik[$idProduktu]['quantity'] = $mnozstvo;
            } else {
                $kosik[$idProduktu]['quantity'] = $kosik[$idProduktu]['quantity'] + $mnozstvo;
            }
        } else {
            // --- Nový riadok (nepridávame nič, ak by množstvo bolo 0 bez presného módu) ---
            if ($mnozstvo <= 0) {
                session(['cart' => $kosik]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'cart' => $kosik,
                    ]);
                }

                return redirect()->back();
            }

            $kosik[$idProduktu] = $this->polozkaKosikaZProduktu($produkt, $mnozstvo);
        }

        // --- Spoločné pravidlá: max 99, pri 0 riadok zmazať ---
        if (array_key_exists($idProduktu, $kosik) === true) {
            if ($kosik[$idProduktu]['quantity'] > self::MAX_KS) {
                $kosik[$idProduktu]['quantity'] = self::MAX_KS;
            }
            if ($kosik[$idProduktu]['quantity'] <= 0) {
                unset($kosik[$idProduktu]);
            }
        }

        session(['cart' => $kosik]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $kosik,
            ]);
        }

        return redirect()->back()->with('success', 'Produkt pridaný do košíka!');
    }

    /**
     * Jeden záznam v session košíku (jednoduché pole, aby s ním vedel pracovať aj JS).
     */
    private function polozkaKosikaZProduktu(Produkt $produkt, int $mnozstvo): array
    {
        $obrazok = 'assets/grapes_white_tray.png';
        if ($produkt->hlavnyObrazok !== null) {
            $obrazok = $produkt->hlavnyObrazok->url;
        }

        return [
            'name' => $produkt->name,
            'quantity' => $mnozstvo,
            'price' => $produkt->cena_po_zlave,
            'old_price' => $produkt->price,
            'discount' => $produkt->discount,
            'image' => $obrazok,
            'weight' => $produkt->mnozstvo_display ?? '1 ks',
        ];
    }

    public function remove(Request $request, $id)
    {
        $idProduktu = (int) $id;

        $kosik = session('cart', []);

        if (array_key_exists($idProduktu, $kosik) === true) {
            unset($kosik[$idProduktu]);
            session(['cart' => $kosik]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => session('cart', []),
            ]);
        }

        return redirect()->back()->with('success', 'Produkt odstránený.');
    }

    public function emptyCart(Request $request)
    {
        session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => [],
            ]);
        }

        return redirect()->back()->with('success', 'Košík bol vyprázdnený.');
    }

    public function getCart()
    {
        return response()->json(session('cart', []));
    }
}
